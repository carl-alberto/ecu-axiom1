<?php
/**
 * @var $heading included
 */
	/**
	 * Print widget if there is no alert
	 * @author Daniel Krochmalny <krochmalnyd@ecu.edu>
	 *
	 * @return NULL
	 */
	function printNoAlert()	{
		echo "<div class='bs-callout bs-callout-success ecu-alert-widget'>
		<div class='row'>
		<div class='col-xs-3' data-mh='alert-widget'>
			<div class='alert-icon'>
				<span class='fa fa-check fa-2x' aria-hidden='true'></span>
			</div>
		</div>
		<div class='col-xs-9'>
			<div class='alert-content' data-mh='alert-widget'>
				<div class='header'>No Current Alerts</div>
			</div>
		</div>
			</div>
		</div>";

	}

	/**
	 * Print widget if there is an alert
	 * @author Daniel Krochmalny <krochmalnyd@ecu.edu>
	 *
	 * @return NULL
	 */
	function printAlert($alert)	{
		echo "<div class='bs-callout bs-callout-danger ecu-alert-widget'>
		<a href='https://". getenv('TOPSITE_ENV')."/alert' target='_blank'>
		<div class='row'>
		<div class='col-xs-3' data-mh='alert-widget'>
			<div class='alert-icon'>
				<span class='fa fa-exclamation fa-2x' aria-hidden='true'></span>
			</div>
		</div>
		<div class='col-xs-9' >
			<div class='alert-content' data-mh='alert-widget'>
				<div class='header'>".$alert->title."</div>
			</div>
		</div>
			</div>
			</a>
		</div>";
	}

	//GET DATA
	// $mydb = new wpdb(getenv('HOMEPAGE_DB_USER'),getenv('HOMEPAGE_DB_PASSWORD'),getenv('HOMEPAGE_DB_NAME'),getenv('HOMEPAGE_DB_HOST'));
	// $rows = $mydb->get_results("select * from rave_alerts where deleted = 0 and expiration >= NOW() and effective <= NOW()");
	$rows = \Database\Homepage::query("
		SELECT *
		FROM rave_alerts
		WHERE deleted = 0
			AND expiration >= NOW()
			AND effective <= NOW()
	", NULL, false);

	//print heading
	if (isset($instance['heading'])) {
		echo "<h3>".$instance['heading']."</h3>";
	}
	//print alerts
	if (count($rows)>0) {
		foreach ($rows as $alert) {
			printAlert($alert);
		}
	}	else    {
		printNoAlert();
	}
?>
