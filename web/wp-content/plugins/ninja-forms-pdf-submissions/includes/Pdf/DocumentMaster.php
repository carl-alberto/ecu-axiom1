<?php

// namespace NinjaForms\Pdf\Document;

class NF_Pdf_Submissions_Pdf_DocumentMaster
{
    private $pdf;

    protected $title;

    private $fields = [];

    protected $data;

    protected $submission = null;

    protected $form_id = null;

    protected $form_settings = null;

    protected $template = 'pdf.php';

    protected $template_css = 'pdf.css';

    protected $template_dir = 'Templates';

    /**
     * Should merge tag fields be populated?
     *
     * @var bool
     */
    protected $populateMergeFields;

    /** @var int */
    private $fontSize = 12;

    /** @var string */
    private $font = 'Arial';
    
    public function __construct()
    {
        require_once(plugin_dir_path(__FILE__) . '../../vendor/autoload.php');

        /**
         * Suppress non-fatal errors while we build the PDF to avoid a buffer conflict.
         * Ideally, we would prefer to not need this suppression, but for the moment, it resolves the issue.
         */
        error_reporting(0);

        $this->pdf = new \Mpdf\Mpdf($this->filterMpdfConfiguration());
    }

    /**
     * Add filter to enable dynamic configuration
     */
    protected function filterMpdfConfiguration( ): array
    {
        $default = [
            'mode' => 'utf-8',
            'default_font_size' => $this->fontSize,
            'default_font' => $this->font
        ];

        $return = \apply_filters('nf_sub_mpdf_configuration',$default);

        if(isset($return['default_font_size'])){
            $this->fontSize = $return['default_font_size'];
        }
        if(isset($return['default_font'])){
            $this->font = $return['default_font'];
        }

        return $return;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    protected function getTitle()
    {
        if (isset($this->form_settings['use_document_title'])
            && $this->form_settings['use_document_title'] == 1
            && isset($this->form_settings['document_title'])) {
                /**
                 * If we're in the admin and exporting PDF submissions, then we need to init merge tags.
                 */
                if ( $this->requireMergeFieldPopulation() ) {
                    $field_merge_tags = Ninja_Forms()->merge_tags[ 'fields' ];
                    $field_merge_tags->set_form_id( $this->form_id );
                    $field_merge_tags->include_all_fields_merge_tags();

                    foreach ( $this->fields as $field_id => $field ) {
                        $field_merge_tags->add_field( $field );
                    }

                }

                // Run the Title setting through our merge tag filter to process any merge tags.
                $this->title = apply_filters( 'ninja_forms_merge_tags', $this->form_settings[ 'document_title' ] );
        }

        return __($this->title, 'ninja-forms');
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function setSubmission($sub)
    {
        $this->submission = $sub;
        $this->setFormId($sub->get_form_id());
    }

    public function setFormId($id)
    {
        $this->form_id = $id;

        $this->getFormSettings();
    }

    protected function getFormSettings()
    {
        $form = Ninja_Forms()->form($this->form_id)->get();

        $this->form_settings = $form->get_settings();
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

    protected function headerSettingsAreOn()
    {
        if ($this->form_settings
            && isset($this->form_settings['toggle_header_settings'])
            && $this->form_settings['toggle_header_settings'] == 1) {
                return true;
        }

        return false;
    }

    protected function footerSettingsAreOn()
    {
        if ($this->form_settings
            && isset($this->form_settings['toggle_footer_settings'])
            && $this->form_settings['toggle_footer_settings'] == 1) {
                return true;
        }

        return false;
    }

    public function setTemplateDirectory($dir)
    {
        $this->template_dir = $dir;
    }

    public function export($name = '', $dest = 'D')
    {

        $args = array_merge([
        'title'    => $this->getTitle(),
        'header'   => $this->getHeader(),
        'fields'   => $this->fields,
        ], $this->data);

        $is_local_template = $this->checkIsLocalTemplate($this->template);

        if ($is_local_template) {
            $this->setFooter();
            $this->addSubmissionTableToPdf($args);
        } else {
            $args['css_path'] = $this->locateTemplate('pdf.css');
            $args['table'] = $this->createHtmlTable();
            $html = $this->getTemplate($this->template, $args);
            $this->pdf->WriteHTML($html);
        }

        return $this->pdf->Output($name, $this->getDestination($dest));
    }

    protected function getHeader()
    {
        $header = '';

        if ($this->headerSettingsAreOn()) {
            $header_position = $this->form_settings['header_position'];
            $header_container_class = 'header_' . $header_position;
            $img_container_class = 'img_' . $header_position;
            $bus_container_class = ' bus_text_' . $header_position;
        
            $header = "<div class='" . $header_container_class . "'>";
            if (isset($this->form_settings['company_logo']) && strlen($this->form_settings['company_logo']) > 0) {
                $header .= "<div class='" . $img_container_class
                    . "'><img src='" . $this->form_settings['company_logo']
                    . "'/></div>";
            }

            $header .= "<div class='business_text" . $bus_container_class . "'>";

            if (isset($this->form_settings['company_name'])) {
                $header .= "<span class='company_name'>" . __($this->form_settings['company_name'], 'ninja-forms')
                . "</span><br/>";
            }

            if (isset($this->form_settings['header_address_1'])
                && strlen($this->form_settings['header_address_1']) > 0) {
                $header .= "<span>" . __($this->form_settings['header_address_1'], 'ninja-forms') . "</span><br/>";
            }

            if (isset($this->form_settings['header_address_2'])
                && strlen($this->form_settings['header_address_2']) > 0) {
                $header .= "<span>" . __($this->form_settings['header_address_2'], 'ninja-forms') . "</span><br/>";
            }

            if (isset($this->form_settings['header_city_state_province'])
                && strlen($this->form_settings['header_city_state_province']) > 0) {
                $header .= "<span>" . __($this->form_settings['header_city_state_province'], 'ninja-forms') . "</span><br/>";
            }

            if (isset($this->form_settings['header_phone'])
                && strlen($this->form_settings['header_phone']) > 0) {
                $header .= "<span>" . __($this->form_settings['header_phone'], 'ninja-forms') . "</span><br/>";
            }

            if (isset($this->form_settings['header_email'])
                && strlen($this->form_settings['header_email']) > 0) {
                $header .= "<span>" . __($this->form_settings['header_email'], 'ninja-forms') . "</span><br/>";
            }

            if (isset($this->form_settings['header_date'])
                && strlen($this->form_settings['header_date']) > 0) {
                    
                $this->form_settings['header_date']=apply_filters( 'ninja_forms_merge_tags', $this->form_settings[ 'header_date' ] );
                $header .= "<span>" . __($this->form_settings['header_date'], 'ninja-forms') . "</span><br/>";
            }

            $header .= "</div><div class='clear'></div></div>";
        }

        return $header;
    }

    protected function addSubmissionTableToPdf($args)
    {
        if ($args && is_array($args)) {
            extract($args);
        }

        $args['css_path'] = $this->locateTemplate('pdf.css');

        $pdf_start = $this->getTemplate('pdf_submission_open.php', $args);

        $this->pdf->WriteHTML($pdf_start);

        /**
         * If we're in the admin and exporting PDF submissions, then we need to init merge tags.
         */
        if ( $this->requireMergeFieldPopulation()) {
            $field_merge_tags = Ninja_Forms()->merge_tags[ 'fields' ];
            $field_merge_tags->set_form_id( $this->form_id );
            $field_merge_tags->include_all_fields_merge_tags();

            foreach ( $this->fields as $field_id => $field ) {
                $field_merge_tags->add_field( $field );
            }
        }

        if ( isset ( $this->form_settings[ 'use_document_body' ] ) &&
            1 == $this->form_settings[ 'use_document_body' ]
        ) {
            // Run our custom body setting through our merge tags filter to replace any merge tags with submitted values.
            $document_body = apply_filters( 'ninja_forms_merge_tags', $this->form_settings[ 'document_body' ] );
            $this->pdf->WriteHTML( $document_body );            
        } else {
            // Use default table output.
            $this->createSubmissionTable($args);  
        }
    }

    /**
     * Determine if fields merge tags need to be populated
     * 
     * Store determination for reuse
     *
     * @return boolean
     */
    protected function requireMergeFieldPopulation(): bool
    {
        // Return previously set value
        // In NF >3.6.5, value will be set before export request is made
       if(isset($this->populateMergeFields)){
           return $this->populateMergeFields;
       }

       // If not previously set, use NF <=3.5.8.3 GET querystring determination
       if ( is_admin() && isset ( $_GET[ 'ninja_forms_export_subs_to_pdf' ] ) ) {
           $this->populateMergeFields =  true;
       }else{
           $this->populateMergeFields = false;
       }

       return $this->populateMergeFields;
    }

    public function createSubmissionTable($args)
    {
        if ($args && is_array($args)) {
            extract($args);
        }

        $table_data = $this->setTableData($fields);

        $this->pdf->SetDefaultFontSize(12);
        $this->pdf->SetFont($this->font);
        $this->pdf->SetLineWidth(0.10);
        $this->pdf->SetDrawColor(175, 175, 175);
        $widths = [50,130];
        $aligns = ['C', 'L'];

        foreach ($table_data as $row_index => $row) {
            $row_data = $this->getRowData($row);
            $row_broken = false;
            // Draw the cells of the row
            for ($i = 0; $i < count($row); $i++) {

                if ($row_broken) {
                    break;
                }
                if ($i === 0) {
                    // Set Header Background Color
                    $this->pdf->SetFillColor(221, 221, 221);
                    $this->pdf->SetFont($this->font, 'B', $this->fontSize);
                } else {
                    $this->pdf->SetFillColor(255, 255, 255);
                    $this->pdf->SetFont($this->font, '', $this->fontSize);
                }

                $w= $widths[$i];
                $a= isset($aligns[$i]) ? $aligns[$i] : 'L';
                $line_lengths = [18, 63];

                //Save the current position
                $x = $this->pdf->x;
                $y = $this->pdf->y;

                if ($row_data['row_too_tall']) {
                    $shrink_row = true;
                    $row_broken = true;
            
                    while ($shrink_row) {
                        if ($row_data['row_too_tall']) {
                            // 4.75 is roughly the line height for Arial font at 12pt.
                            $num_lines_left = floor($row_data['row_too_tall'] / 5.50);

                            /**
                             * If we know the row is too tall for the rest of
                             * page and we only have 1 or less lines.
                             * Then go ahead and insert the break.
                             */
                            if ($num_lines_left <= 1) {
                                $this->pdf->AddPage();
                                $x = $this->pdf->x;
                                $y = $this->pdf->y;
                                $h = 0;
                                $row_data = $this->getRowData($row);
                                continue;
                            }

                            $line_length = 18;
                            if ($row_data['tall_column_index'] === 1) {
                                $line_length = 63;
                            }

                            $wrapped_val_array = explode('\n', wordwrap($row[$row_data['tall_column_index']], $line_length, '\n'));

                            $smaller_row = $row;

                            $smaller_row[$row_data['tall_column_index']] = implode(' ', array_slice($wrapped_val_array, 0, $num_lines_left-1));


                            $row[$row_data['tall_column_index']] =
                            implode(' ', array_slice($wrapped_val_array, $num_lines_left));

                            $h = $this->pdf->h - $this->pdf->y - $this->pdf->bMargin - 6;
                            foreach ($smaller_row as $n => $cell_value) {
                                if ($n === 0) {
                                    // Set Header Background Color
                                    $this->pdf->SetFillColor(221, 221, 221);
                                    $this->pdf->SetFont($this->font, 'B', $this->fontSize);
                                } else {
                                    $this->pdf->SetFillColor(255, 255, 255);
                                    $this->pdf->SetFont($this->font, '', $this->fontSize);
                                }
                
                                $w= $widths[$n];
                                $a= isset($aligns[$n]) ? $aligns[$n] : 'L';
                                $line_lengths = [18, 63];
                
                                //Save the current position
                                $x = $this->pdf->x;
                                $y = $this->pdf->y;
                                //Draw the border
                                $this->pdf->Rect($x, $y, $w, $h, 'DF');
                                //Print the text
                                $this->pdf->SetY($y + 1);
                                $this->pdf->SetX($x + 1);
                                $this->pdf->MultiCell($w - 2, 5, $cell_value, 0, $a);
                                $x = $this->pdf->x;
                                // $y = $this->pdf->y;
                                // $this->pdf->SetY($y);
                                //Put the position to the right of the cell
                                $this->pdf->SetXY($x + $w, $y);

                                if ($n === count($smaller_row) - 1) {
                                    $this->pdf->AddPage();
                                    $x = $this->pdf->x;
                                    $y = $this->pdf->y;
                                    $h = 0;
                                    $row_data = $this->getRowData($row);
                                }
                            }
                        } else {
                            if (strlen($row[$row_data['tall_column_index']]) > 0) {
                                $h = $row_data['row_height'];
                                foreach ($row as $m => $cell_value) {
                                    if ($m === 0) {
                                        // Set Header Background Color
                                        $this->pdf->SetFillColor(221, 221, 221);
                                        $this->pdf->SetFont($this->font, 'B', $this->fontSize);
                                    } else {
                                        $this->pdf->SetFillColor(255, 255, 255);
                                        $this->pdf->SetFont($this->font, '', $this->fontSize);
                                    }
                    
                                    $w= $widths[$m];
                                    $a= isset($aligns[$m]) ? $aligns[$m] : 'L';
                                    $line_lengths = [18, 63];
                    
                                    //Save the current position
                                    $x = $this->pdf->x;
                                    $y = $this->pdf->y;
                                    //Draw the border
                                    $this->pdf->Rect($x, $y, $w, $h, 'DF');
                                    //Print the text
                                    $this->pdf->SetY($y + 1);
                                    $this->pdf->SetX($x + 1);
                                    $this->pdf->MultiCell($w - 2, 5, $cell_value, 0, $a);

                                    $x = $this->pdf->x;
                                    // $y = $this->pdf->y;
                                    // $this->pdf->SetY($y);
                                    //Put the position to the right of the cell
                                    $this->pdf->SetXY($x + $w, $y);

                                    if ($m === count($row) - 1) {
                                        //Go to the next line
                                        $this->pdf->Ln($h);
                                        $shrink_row = false;
                                    }
                                }
                            } else {
                                $this->pdf->Ln($h);
                                $shrink_row = false;
                                break 2;
                            }
                        }
                    }
                } else {
                    $h = $row_data['row_height'];
                    //Draw the border
                    $this->pdf->Rect($x, $y, $w, $h, 'DF');
                    //Print the text
                    $this->pdf->SetY($y + 1);
                    $this->pdf->SetX($x + 1);
                    $this->pdf->MultiCell($w - 2, 5, $row[$i], 0, $a);

                    $this->pdf->SetY($y);
                    //Put the position to the right of the cell
                    $this->pdf->SetXY($x + $w, $y);
                    //Go to the next line
                    if ($i === count($row) -1) {
                        $this->pdf->Ln($h);
                    }
                }
            }
        }
    }

    protected function getRowData($row)
    {
        //Calculate the height of the row
        $num_lines = 0;
        $offending_column_index = 0;
        
        for ($i = 0; $i < count($row); $i++) {
            if ($i === 0) {
                $line_length = 18;
            } else {
                $line_length = 63;
            }
            
            // Count manual line breaks and add them to word wrap line breaks
            $manualLinebreakDelimiter = '<br';
            $manualLineBreakCount = substr_count(nl2br($row[$i]), $manualLinebreakDelimiter);

            $var_lines = count(explode('\n', wordwrap($row[$i], $line_length, '\n'))) + $manualLineBreakCount;

            if ($var_lines > $num_lines) {
                $num_lines = $var_lines;
                $offending_column_index = $i;
            }
        }

        $row_height = $num_lines * 10;
        $return_array = [
            'num_lines' => $num_lines,
            'row_height' => $row_height,
            'tall_column_index' => $offending_column_index,
            'row_too_tall' => $this->CheckPageBreak($row_height),
        ];

        return $return_array;
    }

    protected function checkPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->pdf->y + $h > $this->pdf->PageBreakTrigger) {
            return abs($this->pdf->PageBreakTrigger - $this->pdf->y - $this->pdf->bMargin);
        }

        return false;
    }

    /**
     * Construct table rows from submission fields
     * 
     * @todo Remove unused $fields incoming parameter
     *
     * @param array $fields
     * @return array
     */
    protected function setTableData($fields)
    {
        $hidden_field_types = $this->getHiddenFieldTypes();

        // allow user to filter fields that are used in document via nf_sub_document_fields
        $fields = apply_filters('nf_sub_document_fields', $this->fields);

        $table_data = [];
        foreach ($fields as $field) {
            if (in_array($field['type'], array_values($hidden_field_types))) {
                continue;
            }

            if (isset($field['admin_label']) && $field['admin_label']) {
                $field_label = $field['admin_label'];
            } else {
                $field_label = $field['label'];
            }

            $field_value = (isset($field['value'])) ? $field['value'] : null;

            if('repeater'==$field['type'] && is_array($field_value)){

                $fieldLookup = array_combine(array_column($field['fields'],'id'),$field['fields']);

                $deconstructedValue = $this->deconstructRepeaterFieldValue($field_value);
                
                foreach($deconstructedValue as $index=>$repeatedSet){

                    foreach($repeatedSet as $fieldId=>$valueArray){
                        
                        if (isset($fieldLookup[$fieldId]['admin_label']) && $fieldLookup[$fieldId]['admin_label']) {
                            $repeaterFieldLabel = $fieldLookup[$fieldId]['admin_label'];
                        } else {
                            $repeaterFieldLabel = $fieldLookup[$fieldId]['label'];
                        }

                        // arrays start at zero, humans start at 1
                        $counter = $index +1;

                        $indexedLabel = $field_label. '-'.$repeaterFieldLabel.' #'.$counter;

                        $filteredLabel =apply_filters('ninja_forms_pdf_filter_repeater_label', $indexedLabel, $fieldId);
                        
                        $filteredValue = apply_filters('ninja_forms_pdf_pre_user_value', $valueArray['value'], array());
                        
                        $table_data[] = [$filteredLabel, $filteredValue];
                    }
                }

                continue;
            }

            $field_value = apply_filters('ninja_forms_pdf_pre_user_value', $field_value, array());

            // if the user submitted value is an array we need to make it pretty
            if (is_array($field_value)) {
                $field_value = implode(", ", $field_value);
            }
            
            $field_value = apply_filters('ninja_forms_pdf_field_value', html_entity_decode($field_value), $field_value, $field);

            $table_data[] = [$field_label, $field_value];
        }

        return $table_data;
    }

    /**
     * Get filtered list of hidden field types
     *
     * @return array
     */
    protected function getHiddenFieldTypes(): array
    {
        $return = apply_filters('nf_sub_hidden_field_types', []);

        return $return;
    }

    /**
     * Deconstruct repeater field array by repeated fields
     * 
     * 
     * @todo Add exception handling for unexpected key structure
     * @param array $constructedValue
     * @return array
     */
    protected function deconstructRepeaterFieldValue(array $constructedValue): array
    {
        $delimiter = '_';

        $return = [];
        foreach ($constructedValue as $constructedKey => $submissionValue) {

            $exploded = explode($delimiter,$constructedKey);

            if(isset($exploded[1])){

                $return[$exploded[1]][$exploded[0]]=$submissionValue;
            }
        }

        return $return;
    }
    
    protected function getFooterContainerClass()
    {
        $class_str = '';
        switch ($this->form_settings['footer_position']) {
            case 'left':
                $class_str = 'footer_left';
                break;
            case 'center':
                $class_str = 'footer_center';
                break;
            case 'right':
                $class_str = 'footer_right';
                break;
            default:
                break;
        }

        return $class_str;
    }

    protected function setFooter()
    {
        $footer_text = '';
        if ($this->footerSettingsAreOn()) {
            $footer_class = $this->getFooterContainerClass();
            if (isset($this->form_settings['additional_info'])
                && strlen($this->form_settings['additional_info']) > 0) {
                $footer_text = "<div class='" . $footer_class . "'>"
                    . "<span style='margin-right: 10px;font-weight: bold;'>" . __($this->form_settings['additional_info'], 'ninja-forms')
                    . "</span>";
            }

            if (isset($this->form_settings['pagination'])
                && $this->form_settings['pagination'] == 1) {
                if (strlen($footer_text) === 0) {
                    $footer_text = "<div class='" . $footer_class . "'>";
                    $footer_text .= "<span>{PAGENO}/{nbpg}</span>";
                } else {
                    $footer_text .= "<br/><span style='margin-left:15px;padding:20px;'>{PAGENO}</span>";
                }
            }

            if (strlen($footer_text) > 0) {
                $footer_text .= "</div>";

                $this->pdf->SetHTMLFooter($footer_text);
            }
        }
    }

    /**
     * Get other templates passing attributes and including the file.
     *
     * @access public
     * @param mixed $template_name
     * @param array $args (default: array())
     * @param string $template_path (default: '')
     * @param string $default_path (default: '')
     * @return string
     */
    public function getTemplate($template_name, $args = array(), $template_path = '', $default_path = '')
    {
        if ($args && is_array($args)) {
            extract($args);
        }

        $template = $this->locateTemplate($template_name, $template_path, $default_path);

        $level = error_reporting();
        error_reporting(0);
        ob_start();

        do_action('nf_pdf_before_template_part', $template_name, $template_path, $template, $args);

        include($template);

        do_action('nf_pdf_after_template_part', $template_name, $template_path, $template, $args);

        error_reporting($level);
        return ob_get_clean();
    }

    /**
     * Locate a template and return the path for inclusion.
     *
     * This is the load order:
     *
     *     yourtheme      /  $template_path  /  $template_name
     *     yourtheme      /  $template_name
     *     $default_path  /  $template_name
     *
     * @access public
     * @param mixed $template_name
     * @param string $template_path (default: '')
     * @param string $default_path (default: '')
     * @return string
     */
    public function locateTemplate($template_name, $template_path = '', $default_path = '')
    {
        // set a default template directory url
        if (!$template_path) {
            $template_path = apply_filters('nf_pdf_template_url', 'ninja-forms-pdf-submissions/');
        }

        if (!$default_path) {
            $default_path = $this->template_dir;
        }

        // Look within passed path within the theme - this is priority
        $template = locate_template(
            [
                trailingslashit($template_path) . $template_name,
                $template_name
            ]
        );

        // Get default template if we couldn't find anything in the theme
        if (!$template) {
            $template = plugin_dir_path(__FILE__) . $default_path . '/' . $template_name;
        }

        return apply_filters( 'nf_pdf_locate_template', $template, $template_name, $template_path );
    }

    protected function createHtmlTable()
    {
        ob_start(); // open buffer

        echo "<table>";

        // before looping through the fields let's add the date to the results
        // default is off but can be turned on via a filter
        if ($this->submission && apply_filters('ninja_forms_submission_pdf_fetch_date', false, $this->submission->get_id())) {
            echo "<tr>";
            echo "<td>" . __('Date Submitted', 'ninja-forms') . "</td>";
            echo "<td>" . $this->submission->get_sub_date() . "</td>";
            echo "</tr>\n";
        }

        // we should also add the option to add the sequential number to the form
        // default is off but can be turned on via a filter
        if ($this->submission && apply_filters('ninja_forms_submission_pdf_fetch_sequential_number', false, $this->submission->get_id())) {
            echo "<tr>";
            echo "<td>" . __('Form Submission ID', 'ninja-forms') . "</td>";
            echo "<td>" . $this->submission->get_seq_num() . "</td>";
            echo "</tr>\n";
        }

        $hidden_field_types = apply_filters('nf_sub_hidden_field_types', array());

        // allow user to filter fields that are used in document via nf_sub_document_fields
        $fields = apply_filters('nf_sub_document_fields', $this->fields);

        foreach ($fields as $field) {
            if (in_array($field['type'], array_values($hidden_field_types))) {      continue;
            }

            if (isset($field['admin_label']) && $field['admin_label']) {
                $field_label = $field['admin_label'];
            } else {
                $field_label = $field['label'];
            }

            $field_value = (isset($field['value'])) ? $field['value'] : null;

            $field_value = apply_filters('ninja_forms_pdf_pre_user_value', $field_value, array());

            // if the user submitted value is an array we need to make it pretty
            if (is_array($field_value)) {
                $field_value = implode(", ", $field_value);
            }

            $field_value = apply_filters('ninja_forms_pdf_field_value', html_entity_decode($field_value), $field_value, $field);

            if (apply_filters('ninja_forms_pdf_field_value_wpautop', true, $field_value, $field)) {
                $field_value = wpautop($field_value);
            }

            echo "<tr>";
            echo "<td>" . $field_label . "</td>";
            echo "<td>" . $field_value . "</td>";
            echo "</tr>\n";
        }

        echo "</table>";

        return ob_get_clean();
    }

    protected function checkIsLocalTemplate()
    {
        $template = $this->locateTemplate($this->template);

        $local_tmpl = plugin_dir_path(__FILE__) . 'Templates/pdf.php';

        if ($local_tmpl === $template) {
            return true;
        } else {
            set_transient('ninja_forms_using_custom_pdf_template', 'true', WEEK_IN_SECONDS);
        }
        return false;
    }

    protected function getDestination($dest)
    {
        switch ($dest) {
            case 'D':
                return \Mpdf\Output\Destination::DOWNLOAD;
                break;
            case 'F':
                return \Mpdf\Output\Destination::FILE;
                break;
            case 'S':
                return \Mpdf\Output\Destination::STRING_RETURN;
                break;
            case 'I':
                return \Mpdf\Output\Destination::INLINE;
                break;
            default:
                return \Mpdf\Output\Destination::DOWNLOAD;
                break;
        }
    }

    /**
     * Set should merge tag fields be populated?
     *
     * @param  bool  $populateMergeFields  Should merge tag fields be populated?
     *
     * @return  NF_Pdf_Submissions_Pdf_DocumentMaster
     */ 
    public function setPopulateMergeFields(bool $populateMergeFields):NF_Pdf_Submissions_Pdf_DocumentMaster
    {
        $this->populateMergeFields = $populateMergeFields;

        return $this;
    }
}
