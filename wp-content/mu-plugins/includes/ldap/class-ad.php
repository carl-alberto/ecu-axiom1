<?php

namespace Ldap;

defined( 'ABSPATH' ) OR exit;

require_once( __DIR__ .  '/vendor/autoload.php');

/**
 * Class ECU AD
 *
 * Provides an ADLDAP 2 provider with ECU connections.  Use as a parent class for the ECU LDAP Library.
 *
 * @see https://adldap2.github.io/Adldap2/#/
 */
class Ad
{
	/**
	 * ADLDAP2 provider
	 *
	 * @see https://adldap2.github.io/Adldap2/#/?id=quick-start
	 *
	 * @var ADLDAP Object
	 */
	protected $ad;

	/**
	 * Initiliazes the ADLDAP2 provider with the default and student connections
	 *
	 * @see https://adldap2.github.io/Adldap2/#/setup
	 */
	public function __construct() {
		$config = [
			'default' => [
				'base_dn' => LDAP_DN,
				'hosts' => [ LDAP_HOST ],
				'username' => LDAP_USERNAME,
				'password' => LDAP_PASSWORD,
				'account_suffix' => LDAP_SUFFIX,
				'use_ssl' => true,
				'port' => 636
			],
			// Need two connections for auth of student accounts.  The account suffix could be appended manually when auth but this provides cleaner code.
			'student' => [
				'base_dn' => LDAP_DN,
				'hosts' => [ LDAP_HOST ],
				'username' => LDAP_USERNAME,
				'password' => LDAP_PASSWORD,
				'account_suffix' => LDAP_STUDENT_SUFFIX,
				'use_ssl' => true,
				'port' => 636
			]
		];

		$this->ad = new \Adldap\Adldap($config);
	}
}