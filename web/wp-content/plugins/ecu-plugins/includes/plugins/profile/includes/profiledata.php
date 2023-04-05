<?php
namespace Ecu_Plugins;

class Profile_Data {

	//Used to get phone number for tel link
	public static function strip_phone($phone){
		$phone = preg_replace("/[^0-9]/","",$phone);
		$phone = strlen($phone) != 10 ? '252' . $phone : $phone;
		if($phone[1] != 1) {
			$phone = '1' . $phone;
		}

		return $phone;
	}

	// Formats phone number for display, adds area code if not supplied and dashes
	public static function format_phone( $phone ){
		$origphone = $phone;
		$phone = preg_replace("/[^0-9]/","",$phone);
		$phone = strlen($phone) != 10 ? '252-' . substr( $phone, 0, 3 ) . '-' . substr( $phone, 3, 7) : $origphone;
		return $phone;
	}

	// Used to get aria label for tel link
	// The spaces in the label tell the screen reader to read each digit individually. The period after the area code and the exchange tell
	// the screen reader to pause (like at the end of a sentence).
	public static function aria_phone($phone){
		// Remove formatting
		$phone = preg_replace("/[^0-9]/","",$phone);
		$phone = strlen($phone) != 10 ? '252' . $phone : $phone;

		if($phone[1] != 1) {
			$phone = '1' . $phone;
		}
		// Add spaces after every number
		$phone = trim( chunk_split($phone, 1, ' ') );

		// Add periods after each group.
		$phone = substr_replace($phone, '.', 1, 0);
		$phone = substr_replace($phone, '.', 8, 0);
		$phone = substr_replace($phone, '.', 15, 0);
		$phone .= '.';

		return $phone;
	}

	public static function get_profile($pirate_id) {
		$result = array(
				'ad_account'=>null,
				'mailstop_url'=>null,
		);

		if( $pirate_id !== '' ) {


			$user = new \Ldap\Ad_User();
			$user->set_user($pirate_id);

			if (!$user->is_valid()) {
				$str = "User could not be found";
			} else {
				$department = $user->get_user()->getDepartment();
				if (isset($department) && preg_match('/^[A-Za-z]{0,3} /', $department, $division_matches)) {
					$result['division'] = $division_matches[0];
					$result['dept'] = trim(substr($department, strlen($result['division'])));
				}

				$result['ad_account'] = $user;

				// Check to see if we can map the mailstop number to a campus map location
				if ($instance['hide_mailstop'] != 'true' && $user->get_user()->getCompany() != '') {
					// Mailstop display is enabled, let's see if we can get the first value and verify it's an integer
					$mailstop = explode(' ', $user->get_user()->getCompany());
					if (array_count_values($mailstop) > 0 && is_numeric($mailstop[0])) {
						// Query the DB for the "code name" of the building
						// Find the building map URL
						$building_code = \Database\Tools::query('
							SELECT code
							FROM homepage_tools.university_buildings ub
							INNER JOIN homepage_tools.university_buildings_mailstops ubm
							ON ub.id = ubm.building_id
							WHERE ubm.mailstop = ?
							LIMIT 1', array($mailstop[0])
						);
						if (trim($building_code[0]->code) != '') {
							$url = 'https://'. getenv('TOPSITE_ENV') . '/buildings/' . urlencode($building_code[0]->code);
							$result['mailstop_url'] = $url;
						}
						$result['mailstop'] = $mailstop[0];
					}
				}
			}
		}

		return $result;
	}
}
