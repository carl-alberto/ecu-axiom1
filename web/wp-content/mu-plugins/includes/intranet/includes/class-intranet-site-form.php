<?php

namespace Intranet;
use \Mu_Plugins\Form as Form;

defined( 'ABSPATH' ) OR exit;

/**
 * Provides a forms functionality ( data validation/sanitation, submission process, error/result reporting).
 * This is not meant to be form generator.   Also provides some data retrieval functions that exclude things like the info.ecu.edu site
 * or the administrator role.    
 */
class Site_Form extends Form
{
    use Settings;

    public function __construct() {
        $this->init_settings();
    }
    
    public function process()
    {
        if(3 === $this->type && empty($this->ad_accounts) && empty($this->ad_groups)) {
            $this->errors[] = 'Please provide at least one AD group or valid pirate_id!';
        }

        if(empty($this->errors)) {
            $this->save_settings();

            if($this->get_enabled()) {
                update_option('default_role', Intranet::USER_ROLE);
            } else {
                update_option('default_role','blog_owner');
            }


            $this->message = '<div id="message" class="updated notice is-dismissible"><p><strong>Success! </strong> Settings Updated!</p></div>';
        } else {
            foreach ($this->errors as $err) {
                $this->message .= '<div id="message" class="error notice is-dismissible"><p><strong>Error! </strong>'.$err.'</p></div>';
            }

        }
    }
}