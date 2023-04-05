<?php

namespace NinjaForms\ExcelExport\Admin;
use NinjaForms\ExcelExport\Contracts\ExtractPostData as ContractsExtractPostData;
/**
 * Extracts POST data
 * 
 * ExcelExport request is triggered by POST data; this isolates its extraction
 */
class ExtractPostData implements ContractsExtractPostData{

    /**
     * Form Id
     *
     * @var string
     */
    private $formId;

    /**
     * Field Ids
     *
     * @var array
     */
    private $fieldIds;

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
     * Temporary filename
     *
     * @var string
     */
    private $tempFileName;

    /**
     * Current iteration
     *
     * @var int
     */
    private $iteration;

    public function __construct( )
    {
        $this->formId = $this->extractFormId();
        $this->fieldIds = $this->extractFieldIds();
        $this->filters = $this->extractFilters();
        $this->isXls = $this->determineIsXls();
        $this->tempFileName = $this->constructTempFileName();
        $this->iteration = $this->extractIteration();
    }

    /**
     * Extract form Id
     * 
     * Uses POST
     *
     * @return string
     */
    protected function extractFormId(): string
    {
        $return = \filter_input(INPUT_POST, 'spreadsheet_export_form_id');

        return $return;
    }

    /**
     * Extract field Ids
     *
     * @return array
     */
    protected function extractFieldIds(): array
    {
        $return = $_POST['spreadsheet_export_field_ids'];

        return $return;
    }

    /**
     * Extract filters
     *
     */
    protected function extractFilters()
    {
        $return = json_decode(stripslashes(\filter_input(INPUT_POST, 'spreadsheet_export_filter')));

        return $return;
    }

    /**
     * Determine if using XLS format
     *
     * @return boolean
     */
    protected function determineIsXls(): bool
    {
        $format = \filter_input(INPUT_POST, 'spreadsheet_export_file_format');

        if ('xls' == $format) {
            $return = true;
        } else {
            $return = false;
        }

        return $return;
    }

    /**
     * Construct the temporary file name
     *
     * @return string
     */
    protected function constructTempFileName(): string
    {
        $tempFileBase = \filter_input(INPUT_POST, 'spreadsheet_export_tmp_name');

        if ($this->isXls) {

            $tmp_file = 'form-submissions' . $tempFileBase . '.xls';
        } else {

            $tmp_file = 'form-submissions' . $tempFileBase . '.xlsx';
        }

        $return = trailingslashit(\get_temp_dir()) . $tmp_file;

        return $return;
    }

    /**
     * Extract iteration number
     *
     * @return integer
     */
    protected function extractIteration(): int
    {
        $return = \filter_input(INPUT_POST, 'spreadsheet_export_iteration');

        return $return;
    }



    /**
     * Get form Id
     *
     * @return  string
     */ 
    public function getFormId():string
    {
        return $this->formId;
    }

    /**
     * Get field Ids
     *
     * @return  array
     */ 
    public function getFieldIds():array
    {
        return $this->fieldIds;
    }

    /**
     * Get filters
     */ 
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Get is XLS (default is XLSX)
     *
     * @return  bool
     */ 
    public function isXls():bool
    {
        return $this->isXls;
    }

    /**
     * Get temporary filename
     *
     * @return  string
     */ 
    public function getTempFileName():string
    {
        return $this->tempFileName;
    }

    /**
     * Get current iteration
     *
     * @return  int
     */ 
    public function getIteration():int
    {
        return $this->iteration;
    }
}