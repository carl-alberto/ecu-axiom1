jQuery(document).ready(function() {
	jQuery('#tablepress-default-inline-css').remove();

	if(jQuery('body').hasClass('post-php') || jQuery('body').hasClass('post-new-php')){
		display_sidebar_selector();
	}
});


function display_sidebar_selector(){
	var template = jQuery('#page_template').val();
	if(jQuery('body').hasClass('post-type-page') && template == 'default'){
		if(templates.page == 'page-full-width.php'){
			jQuery('#acf-group_594a86dd4bd58').remove();
		}
	} else if(jQuery('body').hasClass('post-type-post') && template == 'default'){
		if(templates.post == 'page-full-width.php'){
			jQuery('#acf-group_594a86dd4bd58').remove();
		}
	}
}
