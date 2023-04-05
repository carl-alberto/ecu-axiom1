/**
 * data is an array passed from con-directory.php.
 * {string} DATA.mode - Indicates which directory to render (0 - 5).
 * {string} DATA.columns - How many columns for directory display.
 * {array} DATA.employees - Employee objects.
 * {string} DATA.order - List of PID values of employees to be pushed to top of display.
 * {array} DATA.debug - PHP debugging messages.
 */
var DATA = data;

 /** Sets of attributes that must be matched for an employee to be listed in a given directory. */
var FILTERS = [
	{
		Enabled: true,
		TimeStatus: "Full-time"
	},
	{
		Enabled: true,
		TimeStatus: "Full-time",
		Classification: "Administration"
	},
	{
		Enabled: true,
		TimeStatus: "Full-time",
		Classification: "Faculty",
		Department1: "Baccalaureate"
	},
	{
		Enabled: true,
		TimeStatus: "Full-time",
		Classification: "Faculty",
		Department1: "Advanced"
	},
	{
		Enabled: true,
		TimeStatus: "Full-time",
		Classification: "Faculty",
		Department1: "Science"
	},
	{
		Enabled: true,
		TimeStatus: "Full-time",
		Classification: "Staff"
	}
];

// Map jQuery to $ to avoid typing 'jQuery' out every time we need to use it.
( function ( $ ) {
	/**
	 * Set configuration variabes, process data, and set vue data on load.
	 */
	$( function () {
		var content = [];
		var employees = data.employees;

		// Determine which employees belong in this directory.
		try {
			employees = filter( employees, FILTERS[ parseInt( data.mode ) ] );
		} catch( error ) {
			fail(error);
		}
		// Sort employees for current directory by last name.
		employees.sort( objSort( 'LastName' ) );
		
		// Employees that to be listed first, out of abc order.
		if ( data.order ) {
			employees = rankObj( employees, data.order.split( "," ) );
		}
		vm.content = doLists( employees, content, parseInt( data.columns ) );
	} );
} )( jQuery );

/**
 * 
 * @param {array} employee - Employee objects ready to be displayed in directory.
 * @param {array} content - Holds employee objects sorted into equal columns-arrays.
 * @param {int} columnCnt - Number of columns for current directory display.
 * @return {array} - Column-arrays of employee objects.
 */
function doLists( employees, content, columnCnt ) {
	var tempEmployees = employees;
	var employeesLen = employees.length;
	var numCols = columnCnt;
	var loopCnt = numCols;
	var cnt = Math.ceil( employeesLen / numCols );
	for ( var i = 0; i < loopCnt; i++ ) {
		var col = [];
		col = employees.slice( 0, cnt );
		content.push( col );
		employees = tempEmployees.slice( cnt * ( i + 1 ), tempEmployees.length );
		numCols--;
		employeesLen -= cnt;
	}
	return content;
}

/**
 * Pull specified items from abc order and push to the top.
 * @param {array} profiles - All profile objects for current directory, in abc order.
 * @param {array} pids - PIDs of employees to be moved to top of directory listing.
 * @return {array} - Employee objects in order for display.
 */
function rankObj( profiles, pids ) {
	var regularProfiles = [];
	var topProfiles = [];
	var onList;
	profiles.forEach( function ( profile ) {
		onList = false;
		pids.forEach( function ( pid ) {
			if ( profile.UserID.trim().toLowerCase() === pid.trim().toLowerCase() ) {
				onList = true;
				topProfiles.push( profile );
			}
		} );
		if ( ! onList ) {
			regularProfiles.push( profile );
		}
	} );
	return topProfiles.concat( regularProfiles );
}

/**
 * Array sort function: order objects by the key and order provided.
 * 
 * @param {string} key - Key to be used to for sorting.
 * @param {string} order - How objects should be ordered, ascending by default.
 * @return {int} - 0 if neither object has property, 1 if object a ranks higher, -1 otherwise.
 */
function objSort( key, order ) {
	order = order === 'desc' ? order : 'asc';
	return function ( a, b ) {
		if ( ! a.hasOwnProperty( key ) || ! b.hasOwnProperty( key ) ) {
			// property doesn't exist on either object
			return 0;
		}

		var varA = ( typeof a[ key ] === 'string' ) ?
			a[ key ].toUpperCase() : a[ key ];
		var varB = ( typeof b[ key ] === 'string' ) ?
			b[ key ].toUpperCase() : b[ key ];

		var comparison = 0;
		if ( varA > varB ) {
			comparison = 1;
		} else if ( varA < varB ) {
			comparison = -1;
		}
		return (
			( order === 'desc' ) ? ( comparison * -1 ) : comparison
		);
	};
}

function fail( error ) {
	console.log( error );
	$( '#app' ).html( "<h3>There was an error loading directory data.</h3>" );
}

/**
 * Use filter conditions to pick out employees with correct attributes for current directory.
 * 
 * @param {array} profiles - Employee directory listing objects.
 * @param {object} conditions - Attributes to be matched for current directory.
 * @return {array} - Employee objects to be included in current directory.
 */
function filter( profiles, conditions ) {
	var arr = [];
	var matched;
	profiles.forEach( function ( profile ) {
		matched = true;
		jQuery.each( conditions, function ( key, value ) {
			matched = matched && ( profile[ key ] === value || profile[ key ].toString().toLowerCase().indexOf( value.toString().toLowerCase() ) >= 0 );
		} );
		if ( matched )
			arr.push( profile );
	} );
	return arr;
}

/**
 * Check to see if given string is null or empty.
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
		content: []
	}
} );