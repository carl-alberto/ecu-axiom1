/**
 * data is an array passed from con-directory.php.
 * {string} DATA.mode - Indicates which directory to render (0 - 5).
 * {string} DATA.columns - How many columns for directory display. - Not used here.
 * {array} DATA.employees - Employee objects.
 * {string} DATA.order - List of PID values of employees to be pushed to top of display. - Not used here.
 */
const DATA = data;

// Map jQuery to $ to avoid typing 'jQuery' out every time we need to use it.
( function ( $ ) {

	/**
	 * Initialize variables, build profile object, and set vue data.
	 */
	$( function () {
		const employee = DATA.employees;
		const tabsData = employee.Tabs;
		const tabsOn = tabsData ? tabsData[ '#overview' ] || tabsData[ '#scholarship' ] ||
		 tabsData[ '#teaching' ] || tabsData[ '#service' ] || tabsData[ '#honorsAwards' ] : false;

		vm.profile = {
			image: employee.ImageURL,
			name: buildName( employee ),
			title: trimOrEmpty( employee.Title ),
			rank: trimOrEmpty( employee.Rank ),
			department: trimOrEmpty( employee.Department1),
			office: trimOrEmpty( employee.OfficeNumber + ' ' + employee.Building ),
			phone: trimOrEmpty( employee.PhoneNumber ),
			email: trimOrEmpty( employee.Email ),
			tabs: tabsData,
			doTabs: tabsOn,
			enabled: employee.Enabled
		}
	} );

} )( jQuery );

/**
 * Build employee name and attach credentials if applicable.
 * @param {object} profile - Data for complete employee profile.
 */
function buildName( profile ) {
	const name = trimOrEmpty( profile.FirstName ) + ' ' + trimOrEmpty( profile.MiddleName ) + ' ' + trimOrEmpty( profile.LastName );
	const creds = trimOrEmpty(profile.Credential).length > 0 ? ( ', ' + profile.Credential ) : '';
	return name + creds;
}

/**
 * Activate the employee's first tab when the profile does not have the template's default first tab.
 */
function activateTab() {
	let activeTab = $( '.tabz' )[ 0 ];
	$( activeTab ).trigger( 'click' );
}

/**
 * Check to see if given string is null or empty before trimming whitespace.
 * 
 * @param {string} string - String to check and trim.
 * @return {string} - Trimmed string or empty string if parameter was null or undefined.
 */
function trimOrEmpty( string ) {
    return ( ( string === undefined ) || ( string === null ) ) ? '' : string.trim();
};

// Initialize vue.
var vm = new Vue( {
	el: '#app',
	data: {
		profile: {}
	},
	mounted: function () {
		window.onload = function () {
			// Make sure the first enabled tab is displayed if profile does not include the Overview tab.
			// Timeout needed for IE support, for some reason.
			this.setTimeout(activateTab, 100);
		  }
	}
} );