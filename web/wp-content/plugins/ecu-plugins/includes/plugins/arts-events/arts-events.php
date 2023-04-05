<?php

namespace Ecu_Plugins\Arts_Events;

// define shortcode for page
add_shortcode('ecu_arts_events', 'Ecu_Plugins\Arts_Events\show_events');

function show_events(){
	return '<script type="text/javascript" src="https://calendar.ecu.edu/widget/view?schools=ecu&types=127734&days=365&num=50&show_view_all_cta=0&template=modern"></script>

	<style type="text/css">
		.localist_widget_container .action_button a { background: #592a8a !important;
		 color: #fff !important; }
		.localist_widget_container .action_button a:hover { color: #fec923 !important; }
	</style>
';
}