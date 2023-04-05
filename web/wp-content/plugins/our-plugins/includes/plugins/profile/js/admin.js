jQuery(document).ready(function ($) {

   $(document).on("click", ".ecu_profile_upload_button", function (e) {
      e.preventDefault();
      var $button = $(this);
 
      // Create the media frame.
      var file_frame = wp.media.frames.file_frame = wp.media({
         title: 'Select or upload image',
         library: { // remove these to show all
            type: 'image' // specific mime
         },
         button: {
            text: 'Select'
         },
         multiple: false  // Set to true to allow multiple files to be selected
      });
 
      // When an image is selected, run a callback.
      file_frame.on('select', function () {
         // We set multiple to false so only get one image from the uploader
 
         var attachment = file_frame.state().get('selection').first().toJSON();
 
         $button.siblings('input').val(attachment.id);
         var newImg = document.createElement("IMG");
         newImg.src = attachment.sizes.thumbnail.url;
         $('.ecu_profile_pic_tn').html(newImg).trigger('change');
      });
 
      // Finally, open the modal
      file_frame.open();
   });
   
   // Handle the image remove button click
   $(document).on("click", ".ecu_profile_image_remove", function (e) {
      e.preventDefault();
      $('.ecu_profile_image').val('').trigger('change');
      $('.ecu_profile_pic_tn').html('');
   });
});
