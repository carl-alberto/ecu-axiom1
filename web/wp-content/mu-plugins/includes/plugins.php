<?php

namespace Mu_Plugins;

defined( 'ABSPATH' ) || exit;

/**
 * Extend Preview link duration
 *
 * @see https://wordpress.org/plugins/public-post-preview/
 */
add_filter( 'ppp_nonce_life', __NAMESPACE__ . '\my_nonce_life', 10, 0 );
function my_nonce_life() {
  return DAY_IN_SECONDS * 30; // 30 days
}

/**
 * Change Default Capabilities for Ninja Forms to allow non admins access.
 *
 * @see https://cleody.com/post/559/grant-ninja-forms-backend-access-to-non-admins
 */
add_filter( 'ninja_forms_admin_parent_menu_capabilities', __NAMESPACE__ . '\ninja_forms_capabilities' );
add_filter( 'ninja_forms_admin_all_forms_capabilities', __NAMESPACE__ . '\ninja_forms_capabilities' );
add_filter( 'ninja_forms_admin_submissions_capabilities', __NAMESPACE__ . '\ninja_forms_capabilities' );
add_filter( 'ninja_forms_admin_add_new_capabilities', __NAMESPACE__ . '\ninja_forms_capabilities' );
add_filter( 'ninja_forms_admin_import_export_capabilities', __NAMESPACE__ . '\ninja_forms_capabilities' );
function ninja_forms_capabilities($capabilities) {
	return "edit_pages";
}