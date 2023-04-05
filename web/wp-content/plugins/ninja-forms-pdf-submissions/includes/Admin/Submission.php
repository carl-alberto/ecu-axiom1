<?php

class NF_Pdf_Submissions_Admin_Submission
{
    protected $submission_id = null;

    protected $submission;

    protected $form_id;

    protected $fields = [];

    protected $data = [];

    protected $title;

    protected $pdf_doc;

    public function __construct($sub_id)
    {
        $this->setSubId($sub_id);

        $this->submission = Ninja_Forms()->form()->get_sub($sub_id);
        $this->form_id = $this->submission->get_form_id();

        $this->title = Ninja_Forms()->form($this->form_id)->get()->get_setting('title');
        $this->fields =  Ninja_Forms()->form($this->form_id)->get_fields();
        $this->fields = new NF_Pdf_Submissions_Adapters_Submission($this->fields, $this->form_id, $this->submission);
        $this->data = ['form_ID' => $this->form_id];

        $this->pdf_doc = new NF_Pdf_Submissions_Pdf_Document();
    }
    /**
     * Set should merge tag fields be populated?
     *
     * @param  bool  $populateMergeFields  Should merge tag fields be populated?
     *
     * @return  NF_Pdf_Submissions_Pdf_Document
     */
    public function setPopulateMergeFields(bool $populateMergeFields):NF_Pdf_Submissions_Admin_Submission
    {
        $this->pdf_doc->setPopulateMergeFields($populateMergeFields);
        return $this;
    }

    public function setSubId($subId)
    {
        $this->submission_id = $subId;
    }

    public function bulkExportPdf()
    {
        $this->pdf_doc->setFields($this->fields);
        $this->pdf_doc->setTitle($this->title);
        $this->pdf_doc->setData($this->data);
        
        if ($this->submission_id !== null) {
            $this->pdf_doc->setSubmission($this->submission);
        }
        
        $form_settings = Ninja_Forms()->form( $this->data[ 'form_ID' ] )->get_settings();
        if ( isset ( $form_settings[ 'use_document_filename' ] ) && 1 == $form_settings[ 'use_document_filename' ] ) {
            $field_merge_tags = Ninja_Forms()->merge_tags[ 'fields' ];
            $field_merge_tags->set_form_id( $this->form_id );

            foreach ( $this->fields as $field_id => $field ) {
                $field_merge_tags->add_field( $field );
            }

            $name = $form_settings[ 'document_filename'];
            // Replace any occurances of .pdf within our filename, just incase a user has set one within their custom document filename.
            $name = str_replace( '.pdf', '', $name );
            // Run our name through merge tags filter to replace any merge tags with submitted values.
            $name = apply_filters( 'ninja_forms_merge_tags', $name );
        } else {
            $name = 'ninja-forms-submission-' . $this->submission_id;
        }

        $name = apply_filters('ninja_forms_submission_pdf_name', $name, $this->submission_id);

        $content = $this->pdf_doc->export($name . '.pdf');

        echo $content;

        die();
    }

    /**
     * Populate the 'fields' merge tags
     *
     * @return void
     */
    protected function populateFieldsMergeTags(): void
    {
        $field_merge_tags = Ninja_Forms()->merge_tags['fields'];
        $field_merge_tags->set_form_id($this->form_id);

        foreach ($this->fields as $field_id => $field) {
            $field_merge_tags->add_field($field);
        }
    }

    /**
     * Construct the filename WITHOUT extension
     *
     * @return string
     */
    protected function constructFilename( ): string
    {
        $form_settings = Ninja_Forms()->form( $this->data[ 'form_ID' ] )->get_settings();
        
        if ( isset ( $form_settings[ 'use_document_filename' ] ) && 1 == $form_settings[ 'use_document_filename' ] ) {

            $this->populateFieldsMergeTags();

            $name = $form_settings[ 'document_filename'];
            // Replace any occurances of .pdf within our filename, just incase a user has set one within their custom document filename.
            $name = str_replace( '.pdf', '', $name );
            // Run our name through merge tags filter to replace any merge tags with submitted values.
            $name = apply_filters( 'ninja_forms_merge_tags', $name );
        } else {
            $name = 'ninja-forms-submission-' . $this->submission_id;
        }

        $return = apply_filters('ninja_forms_submission_pdf_name', $name, $this->submission_id);

        return $return;
    }

    /**
     * Return the filename INCLUDING extension
     *
     * @return string
     */
    public function getFilename(): string
    {
        $name = $this->constructFilename();

        $return = $name . '.pdf';

        return $return;
    }

    /**
     * Return base64 encoded string of PDF document
     */
    public function returnPdf():string
    {
        $this->pdf_doc->setFields($this->fields);
        $this->pdf_doc->setTitle($this->title);
        $this->pdf_doc->setData($this->data);
        
        if ($this->submission_id !== null) {
            $this->pdf_doc->setSubmission($this->submission);
        }
        $name = 'unused';
        $content = base64_encode( $this->pdf_doc->export($name . '.pdf','S'));

        return $content;
    }
}
