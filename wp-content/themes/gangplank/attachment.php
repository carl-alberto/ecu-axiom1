<?php include('includes/media-attachment.php'); ?>
<?php get_header(); ?>
<?php
	$attachment = new Media_Attachment(get_the_ID());
	$embed = $attachment->get_embed(array(
		'width' => '100%',
		'height' => '400px'
		));
	$size = $attachment->get_human_readable_filesize();
	$download = $attachment->get_download();
?>
<div class="container">
  <main id="main">
	<h1><?php the_title(); ?></h1>
	<p><?php //the_content();
	apply_filters('the_content', get_post_field('post_content', get_the_ID())); ?></p>
	<p><?php echo $download; ?></p>
	<?php echo do_shortcode($embed); ?>
  </main>
</div>
<?php get_footer(); ?>
