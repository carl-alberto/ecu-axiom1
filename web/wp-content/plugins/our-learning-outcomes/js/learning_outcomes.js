jQuery(document).ready(function ($) {
	//functions
	function hideColls()		{
		$( "#college-wrap" ).addClass('d-none');
		$( "#program-wrap" ).addClass('d-none');
	}
	function hideComps()		{
		$( "#competency-wrap" ).addClass('d-none');
	}
	function showColls()		{
		$( "#college-wrap" ).removeClass('d-none');
		$( "#program-wrap" ).removeClass('d-none');

	}
	function showComps()		{
		$( "#competency-wrap" ).removeClass('d-none');
	}
	//hide and show appropriate boxes
	function setPostState() {
		var post_type = location.pathname.split('/')[1];
		if (post_type) {
			switch(post_type) {
				case 'competency':
					hideColls();
					showComps();
		  			break;
		  		case 'college':
		  		case 'program':
		  			showColls();
					hideComps();
		  			break;
		  		default:
		  			break;
		  	}
		}
	}

	//if coming home after selecting first dropdown, set state
	if (window.location.href == window.location.protocol + '//' + window.location.hostname + '/') {
		var lo = sessionStorage.getItem("lo");
		if ( lo != null ) {
			$( "#learning_outcome" ).val(lo);
		}    else    {
			$( "#learning_outcome" ).val(0);
		}
		$( "#college_selector" ).val(0);
		$( "#competency_selector" ).val(0);
		hideColls();
		hideComps();
		switch(lo) {
	  		case '0':
	  			hideColls();
	  			hideComps();
	  			break;
	  		case '1':
	  			showComps();
	  			hideColls();
	  			break;
	  		case '2':
	  			$( "#competency-wrap" ).addClass('d-none');
	  			$( "#college-wrap" ).removeClass('d-none');
	  			break;
	  		default:
	  			break;
	  	}
	}

	//go to page when college or comp or program is selected
  	$( '#competency_selector, #college_selector, #program_selector' ).change(function() {
		val = this.value;
		if (val != 0) {
			window.location.href = val;
		}
	});
	//load state base on post type
	setPostState();

	$( "#learning_outcome" ).change(function() {
			sessionStorage.setItem("lo",this.value);
		if (window.location.href != window.location.protocol + '//' + window.location.hostname + '/') {
			window.location = '/';
		}
	  	switch(this.value) {
	  		case '0':
	  			hideColls();
	  			hideComps();
	  			break;
	  		case '1':
	  			showComps();
	  			hideColls();
	  			break;
	  		case '2':
	  			$( "#competency-wrap" ).addClass('d-none');
	  			$( "#college-wrap" ).removeClass('d-none');
	  			break;
	  		default:
	  			break;
	  	}
	});
});
