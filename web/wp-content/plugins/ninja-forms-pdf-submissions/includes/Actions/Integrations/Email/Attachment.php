<?php

class NF_Pdf_Submissions_Actions_Integrations_Email_Attachment
{
    protected $submission_id = null;

    protected $submission = null;

    protected $form_id;

    protected $fields = [];

    protected $data = [];

    protected $title;

    protected $full_file_path = '';

    protected $pdf_doc;

    public function __construct($data)
    {
        $this->title = $data['settings']['title'];
        $this->form_id = $data['form_id'];
        $this->fields = $data['fields'];
        $this->fields =  new NF_Pdf_Submissions_Adapters_Fields($this->fields, $this->form_id);
        $this->submission_id = (isset($data['actions']['save'])) ? $data['actions']['save']['sub_id'] : null;
        $this->data = array('form_ID' => $data['form_id'], 'form_settings' => $data[ 'settings' ]);

        if ($this->submission_id !== null) {
            $this->submission = Ninja_Forms()->form()->get_sub($this->submission_id);
        }

        $this->pdf_doc = new NF_Pdf_Submissions_Pdf_Document();
    }

    public function attachPdf()
    {
        $this->pdf_doc->setFields($this->fields);
        $this->pdf_doc->setTitle($this->title);
        $this->pdf_doc->setData($this->data);
        
        if ($this->submission_id !== null) {
            $this->pdf_doc->setSubmission($this->submission);
        }else{

            $this->pdf_doc->setFormId($this->form_id);
        }

        /**
         * If we've set a custom document filename, use that.
         * Otherwise, generate a filename.
         */
        if ( isset ( $this->data[ 'form_settings' ][ 'use_document_filename'] ) &&
            1 == $this->data[ 'form_settings' ][ 'use_document_filename'] ) {
            $name = $this->data[ 'form_settings' ][ 'document_filename'];
            // Replace any occurances of .pdf within our filename, just incase a user has set one within their custom document filename.
            $name = str_replace( '.pdf', '', $name );
            // Run our name through merge tags filter to replace any merge tags with submitted values.
            $name = apply_filters( 'ninja_forms_merge_tags', $name );
        } else {
            // Generate a filename
            $name = 'ninja-forms-submission';

            if (strlen($this->submission_id) > 0) {
                $name = 'ninja-forms-submission_' . $this->submission_id;
            }
        }

        $name = apply_filters('ninja_forms_submission_pdf_name', $name, $this->submission_id);

        $name = $this->validateFilename($name);
        
        $this->setFullPathName($name);

        $this->pdf_doc->export($this->getFullPathName(), 'F');

        return $this->getFullPathName();
    }

    protected function validateFilename(string $name): string
    {
        $bad = array_merge(
            array_map('chr', range(0, 31)),
            array("<", ">", ":", '"', "/", "\\", "|", "?", "*")
        );

        $return = str_replace($bad, "", $name);

        return $return;
    }

    protected function setFullPathName($name)
    {
        $this->full_file_path = get_temp_dir() . $name . '.pdf';
        /**
         * Because we allow users to add their own filename, we have to make sure that we aren't always overwriting the same file.
         * This is a simple solution that checks if we already have a file with this name.
         * If we do, we append a date/time to it to make it unique.
         */
        if ( file_exists( $this->full_file_path ) ) {
            $this->full_file_path = get_temp_dir() . $name . ' - ' . date( 'Y.m.d G.i.s') . '.pdf';
        }
    }

    protected function getFullPathName()
    {
        return $this->full_file_path;
    }
}
