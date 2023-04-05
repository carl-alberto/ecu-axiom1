<?php

namespace Ecu_Plugins;

add_action("wp_ajax_ecu_connect", "Ecu_Plugins\ajax_ecu_connect");
add_action("wp_ajax_nopriv_ecu_connect", "Ecu_Plugins\ajax_ecu_connect");
function ajax_ecu_connect () {
	try {
		wp_send_json_success(render_connect_table());
	} catch (Exception $e) {
		wp_send_json_error('Error: ' . $e->getMessage());
	}
}

function render_connect_table() {
	$db = new Ecu_Database();
	$mydb = $db->get_homepage_db();

    if(isset($_POST['ecu_connect_org']) && !empty($_POST['ecu_connect_org'])) {
    	$total = 1;
    	$pager = new Ecu_Pager($total);

		$data = \Database\Homepage::query("
			SELECT homepage_connect_organizations.id as org_id, homepage_connect_organizations.title, homepage_connect_organizations.short_url, homepage_connect_organizations_sites.site_id, homepage_connect_organizations_sites.url as social_url, social_media_sites.*
	    	FROM homepage_tools.homepage_connect_organizations
			LEFT JOIN homepage_tools.homepage_connect_organizations_sites ON homepage_connect_organizations.id = homepage_connect_organizations_sites.org_id
			LEFT JOIN homepage_tools.social_media_sites ON homepage_connect_organizations_sites.site_id = social_media_sites.id
			WHERE homepage_connect_organizations.id = ?
			ORDER BY homepage_connect_organizations.title, social_media_sites.sort_order
		", array($_POST['ecu_connect_org']));
    } else {
 		$total = $mydb->get_var('SELECT COUNT(id) FROM homepage_tools.homepage_connect_organizations');
 		$pager = new Ecu_Pager($total);
		$data = \Database\Homepage::query("
			SELECT homepage_connect_organizations.id as org_id, homepage_connect_organizations.title, homepage_connect_organizations.short_url, homepage_connect_organizations_sites.site_id, homepage_connect_organizations_sites.url as social_url, social_media_sites.*
			FROM homepage_tools.homepage_connect_organizations
			LEFT JOIN homepage_tools.homepage_connect_organizations_sites ON homepage_connect_organizations.id = homepage_connect_organizations_sites.org_id
			LEFT JOIN homepage_tools.social_media_sites ON homepage_connect_organizations_sites.site_id = social_media_sites.id
			WHERE homepage_connect_organizations.id IN ( SELECT * FROM ( SELECT id FROM homepage_tools.homepage_connect_organizations LIMIT {$pager->get_limit_start()}, {$pager->get_limit_end()}) id )
			ORDER BY homepage_connect_organizations.title, social_media_sites.sort_order
		");
	}

 	$pager->ajax_function = 'refresh_connect_table';

 	$str = '';

    if(empty($data)) {
        $str .= '
	  	<table class="ecu-connect-table table table-striped">
	  	<tr>
	  		<td>There are currently no social media accounts registered with the university.</td>
	  	</tr>
	  	</table>';
    } else {

    	if($total > 1) {
			$str .= '
			<div class="row">
			    <div class="col-md-6"><div class="ecu-connect-info ">
			      ' . $pager->page_summary() . '
			    </div></div>
			    <div class="col-md-6"><div class="pull-right">
			      ' . $pager->page_navigation_control() . '
			    </div></div>
		  	</div>';
		}

		$str .= '
	  	<table class="ecu-connect-table table table-striped">';

	  	$current = '';
	  	foreach ($data as $connect) {
	  		if($current != $connect->org_id) {
	  			if(!empty($current)) {
	  				$str .= '</td></tr>';
	  			}
	  			$str .= '
	  			<tr>
	  				<td class="ecu-connect-td"><span class="ecu-connect-title">' . $connect->title . '</span></td>
	  				<td class="ecu-connect-td">';
	  			$current = $connect->org_id;
	  		}
	  		$str .= '<a target="_blank" class="ecu-connect-link" rel="noopener" href="' . $connect->social_url .'"><img class="ecu-connect-social-image" src="' . CDN_IMAGE_URL . 'connect/' . $connect->logo_32 . '" alt="' . $connect->title . ' ' . $connect->name . '" /></a>';
	  	}

		$str .= '</table>';

		if($total > 1) {
			$str .= '
			<div class="row">
			    <div class="col-md-6"><div class="ecu-connect-info ">
			      ' . $pager->page_count() . '
			    </div></div>
			    <div class="col-md-6"><div class="pull-right">
			      ' . $pager->page_navigation_control() . '
			    </div></div>
		  	</div>';
		 }
	}

	return $str;
}

function render_connect() {

		$orgs = \Database\Homepage::query("
			SELECT *
			FROM homepage_tools.homepage_connect_organizations
			ORDER BY homepage_connect_organizations.title
		");
   	$str = '';
    if(!empty($orgs)) {
        $str .= '
		<form method="post">
		<div class="form-group">
		<label for="connect">Organizations</label>  <div id="ajax-result"></div>
		<select onchange="javascript:update_connect_grid_org_select();" id="connect_org_select" class="form-control" name="ecu_connect_org">
		<option value=""></option>';

		foreach($orgs as $o) {
			$str .= '<option value="' . $o->id . '" ';
			if($_POST['ecu_connect_org'] == $o->id) {
				$str .= 'selected';
			}
			$str .= '>' . $o->title . '</option>';
		}

		$str .= '
		</select>
		</div>
		</form>';
	}

	$str .= '<div id="ecu-connect-container">';
	$str .= render_connect_table();
	$str .= '</div>';

	return $str;
}
