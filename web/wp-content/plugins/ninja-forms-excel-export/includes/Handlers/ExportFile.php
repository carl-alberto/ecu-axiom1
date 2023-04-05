<?php

namespace NinjaForms\ExcelExport\Handlers;

use NinjaForms\ExcelExport\Contracts\SpreadsheetFactory;
use NinjaForms\ExcelExport\Contracts\SpreadsheetWriterFactory;
use NinjaForms\ExcelExport\Contracts\NfDatabaseQueryFactory;
use NinjaForms\ExcelExport\Contracts\ExtractPostData;
use NinjaForms\ExcelExport\Contracts\FormDefinition;

use NinjaForms\ExcelExport\Wrappers\SpreadsheetObject as SpreadsheetObjectWrapper;

use PhpOffice\PhpSpreadsheet\Writer\BaseWriter;
use PhpOffice\PhpSpreadsheet\Shared\File;

/**
 * Exports Excel file
 */
class ExportFile
{
    /**
     * Submissions to extract per page
     *
     * @var integer
     */
    private $subs_per_page = 200;

    /** @var SpreadsheetFactory */
    private $spreadsheetFactory;

    /** @var SpreadsheetWriterFactory */
    private $spreadsheetWriterFactory;

    /** @var NfDatabaseQueryFactory */
    private $nfDatabaseQueryFactory;

    /** @var ExtractPostData */
    private $extractPostData;

    /** @var BaseWriter */
    private $spreadsheetWriter;

    /** @var FormDefinition */
    private $formDefinition;

    /** @var SpreadsheetObjectWrapper */
    private $spreadsheetObjectWrapper;

    /**
     * Form Id
     *
     * @var string
     */
    private $formId;

    /**
     * Filters
     *
     */
    private $filters;

    /**
     * Is XLS (default is XLSX)
     *
     * @var bool
     */
    private $isXls;

    /**
     * Current iteration
     *
     * @var int
     */
    private $iteration;

    /**
     * Result of submission query
     *
     * @var array
     */
    protected $submissionResults;

    /**
     * NF Fields DB model collection
     *
     * @var array
     */
    protected $fieldsCollection;

    /**
     * Collection of fields selected for export
     *
     * @var array
     */
    protected $selectedFields;

    /**
     * Collection of field types for selected fields
     * 
     * @var array
     */
    protected $selectedFieldTypes;

    /**
     * Current row number
     *
     * @var int
     */
    protected $rowNumber;

    /**
     * Export Excel Spreadsheet file
     */
    public function handle()
    {
        $this->populateProperties();

        $this->loadSpreadsheetObjectsAtCurrentRow();

        // this should be the same wordpress uses and therefore be the saver choice
        File::setUseUploadTempDirectory(true);

        $this->iterateSubmissionsToWriteRows();

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $this->writeTempFile();
        } else {
            $this->saveCompletedFile();
        }

