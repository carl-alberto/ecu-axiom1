
function update_connect_grid_org_select (e) {
	jQuery('#ajax-result').attr('class', 'fa fa-spinner fa-pulse');
	var selected = jQuery('#connect_org_select').val();
	jQuery.ajax({
		type: 'POST',
		url: '/wp-admin/admin-ajax.php',
		data: {
			action: 'ecu_connect',
			ecu_connect_org: selected,
		},
		error: function (response){
			jQuery('#ajax-result').fadeOut('slow', function() {
			    jQuery('#ajax-result').attr('class', '');
			    jQuery('#ajax-result').show();
			});
			console.log(response);
		},
		success: function (response) {
			jQuery('#ajax-result').fadeOut('slow', function() {
			    jQuery('#ajax-result').attr('class', '');
			    jQuery('#ajax-result').show();
			});
			//console.log(response.data);
			jQuery('#ecu-connect-container').html(response.data);
		},
	});
}

function refresh_connect_table (current_page, page_size) {
	jQuery('#ajax-result').attr('class', 'fa fa-spinner fa-pulse');
	//var selected = jQuery('#ecu_pager_size_control').val();
	//console.log(page_size);
	//console.log(selected);
	//if(selected != page_size) {
	//	page_size = selected;
	//}
	//console.log(page_size);
	jQuery.ajax({
		type: 'POST',
		url: '/wp-admin/admin-ajax.php',
		data: {
			action: 'ecu_connect',
			ecu_pager_current: current_page,
			//ecu_pager_page_size: page_size,
		},
		error: function (response){
			jQuery('#ajax-result').fadeOut('slow', function() {
			    jQuery('#ajax-result').attr('class', '');
			    jQuery('#ajax-result').show();
			});
			console.log(response);
		},
		success: function (response) {
			jQuery('#ajax-result').fadeOut('slow', function() {
			    jQuery('#ajax-result').attr('class', '');
			    jQuery('#ajax-result').show();
			});
			//console.log(response.data);
			jQuery('#ecu-connect-container').html(response.data);
		},
	});
}