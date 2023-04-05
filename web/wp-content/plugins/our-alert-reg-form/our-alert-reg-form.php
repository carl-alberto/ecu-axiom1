<?php

namespace OUR\ALERT\REG\FORM;

/**
 * Plugin Name: Our Alert Reg Form Hijacker
 * Description:  This plugin intercepts the submission of the alert registration form and saves it to the tools database so that it can be retrieved by RAVE and managed through tools.ecu.edu
 * Version: 1.0.0
 * Text Domain: our-alert-reg-form
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Take sumbission of ninja forms alert registration form and save to tools database
 *
 * @see https://developer.ninjaforms.com/codex/submission-processing-hooks/
 */
add_action( 'ninja_forms_after_submission', __NAMESPACE__ . '\after_submission' );
function after_submission( $form_data ){
	// If the form id is changed you need to update it here!
	if ($form_data['form_id'] == 2) {

		$mydb = new \wpdb(getenv('TOOLS_DB_USER'), getenv('TOOLS_DB_PASSWORD'), getenv('TOOLS_DB_NAME'), getenv('TOOLS_DB_HOST'));

		$mydb->insert('alert_registration',	array(
			'registrationdate' => date("Y-m-d H:i:s"),
			'firstname' => sanitize_text_field($form_data['fields']['5']['value']),
			'lastname' => sanitize_text_field($form_data['fields']['6']['value']),
			'association' => sanitize_text_field($form_data['fields']['9']['value']),
			'cellnumber' => sanitize_text_field($form_data['fields']['8']['value']),
			'email' => sanitize_text_field($form_data['fields']['7']['value']),
			'studentname' => sanitize_text_field($form_data['fields']['10']['value']),
			'studentyear' => sanitize_text_field($form_data['fields']['16']['value']),
		));
    }

	return $form_data;
}