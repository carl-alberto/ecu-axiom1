<?php

if (!defined('ABSPATH') || !class_exists('NF_Abstracts_Action')) {
    exit;
}

/**
 * Class NF_Pdf_Submissions_Actions_Pdf
 */
final class NF_Pdf_Submissions_Actions_Pdf extends NF_Abstracts_Action
{
    /**
     * @var string
     */
    protected $_name  = 'pdf';

    /**
     * @var array
     */
    protected $_tags = array();

    /**
    * @var array
    */
    protected $_settings = [];

    /**
     * @var string
     */
    protected $_timing = 'late';

    /**
     * @var int
     */
    protected $_priority = '9';

    /**
    * Constructor
    */
    public function __construct()
    {
        parent::__construct();

        $this->_nicename = __('Pdf', 'pdf');

        $settings = NF_Pdf_Submissions::config('Settings');

        $this->_settings = array_merge($this->_settings, $settings);
    }

    public function process($action_id, $form_id, $data)
    {
        return $data;
    }
}
