<?php
/**
 * LDAP Login
 *
 * @package     LdapLogin
 * @author      ATWebDev
 * @copyright   2019 East Carolina University
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Ldap-Login
 * Plugin URI:  https://itcs.ecu.edu/departments/academic-technologies/web-services/
 * Description: Authenticates against ECU AD.
 * Version:     1.0.0
 * Author:      ATWebDev
 * Author URI:  https://itcs.ecu.edu/departments/academic-technologies/web-services/
 * Text Domain: ldap-login
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace Ldap_Login;
use \WP_Error as WP_Error;
use \Ldap\Ad_User as Ad_User;

defined( 'ABSPATH' ) || exit;

// Replace default wp authentication with our custom auth method.
remove_filter( 'authenticate', 'wp_authenticate_username_password',20);
remove_filter( 'authenticate', 'wp_authenticate_email_password',20);
remove_filter( 'authenticate', 'wp_authenticate_spam_check',99);
add_filter( 'authenticate', __NAMESPACE__ . '\authenticate_username_ldap', 10, 3);

/**
 * Validates a local users ( currently on atwebdev ) and users in AD and makes sure they are assigned to the given blog.
 *
 * @param  object $user       A WP User object or NULL normally, but ignored by this function.
 * @param  string $username   The username that was in the login field
 * @param  string $password   The password that was in the passwords field
 * @return WP User | WP Error Returns either a WP User object if the user validates or a WP Error object if there was an error validating
 */
function authenticate_username_ldap( $user, $username = NULL, $password = NULL ) {

  // 5.2 stopped check if the username was null in the wp_signon function in the user.php.  So have to do it here now.
  if(empty($username)) {
    return;
  }

  // Assume no one is authenticated or authorized for the site.
  $authorized = false;
  $authenticated = false;

  /**
   * Authenticate
   */
  try {
    $ad_user = new Ad_User($username);

    if ($ad_user->authenticate($password) && $ad_user->is_valid()) {
      $authenticated = true;
    }

    if(!$authenticated) {
      // Keep error message generic to not give an attacker knowledge.
      return new WP_Error( 'error',
      __( 'The Pirate ID or Password were not correct.' ));
    }

    /**
     * Authorize
     */
    if(is_super_admin($ad_user->get_wp_id()) || is_user_member_of_blog($ad_user->get_wp_id(), get_current_blog_id())) {
      $authorized = true;
    }

    if($authorized) {
      $user = get_user_by('login', $username);
      return $user;
    } else {
      // They failed authorization
      // Keep error message generic to not give an attacker knowledge.
      return new WP_Error( 'error',
      __( 'The Pirate ID or Password were not correct.' ));
    }
  } catch (\Exception $e) {
    // When unable to reach ldap then re-enable local user authentication.
    return wp_authenticate_username_password( NULL, $username, $password );
  }
}