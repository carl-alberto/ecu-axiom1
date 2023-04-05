(function( $ ) {
	'use strict';

	 jQuery(document).ready(function(){
		 jQuery('.slide-post select').change(function(){
			 let option = jQuery('option', this);
			 let count = option.length;
			 let id = option[count-1].value;

			 jQuery.ajax({
				 type: 'POST',
				 url: '/wp-admin/admin-ajax.php',
				 data: {
					 action: 'post_banner_validate',
					 id : id,
				 },
				 success: function (response) {
					 if(!response.success){
						 alert(response.data);
					 }
				 },
			 });
		 });

		 jQuery('.ribbon-scheme .acf-input select').each(function(){
			 ribbonColor(this);
		 });
		 jQuery('body').on('change', '.ribbon-scheme .acf-input select', function(){
			 ribbonColor(this);
		 });

		 // remove tablepress loaded stylesheet
		 jQuery("label[for='option-extra-css-classes'] .description").remove();
		 jQuery('a[href*="tablepress_options"]').remove();

		 // removes parent categories
		// jQuery("#newcategory_parent").remove();
		 //jQuery("#newfeatured-author_parent").remove();
		 //jQuery("#tagsdiv-post_tag").remove();

 		setTimeout(function(){

			// hides ui select if value has already been chosen
 			if(jQuery("#ui_element_type")){
 				if(jQuery("#ui_element_type .acf-input select").val() != 'none'){
 					jQuery("#ui_element_type").css("display", "none");
 				}
 			}

 		}, 100);

		function ribbonColor(el){
			let parent = jQuery(el).parent().parent().parent();
 			let content = parent.find('.ribbon-content');
			let contentLabel = content.find('.acf-label label');
			let contentDesc = content.find('.acf-label .description');
 			let callout = parent.find('.ribbon-callout');
			let calloutLabel = callout.find('.acf-label label');
			let calloutDesc = callout.find('.acf-label .description');
 			let scheme = jQuery(el).val();
			let primary;
			let priText;
			let priDesc;
			let secondary;
			let secText;
			let secDesc;
 			switch(scheme){
 				case 'style-1':
 					primary = '#41215E';
					priText = '#FFFFFF';
					priDesc = '#FFFFFF';
 					secondary = '#782670';
					secText = '#FFFFFF';
					secDesc = '#FFFFFF';
 					break;
 				case 'style-2':
 					primary = '#D8D7D3';
					priText = '#404041';
					priDesc = '#404041';
 					secondary = '#7b7569';
					secText = '#FFFFFF';
					secDesc = '#FFFFFF';
 					break;
 				case 'style-3':
 					primary = '#B1AFC5';
					priText = '#404041';
					priDesc = '#404041';
 					secondary = '#572C86';
					secText = '#FFFFFF';
					secDesc = '#FFFFFF';
 					break;
 				case 'style-4':
 					primary = '#59849b';
					priText = '#FFFFFF';
					priDesc = '#FFFFFF';
 					secondary = '#004261';
					secText = '#FFFFFF';
					secDesc = '#FFFFFF';
 					break;
 				case 'style-5':
 					primary = '#e0cb8a';
					priText = '#404041';
					priDesc = '#404041';
 					secondary = '#c09630';
					secText = '#FFFFFF';
					secDesc = '#FFFFFF';
 					break;
 				default:
 					primary = '#FFFFFF';
					priText = '#444444';
					priDesc = '#666666';
 					secondary = '#572C86';
					secText = '#FFFFFF';
					secDesc = '#FFFFFF';
 					break;
 			 }
 				jQuery(content).css('background-color', primary);
				jQuery(contentLabel).css('color', priText);
				jQuery(contentDesc).css('color', priDesc);
 				jQuery(callout).css('background-color', secondary);
				jQuery(calloutLabel).css('color', secText);
				jQuery(calloutDesc).css('color', secDesc);
		}
 	});
})( jQuery );


/*
$ribbon-4-main: #59849b;//light teal
$ribbon-4-main-text: $white;
$ribbon-4-sup: #004261;//dark teal
$ribbon-4-sup-text: $white;
$ribbon-4-accent: #C3C1D2;
//urine
$ribbon-5-main: #e0cb8a;
$ribbon-5-main-text: $drk-grey;
$ribbon-5-sup: #c09630;
$ribbon-5-sup-text: $white;
$ribbon-5-accent: #C3C1D2;*/
