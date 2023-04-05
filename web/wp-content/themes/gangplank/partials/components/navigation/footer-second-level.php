<?php if(is_second_level()): ?>
	<div id='footer'>
		<div class='filter'></div>
		<div class='container'>
			<div class='row'>
				<?php
					$menu = get_menu_from_tools(4);
					if(count($menu) > 0) {
						$footerMenu = array_chunk($menu, ceil(count($menu) / 4));
					} else {
						$footerMenu = array();
					}
				?>

				<div class='col-6 col-sm-3 col-md-2'>
					<ul>
						<?php
							if(isset($footerMenu[0]))
							foreach ($footerMenu[0] as $key => $menuItem){
								if ($menuItem->is_external == true){
									echo "<li><a class='ecu-event-tracking' data-ga-category='Footer' data-ga-action='" . $menuItem->link . "' href='" . $menuItem->link . "' target='_blank' rel='noopener noreferrer'>" . $menuItem->link_text . "</a></li>";
								} else {
									echo "<li><a class='ecu-event-tracking' data-ga-category='Footer' data-ga-action='" . $menuItem->link . "' href='" . $menuItem->link . "'>" . $menuItem->link_text . "</a></li>";
								}
							}
						?>
					</ul>
				</div>
				<div class='col-6 col-sm-3 col-md-2'>
					<ul>
						<?php
							if(isset($footerMenu[1]))
							foreach ($footerMenu[1] as $key => $menuItem){
								if ($menuItem->is_external == true){
									echo "<li><a class='ecu-event-tracking' data-ga-category='Footer' data-ga-action='" . $menuItem->link . "' href='" . $menuItem->link . "' target='_blank' rel='noopener noreferrer'>" . $menuItem->link_text . "</a></li>";
								} else {
									echo "<li><a class='ecu-event-tracking' data-ga-category='Footer' data-ga-action='" . $menuItem->link . "' href='" . $menuItem->link . "'>" . $menuItem->link_text . "</a></li>";
								}
							}
						?>
					</ul>
				</div>
				<!-- REMOV HIDDEN -->
				<div class='hidden-xs hidden-sm col-md-4'></div>
				<div class='col-6 col-sm-3 col-md-2'>
					<ul>
						<?php
							if(isset($footerMenu[2]))
							foreach ($footerMenu[2] as $key => $menuItem){
								if ($menuItem->is_external == true){
									echo "<li><a class='ecu-event-tracking' data-ga-category='Footer' data-ga-action='" . $menuItem->link . "' href='" . $menuItem->link . "' target='_blank' rel='noopener noreferrer'>" . $menuItem->link_text . "</a></li>";
								} else {
									echo "<li><a class='ecu-event-tracking' data-ga-category='Footer' data-ga-action='" . $menuItem->link . "' href='" . $menuItem->link . "'>" . $menuItem->link_text . "</a></li>";
								}
							}
						?>
					</ul>
				</div>
				<div class='col-6 col-sm-3 col-md-2'>
					<ul>
						<?php
							if(isset($footerMenu[3]))
							foreach ($footerMenu[3] as $key => $menuItem){
								if ($menuItem->is_external == true){
									echo "<li><a class='ecu-event-tracking' data-ga-category='Footer' data-ga-action='" . $menuItem->link . "' href='" . $menuItem->link . "' target='_blank' rel='noopener noreferrer'>" . $menuItem->link_text . "</a></li>";
								} else {
									echo "<li><a class='ecu-event-tracking' data-ga-category='Footer' data-ga-action='" . $menuItem->link . "' href='" . $menuItem->link . "'>" . $menuItem->link_text . "</a></li>";
								}
							}
						?>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div id="univ-footer">
		<div class='container'>
			<div class='row'>
				<div class='col-12 col-sm-6'>
					<div id='univ-name'>East Carolina University</div>
					<div id='univ-address'><a class='ecu-event-tracking' data-ga-category='Footer' data-ga-action='Address' target='_blank' rel='noopener noreferrer' href='https://www.google.com/maps/place/East+Carolina+University'>East 5th Street | Greenville, NC 27858</a> | <a class='ecu-event-tracking' data-ga-category='Footer' data-ga-action='Phone' href="tel:+12523286131">252-328-6131</a></div>
				</div>
				<div class='col-12 col-sm-6 text-sm-right'>
				<div id='access-links'>©<?php echo date("Y"); ?>  | <a class='ecu-event-tracking' data-ga-category='Footer' data-ga-action='Terms' href='http://<?php echo getenv('TOPSITE_ENV'); ?>/terms'>Terms of Use</a> | <a class='ecu-event-tracking' data-ga-category='Footer' data-ga-action='Accessibility' href='https://accessibility.ecu.edu/'>Accessibility</a> | <a class='ecu-event-tracking' data-ga-category='Footer' data-ga-action='Report a Barrier' href='https://accessibility.ecu.edu/report-an-accessibility-barrier/?referrer=<?php bloginfo('wpurl'); ?>'>Report a Barrier</a></div>
				</div>
			</div>
			<div class='row'>
				<div class='col-12 col-sm-6'>
					<div id='univ-actions'>
						<a class='btn btn-ecu-gold ecu-event-tracking' data-ga-category='Footer' data-ga-action='Give' href='https://give.ecu.edu/s/722/17/advancement/home.aspx'>GIVE TO ECU <span class="fa fa-chevron-right" aria-hidden="true"></span></a>
						<a class='btn btn-ecu-gold ecu-event-tracking' data-ga-category='Footer' data-ga-action='Apply' href='http://<?php echo getenv('TOPSITE_ENV'); ?>/apply'>APPLY <span class="fa fa-chevron-right" aria-hidden="true"></span></a>
					</div>
				</div>
				<div aria-hidden="true" class='col-xs-12 col-sm-6 text-sm-right'>
					<div id="google_translate_element"></div>
				</div>
			</div>
		</div>
	</div>



	<!-- Google Translate -->
	<script type="text/javascript">

			function googleTranslateElementInit() {
				new google.translate.TranslateElement({
					pageLanguage: 'en'
				}, 'google_translate_element');
				//Fix accessibility issues with google translate.
				$('img.goog-te-gadget-icon').attr('alt','Google Translate');
    		$('div#goog-gt-tt div.logo img').attr('alt','Google Translate');
    		$('div#goog-gt-tt .original-text').css('text-align','left');
    		$('.goog-te-gadget-simple .goog-te-menu-value span').css('color','#000000');
    		$('.goog-te-combo').attr('aria-label', 'Google Translate');
    		$('.goog-te-combo').change(function(){
				$('#nav').css('marginTop', '40px');
			});
		}
	</script>
	<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<?php endif; ?>
