<?php

class NF_Pdf_Submissions_Pdf_Document
{

    private $pdf_doc;

    /**
     * Use the deprecated domPDF library
     * @var boolean
     */
    private $use_dompdf;

    public function __construct()
    {
        $this->use_dompdf = apply_filters('ninja_forms_use_dompdf', false);

        if ($this->use_dompdf) {

            $this->pdf_doc = new NF_Deprecated_PDF_Document();
        } else {

            require_once(plugin_dir_path(__FILE__) . '../../vendor/autoload.php');

            $this->pdf_doc = new NF_Pdf_Submissions_Pdf_DocumentMaster();
        }
    }

    public function setFields($fields)
    {
        $this->pdf_doc->setFields($fields);
    }

    public function setTitle($title)
    {
        $this->pdf_doc->setTitle($title);
    }

    public function setData($data)
    {
        $this->pdf_doc->setData($data);
    }

    public function setSubmission($sub)
    {
        $this->pdf_doc->setSubmission($sub);
    }

    public function setFormId( $formId)
    {
        $this->pdf_doc->setFormId($formId);
    }
    public function setTemplate($template)
    {
        $this->pdf_doc->setTemplate($template);
    }

    public function setTemplateDirectory($dir)
    {
        $this->pdf_doc->setTemplateDirectory($dir);
    }

    public function export($name = '', $dest = 'D')
    {
       $return = $this->pdf_doc->export($name, $dest);
       
       return $return;
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
        $return = $this->pdf_doc->getTemplate($template_name, $args, $template_path, $default_path);

        return $return;
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
        $return = $this->pdf_doc->locateTemplate($template_name, $template_path, $default_path);

        // Return what we found
        return $return;
    }

    /**
     * Set should merge tag fields be populated?
     *
     * Feature only available on mPDF construction.
     * @param  bool  $populateMergeFields  Should merge tag fields be populated?
     *
     * @return  NF_Pdf_Submissions_Pdf_Document
     */
    public function setPopulateMergeFields(bool $populateMergeFields): NF_Pdf_Submissions_Pdf_Document
    {

        if (!$this->use_dompdf) {
            $this->pdf_doc->setPopulateMergeFields($populateMergeFields);
        }
        return $this;
    }
}
