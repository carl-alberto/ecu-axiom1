$(document).ready(function() {
	$('#searchBox').on('keypress',function(e) {
	    if(e.which == 13) {
	        window.location.href = "https://" + degree_search_script_obj.env + "/degrees?search=" + $(this).val();
	    }
	});

	$('#searchBtn').click(function(){
		window.location.href = "https://" + degree_search_script_obj.env + "/degrees?search=" + $('#searchBox').val();
	});

});