        die();
    }
    /**
     * Iterate through each submission result in collection to write a single row
     */
    protected function iterateSubmissionsToWriteRows(): void
    {
        try {
            foreach ($this->submissionResults as $sub) {
                $columnIndex = 1;

                $columnIndex = $this->writeStandardColumns($columnIndex, $sub);

                $this->iterateFieldsToWriteColumns($columnIndex, $sub);

                $this->rowNumber++;
            }
        } catch (\RuntimeException $e) {
            error_log('ExportFile.iterateSubmissionsToWriteRows');
            error_log($e->getMessage());
        }
    }

    /**
     * Itereate each field in a submission to write columns on a single row
     *
     * @param integer $incomingColumnIndex
     * @param array $submissionArray
     * @return void
     */
    private function iterateFieldsToWriteColumns(int $columnIndex, array $submissionArray): void
    {
        foreach ($this->selectedFields as $field_id) {

            $field_key = '_field_' . $field_id;

            if (!isset($submissionArray[$field_key])) {
                // leave empty cell
                $columnIndex++;
                continue;
            }

            $fieldValue = $submissionArray[$field_key];

            $type = $this->selectedFieldTypes[$field_id];

            if (strpos($type, '-optin')) {
                $type = 'optin';
            }

            if ('repeater' === $type) {

                $columnIndex = $columnIndex + $this->extractRepeaterSubmissionColumns($columnIndex, $fieldValue, $field_id);

                continue;
            }

            $nfField =  $this->fieldsCollection[$field_id];

            $filtered = $this->filterFieldValue($fieldValue, $field_id, $type, $nfField);

            $columnIndex = $this->writeColumnValue($type, $columnIndex, $filtered);
        }
    }

    /**
     * Extract repeater field submission values
     */
    private function extractRepeaterSubmissionColumns(int $columnIndex, string $fieldValue, string $parentFieldId): int
    {
        // returned value
        $repeatedFieldCount = $this->getRepeaterFieldsCount($parentFieldId);

        $fieldValueArray = \unserialize($fieldValue, ['allowed_classes' => false]);
        if (!is_array($fieldValueArray)) {
            return $repeatedFieldCount;
        }

        $consolidatedByFields = [];

        try {

            $consolidatedByFields = $this->consolidateRepeaterFieldValues($fieldValueArray);

            foreach ($consolidatedByFields as $consolidatedValuesArray) {

                $this->writeColumnValue('stringedRepeater', $columnIndex, implode("\n", $consolidatedValuesArray));
                $columnIndex++;
            }
        } catch (\Throwable $e) {
            \error_log('Handlers\ExportFile.extractRepeaterSubmissionColumns');
            \error_log($e->getMessage());
        }

        // ensure column count is correct by moving index correct number of
        // columns within fieldset repeater
        return $repeatedFieldCount;
    }

    /**
     * Consolidate repeated field values, filtering each individually
     *
     * @param array $fieldValueArray
     * @return array
     */
    private function consolidateRepeaterFieldValues(array $fieldValueArray): array
    {
        // returned value
        $consolidatedByFields = [];

        foreach ($fieldValueArray as $submissionKey => $valueArray) {
            $exploded = \explode('_', $submissionKey);

            $repeaterFieldId = $exploded[0];

            if (isset($this->selectedFieldTypes[$repeaterFieldId]) && isset($repeaterFieldObjectCollection[$repeaterFieldId])) {
                $filtered = $this->filterFieldValue($valueArray['value'], $repeaterFieldId, $this->selectedFieldTypes[$repeaterFieldId], $repeaterFieldObjectCollection[$repeaterFieldId]);
            } else {
                $filtered = $valueArray['value'];
            }

            if (is_array($filtered)) {
                $filtered =  implode(", ", $filtered);
            }
            $consolidatedByFields[$repeaterFieldId][] = $filtered;
        }

        return $consolidatedByFields;
    }

    /**
     * Get repeater fields count
     *
     * @return integer
     */
    protected function getRepeaterFieldsCount($fieldId): int
    {
        $keyedArray = $this->formDefinition->getRepeaterFieldsCount();
        $return = $keyedArray[$fieldId];
        return $return;
    }

    /**
     * Get repeater fields from form definition
     *
     * @return array
     */
    protected function getRepeaterFields(): array
    {
        $return = $this->formDefinition->getRepeaterFields();

        return $return;
    }

    /**
     * Write column values based on value type
     *
     * @param string $type
     * @param integer $columnIndex
     * @param mixed $fieldValue
     * @return integer
     */
    protected function writeColumnValue(string $type, int $columnIndex, $fieldValue): int
    {
        return $this->spreadsheetObjectWrapper->writeColumnValue($type, $columnIndex, $fieldValue, $this->rowNumber);
    }

    /**
     * Write standard columns
     * 
     * Sequence number and date submitted
     *
     * @param int $columnIndex
     * @param array $sub
     * @return int
     */
    protected function writeStandardColumns(int $columnIndex, array $sub): int
    {
        $this->spreadsheetObjectWrapper->writeStandardColumns(
            $columnIndex,
            $this->rowNumber,
            $sub['_seq_num'],
            $sub['date_submitted']
        );

        $columnIndex++;

        $columnIndex++;

        return $columnIndex;
    }

    /**
     * Populate properties used by this class 
     *
     * @return void
     */
    private function populateProperties(): void
    {
        try {
            $this->formId = $this->extractPostData->getFormId();

            $this->filters = $this->extractPostData->getFilters();

            $this->isXls = $this->extractPostData->isXls();

            $this->iteration = $this->extractPostData->getIteration();

            $this->submissionResults = ($this->nfDatabaseQueryFactory->makeQuerySubmissions())->querySubmissions(
                $this->formId,
                $this->subs_per_page,
                $this->iteration,
                $this->filters
            );

            $this->fieldsCollection = $this->getFields();

            $this->selectedFields = $this->constructSelectedFields();

            $this->formDefinition = ($this->nfDatabaseQueryFactory->makeFormDefinition())
                ->setFormParameters($this->formId, $this->selectedFields);

            $this->selectedFieldTypes = $this->formDefinition->getFieldTypes();
        } catch (\Throwable $e) {
            $message = $e->getMessage();

            \error_log('Handlers\ExportFile.populateProperties');
            \error_log($message);

            die();
        }
    }

    /**
     * Apply filters to a field value
     *
     * @param mixed $field_value
     * @param string $field_id
     * @param string $slug
     * @return mixed
     */
    protected function filterFieldValue($field_value, $field_id, $type, $field)
    {
        $field_value = \maybe_unserialize($field_value);

        $field_value = apply_filters('nf_subs_export_pre_value', $field_value, $field_id);
        $field_value = apply_filters('ninja_forms_subs_export_pre_value', $field_value, $field_id, $this->formId);
        $field_value = apply_filters('ninja_forms_subs_export_field_value_' . $type, $field_value, $field);

        return $field_value;
    }

    /**
     * Get collection of field models
     * 
     * Make DB call once and save by form Id
     *
     * @param string $formId
     * @return array NF_Database_Models_Field[]
     */
    private function getFields()
    {
        if (isset($this->formFieldsById[$this->formId])) {

            $return = $this->formFieldsById[$this->formId];
        } else {

            $return = Ninja_Forms()->form($this->formId)->get_fields();
            $this->formFieldsById[$this->formId] = $return;
        }

        return $return;
    }

    /**
     * Construct array of selected fields
     *
     * @return array
     */
    private function constructSelectedFields(): array
    {
        $return = array();

        foreach ($this->extractPostData->getFieldIds() as $key => $active) {
            if ($active == 1)
                $return[] = $key;
        }

        return $return;
    }

    /**
     * Get Excel object with current position
     *
     */
    private function loadSpreadsheetObjectsAtCurrentRow(): void
    {
        if ($this->iteration > 0) {
            $spreadsheetObject = $this->spreadsheetFactory->makeSpreadsheetObject($this->extractPostData->getTempFileName());
            $this->spreadsheetObjectWrapper = (new SpreadsheetObjectWrapper())
            ->setSpreadsheetObject($spreadsheetObject);
            $this->rowNumber = $this->subs_per_page * $this->iteration + 1;
        } else {
            $spreadsheetObject = $this->spreadsheetFactory->makeSpreadsheetObject();
            $this->spreadsheetObjectWrapper = (new SpreadsheetObjectWrapper())
            ->setSpreadsheetObject($spreadsheetObject);
            $this->rowNumber = 1;
            $this->writeColumnHeaders();
        }

        

        $this->spreadsheetWriter = $this->spreadsheetWriterFactory
            ->makeObjectWriter($spreadsheetObject, $this->isXls);
    }

    /**
     * Construct an individual headline
     */
    private function writeColumnHeaders(): void
    {
        $columnIndex = 1;
        $formDefinitionHeaders = $this->formDefinition->getLabels(true);
        $idColumn = __('ID', 'ninja-forms-spreadsheet');
        $dateColumn = __('Submission date', 'ninja-forms-spreadsheet');
        \array_unshift($formDefinitionHeaders, $idColumn, $dateColumn);

        $this->spreadsheetObjectWrapper->writeColumnHeaders($columnIndex, $this->rowNumber, $formDefinitionHeaders);

        $this->rowNumber++;
    }

    /**
     * Write temporary file WIP
     *
     * @return void
     */
    private function writeTempFile(): void
    {
        $count = $this->countSubmissions();

        echo json_encode(
            array(
                'iteration'     => intval($this->iteration),
                'num_iterations' => ceil($count / $this->subs_per_page),
            )
        );
        $this->spreadsheetWriter->save($this->extractPostData->getTempFileName());
    }

    /**
     * Save final version of file
     *
     * @return void
     */
    private function saveCompletedFile(): void
    {
        $columnCountIncludingStandardColumns = \count($this->formDefinition->getLabels()) + 2;

        $this->spreadsheetObjectWrapper->setAutosize($columnCountIncludingStandardColumns);

        $this->outputHeaders();

        $this->spreadsheetWriter->save("php://output");
    }

    /**
     * Construct finalized output filename
     *
     * @return string
     */
    private function constructFilename( ): string
    {
        $return ='';
        $defaultFileName =  \Ninja_Forms()->form($this->formId)->get()->get_setting('title') . '_' . date('Y-m-d_His');

        $filteredFilename = \apply_filters('ninja_forms_excel_export_filename', $defaultFileName, $this->formId);
        $return = \sanitize_title($filteredFilename);

        return $return;
    }

    /**
     * Output headers for file
     *
     * @return void
     */
    private function outputHeaders( ): void
    {
        if (headers_sent())
            ob_clean(); // clean output buffer to catch any notices and warnings sent before (by other plugins)

            $outputFilename = $this->constructFilename();

        if ($this->isXls) {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $outputFilename . '.xls"');
        } else {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $outputFilename . '.xlsx"');
        }

        header('Cache-Control: max-age=0');
    }

    private function countSubmissions()
    {
        $query_args = array(
            'post_type'         => 'nf_sub',
            'posts_per_page'    => -1,
            'date_query'        => array(
                'inclusive'     => true,
            ),
            'meta_query'        => array(
                array(
                    'key' => '_form_id',
                    'value' => $this->formId,
                )
            ),
            'fields' => 'ids'
        );

        if ($this->filters) {
            $query_args = $this->apply_query_filters($query_args);
        }


        $subs = new \WP_Query($query_args);;
        $submissionCount = $subs->found_posts;
        \wp_reset_postdata();

        return $submissionCount;
    }

    private function apply_query_filters($query_args)
    {
        foreach ($this->filters as $filter) {
            if ($filter->field_key == 'submission_date') {
                $date = $filter->value;
                if ($filter->condition == 'GT')
                    $query_args['date_query']['after'] = $date . ' 23:59:59';
                elseif ($filter->condition == 'GE')
                    $query_args['date_query']['after'] = $date . ' 00:00:00';
                elseif ($filter->condition == 'LT')
                    $query_args['date_query']['before'] = $date . ' 00:00:00';
                elseif ($filter->condition == 'LE')
                    $query_args['date_query']['before'] = $date . ' 23:59:59';
                elseif ($filter->condition == 'EQUAL') {
                    $query_args['date_query']['after'] = $date . ' 00:00:00';
                    $query_args['date_query']['before'] = $date . ' 23:59:59';
                }
                // ignore EMPTY and NOTEMPTY
            } elseif ($filter->field_type == 'date') {
                $query_args = $this->apply_query_filter_date($query_args, $filter);
            } elseif (in_array($filter->field_type, array('number', 'starrating', 'quantity', 'shipping', 'total'))) {
                $query_args = $this->apply_query_filter_numeric($query_args, $filter);
            } else {
                $query_args = $this->apply_query_filter_general($query_args, $filter);
            }
        }

        return $query_args;
    }

    private function apply_query_filter_date($query_args, $filter)
    {
        global $wpdb;
        $date = $filter->value;
        $meta_key = '_field_' . $filter->field_id;

        //convert NinjaForm date format string to mysql date format string
        $dateformat = $filter->dateformat;
        $dateformat = str_replace(array('DD', 'MM', 'YYYY', 'dddd', 'MMMM', 'D'), array('%d', '%m', '%Y', '%W', '%M', '%e'), $dateformat);

        if (in_array($filter->condition, array('GT', 'GE', 'LT', 'LE', 'EQUAL', 'NE'))) {
            if ($filter->condition == 'GT')
                $condition = '>';
            elseif ($filter->condition == 'GE')
                $condition = '>=';
            elseif ($filter->condition == 'LT')
                $condition = '<';
            elseif ($filter->condition == 'LE')
                $condition = '<=';
            elseif ($filter->condition == 'EQUAL')
                $condition = '=';
            elseif ($filter->condition == 'NE')
                $condition = '<>';

            $where_filter = $wpdb->prepare(
                "   
                    AND post_id IN(
                        SELECT post_id
                        FROM {$wpdb->postmeta}
                        WHERE 
                            {$wpdb->postmeta}.meta_key = %s
                            AND STR_TO_DATE({$wpdb->postmeta}.meta_value, %s) $condition %s
                    )
                    ",
                $meta_key,
                $dateformat,
                $filter->value
            );
            add_filter('posts_where', function ($where) use (&$where_filter) {
                return $where . $where_filter;
            });
        } elseif ($filter->condition == 'EMPTY') {
            // empty could also mean "not existing" when a new field was added to a form after a submission
            $where_filter = $wpdb->prepare(
                "   
                    AND 
                        (
                            post_id IN(
                                SELECT post_id
                                FROM {$wpdb->postmeta}
                                WHERE 
                                    {$wpdb->postmeta}.meta_key = %s
                                    AND {$wpdb->postmeta}.meta_value = ''
                            )
                        OR 
                        post_id NOT IN(
                                SELECT post_id
                                FROM {$wpdb->postmeta}
                                WHERE 
                                    {$wpdb->postmeta}.meta_key = %s
                            )
                    )
                    ",
                $meta_key,
                $meta_key
            );
            add_filter('posts_where', function ($where) use (&$where_filter) {
                return $where . $where_filter;
            });
        } elseif ($filter->condition == 'NOTEMPTY') {
            $where_filter = $wpdb->prepare(
                "   
                    AND post_id IN(
                        SELECT post_id
                        FROM {$wpdb->postmeta}
                        WHERE 
                            {$wpdb->postmeta}.meta_key = %s
                            AND {$wpdb->postmeta}.meta_value <> ''
                    )
                    ",
                $meta_key
            );
            add_filter('posts_where', function ($where) use (&$where_filter) {
                return $where . $where_filter;
            });
        }

        return $query_args;
    }


    private function apply_query_filter_numeric($query_args, $filter)
    {
        global $wpdb;
        $value = $filter->value;
        $meta_key = '_field_' . $filter->field_id;

        if ($filter->condition == 'EMPTY') {
            // empty could also mean "not existing" when a new field was added to a form after a submission
            $where_filter = $wpdb->prepare(
                "   
                    AND 
                        (
                            post_id IN(
                                SELECT post_id
                                FROM {$wpdb->postmeta}
                                WHERE 
                                    {$wpdb->postmeta}.meta_key = %s
                                    AND {$wpdb->postmeta}.meta_value = ''
                            )
                        OR 
                        post_id NOT IN(
                                SELECT post_id
                                FROM {$wpdb->postmeta}
                                WHERE 
                                    {$wpdb->postmeta}.meta_key = %s
                            )
                    )
                    ",
                $meta_key,
                $meta_key
            );
            add_filter('posts_where', function ($where) use (&$where_filter) {
                return $where . $where_filter;
            });
        } else {
            if ($filter->condition == 'GT')
                $condition = '>';
            elseif ($filter->condition == 'GE')
                $condition = '>=';
            elseif ($filter->condition == 'LT')
                $condition = '<';
            elseif ($filter->condition == 'LE')
                $condition = '<=';
            elseif ($filter->condition == 'EQUAL')
                $condition = '=';
            elseif ($filter->condition == 'NE')
                $condition = '<>';
            elseif ($filter->condition == 'NOTEMPTY') {
                $condition = '<>';
                $value = '';
            }

            $where_filter = $wpdb->prepare(
                "   
                    AND post_id IN(
                        SELECT post_id
                        FROM {$wpdb->postmeta}
                        WHERE 
                            {$wpdb->postmeta}.meta_key = %s
                            AND {$wpdb->postmeta}.meta_value $condition " . ($value == '' ? '%s' : '%d') . "
                    )
                    ",
                $meta_key,
                $value
            );
            add_filter('posts_where', function ($where) use (&$where_filter) {
                return $where . $where_filter;
            });
        }

        return $query_args;
    }


    private function apply_query_filter_general($query_args, $filter)
    {
        global $wpdb;
        $value = $filter->value;
        if (!property_exists($filter, 'field_id'))
            return $query_args;

        $meta_key = '_field_' . $filter->field_id;

        if ($filter->condition == 'EMPTY') {
            // empty could also mean "not existing" when a new field was added to a form after a submission
            $where_filter = $wpdb->prepare(
                "   
                    AND 
                        (
                            post_id IN(
                                SELECT post_id
                                FROM {$wpdb->postmeta}
                                WHERE 
                                    {$wpdb->postmeta}.meta_key = %s
                                    AND {$wpdb->postmeta}.meta_value = ''
                            )
                        OR 
                        post_id NOT IN(
                                SELECT post_id
                                FROM {$wpdb->postmeta}
                                WHERE 
                                    {$wpdb->postmeta}.meta_key = %s
                            )
                    )
                    ",
                $meta_key,
                $meta_key
            );
            add_filter('posts_where', function ($where) use (&$where_filter) {
                return $where . $where_filter;
            });
        } else {
            if ($filter->condition == 'GT')
                $condition = '>';
            elseif ($filter->condition == 'GE')
                $condition = '>=';
            elseif ($filter->condition == 'LT')
                $condition = '<';
            elseif ($filter->condition == 'LE')
                $condition = '<=';
            elseif ($filter->condition == 'EQUAL')
                $condition = '=';
            elseif ($filter->condition == 'NE')
                $condition = '<>';
            elseif ($filter->condition == 'NOTEMPTY') {
                $condition = '<>';
                $value = '';
            } elseif ($filter->condition == 'CONTAINS') {
                $condition = 'LIKE';
                $value = '%' . $value . '%';
            } elseif ($filter->condition == 'LIKE') {
                $condition = 'LIKE';
                $value = str_replace('*', '%', $value);
            }


            $where_filter = $wpdb->prepare(
                "   
                    AND post_id IN(
                        SELECT post_id
                        FROM {$wpdb->postmeta}
                        WHERE 
                            {$wpdb->postmeta}.meta_key = %s
                            AND {$wpdb->postmeta}.meta_value $condition %s
                    )
                    ",
                $meta_key,
                $value
            );
            add_filter('posts_where', function ($where) use (&$where_filter) {
                return $where . $where_filter;
            });
        }

        return $query_args;
    }

    /**
     * Set the value of extractPostData
     *
     * Set ExtractPostData object
     *
     * @param ExtractPostData $extractPostData
     * @return ExportFile
     */
    public function setExtractPostData(ExtractPostData $extractPostData): ExportFile
    {
        $this->extractPostData = $extractPostData;

        return $this;
    }

    /**
     * Set SpreadsheetFactory object
     *
     * @param SpreadsheetFactory $spreadsheetFactory
     * @return ExportFile
     */
    public function setSpreadsheetFactory(SpreadsheetFactory $spreadsheetFactory): ExportFile
    {
        $this->spreadsheetFactory = $spreadsheetFactory;

        return $this;
    }

    /**
     * Set the value of spreadsheetWriterFactory
     *
     * @return  ExportFile
     */
    public function setSpreadsheetWriterFactory($spreadsheetWriterFactory): ExportFile
    {
        $this->spreadsheetWriterFactory = $spreadsheetWriterFactory;

        return $this;
    }

    /**
     * Set the value of nfDatabaseQueryFactory
     *
     * @return  ExportFile
     */
    public function setNfDatabaseQueryFactory($nfDatabaseQueryFactory): ExportFile
    {
        $this->nfDatabaseQueryFactory = $nfDatabaseQueryFactory;

        return $this;
    }
}
