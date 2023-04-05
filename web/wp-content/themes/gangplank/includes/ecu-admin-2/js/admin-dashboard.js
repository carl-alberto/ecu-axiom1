jQuery(document).ready(function($) {
	$.ajax({
		url: 'https://wordpress.ecu.edu/release/wp-json/wp/v2/posts?per_page=1',
		dataType: 'json',
		success: function(result) {
			$('#release_log .hndle span').html(result[0].title.rendered);
			$('#release_log .content').html(result[0].content.rendered);
		},
		failure: function(result) {
		}
	});

	$.ajax({
		url: 'http://local.ecu.edu/wp-json/wp/v2/pages/1002',
		dataType: 'json',
		success: function(result) {

			// Output Notices
			let notices = result.acf.notices;
			let noticeOutput = '';
			if(notices.length > 0){
				for(n = 0; n < notices.length; n++){
					noticeOutput += '<div class="ecu-dash-alert alert '+notices[n].type+'" role="alert">'+notices[n].meta.icon+notices[n].message+'</div>';
				}
			} else {
				noticeOutput += '<p>There are no active alerts</p>';
			}
			$('#dash_notices .content').html(noticeOutput);

			// Output Tips


			// Output Links
			let links = result.acf.links;
			let linkOutput = '<ul>';
			if(links.length > 0){
				for(n = 0; n < links.length; n++){
					linkOutput += '<li><a href="'+links[n].link.url+'" target="'+links[n].link.target+'">'+links[n].icon+links[n].link.title+'</a></li>';
				}
				linkOutput += '</ul>';
			} else {
				linkOutput += '<li>No links available</li></ul>';
			}
			$('#dash_links .content').html(linkOutput);
		},
		failure: function(result) {
		}
	});
});
