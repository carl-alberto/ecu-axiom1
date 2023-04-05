// Using the Vuelidate form validation plugin.
Vue.use( window.vuelidate.default );

// Establish constant values for validators Vuelidate will need.
var { required, minLength, alpha, email, maxLength } = window.validators;

// Configure the Vue instance.
var vm = new Vue({
	el: '#app',
	data: {
		first: '',
		middle: '',
		last: '',
		email: '',
		phone: '',
		address1: '',
		address2: '',
		city: '',
		state: '',
		zip: '',
		county: '',
		school: '',
		education: '',
		programs: []
	},
	methods: {
		submitForm: function () {
			this.$v.$touch();
			// Do not submit if validation fails
			if ( this.$v.$invalid ) {
				var vuelidator = this.$v;
				var fields = Object.keys( vuelidator.$params );

				for (var i = 0; i < fields.length; i++) {
					if ( vuelidator[fields[i]].$anyError ) {
						$( '#' + fields[i] ).focus();
						break;
					}
				}

				if ( vuelidator[ 'programs' ].$anyError ) {
					$( '#C_1' ).focus();
				}
			} else {
				// Validation passed - process selected programs.
				var programs = [];
				this._data.programs.forEach( function( item ) {
					// Trim 'C_' from program ID values.
					programs.push( item.substring( 2, item.length ) );
				} );

				// Sanitize user input and build request data.
				var requestData = {
					'FirstName': sanitize( this._data.first ),
					'MiddleName': sanitize( this._data.middle ),
					'LastName': sanitize( this._data.last ),
					'Email': sanitize( this._data.email ),
					'Phone': sanitize( this._data.phone ),
					'AddressLine1': sanitize( this._data.address1 ),
					'AddressLine2': sanitize( this._data.address2 ),
					'AddressCity': sanitize( this._data.city ),
					'AddressState': sanitize( this._data.state ),
					'AddressZip': sanitize( this._data.zip ),
					'County': sanitize( this._data.county ),
					'CurrentSchool': sanitize( this._data.school ),
					'Programs': programs,
					'EducationLevel': sanitize( this._data.education )
				};

				doPost( requestData );
			}

		}
	},
	// Validation error messages.
	computed: {
		firstErrorMsg() {
			if ( !this.$v.first.required ) {
				return 'First name is required.';
			}
		},
		lastErrorMsg() {
			if ( !this.$v.last.required ) {
				return 'Last name is required.';
			}
		},
		emailErrorMsg() {
			if ( !this.$v.email.required || !this.$v.email.email ) {
				return 'Email is required and must be in a valid format. (name@server.com)';
			}
		},
		countyErrorMsg() {
			if ( !this.$v.county.required ) {
				return 'Please provide the county you currently reside in.';
			}
		},
		programsErrorMsg() {
			return 'Please select at least one program.';
		},
		stateErrorMsg() {
			return 'Please use two letter state abbreviation.';
		}

	},
	// Form field validation parameters.
	validations: {
		first: {
			required,
			maxLength: maxLength(200)
		},
		middle: {
			maxLength: maxLength(100)
		},
		last: {
			required,
			maxLength: maxLength(200)
		},
		email: {
			required,
			email,
			maxLength: maxLength(200)
		},
		county: {
			required,
			maxLength: maxLength(100)
		},
		state: {
			maxLength: maxLength(50),
			alpha
		},
		programs: {
			required,
			minLength: minLength(1)
		},
		phone: {
			maxLength: maxLength(200)
		},
		address1: {
			maxLength: maxLength(200)
		},
		address2: {
			maxLength: maxLength(200)
		},
		city: {
			maxLength: maxLength(100)
		},
		zip: {
			maxLength: maxLength(20)
		},
		school: {
			maxLength: maxLength(200)
		}
	}
});

/**
 * Make ajax call to admission-inquiry.php using jQuery.
 * Form data needs to be a JSON string encoded to Base64.
 * 
 * @param {object} requestData - Sanitized form input data.
 * @return {string} - Response from the CON server.
 */
function doPost( requestData ) {
	$.post( ajax_nfo.ajax_url, {
		_ajax_nonce: ajax_nfo.nonce,
		action: 'form_submit',
		mode: ajax_nfo.mode,
		serialStudent: window.btoa( JSON.stringify( requestData ) )
	}, function ( response ) {
		if ( response === "OK" ) {
			success();
		} else {
			failure( response, requestData );
		}
	} );
}

/**
 * Replace form with success message to user.
 * 
 * @return none
 */
function success() {
	$( '#app' ).html( '<p class="alert alert-success">Thank you for your interest in our programs. Your submission has been received and someone will contact you soon.</p>' );
}

/**
 * Display error message to user and output server response and form data to console.
 * 
 * @param {string} response - Error message from server.
 * @param {string} requestData - Form data, Base64 encoded JSON string.
 */
function failure( response, requestData ) {
	$( '#app' ).html( '<p class="alert alert-danger">There was an error. Your form was not processed.' 
	+ '<br>Please contact <a href="mailto:ganzertj@ecu.edu"> Johathan Ganzert</a> with your request.</p>' );
	console.log( response );
	console.log( requestData );
}

/**
 * Remove any html tags or attributes from user input.
 * 
 * @param {string} input - User input from form fields.
 */
function sanitize( input ) {
	var clean = sanitizeHtml( input, {
		allowedTags: [],
		allowedAttributes: {},
		textFilter: function( text ) {
			return text.replace( /=/g, '' );
		}
	} );
	return clean;
}