<?php

add_action('plugins_loaded', function() {
    register_block_type('wp-blocks/book-details', array(
        'render_callback' => 'block_book_details'
    ));
});

function block_book_details( $attributes ) {
  // Parse attributes
  $book_details_imageObj = $attributes['image'];
  $book_details_image_url = $book_details_imageObj['sizes']['full']['url'];
  $book_details_image_alt_text = $book_details_imageObj['alt'];
  $book_details_image_width = $book_details_imageObj['sizes']['full']['width'] / 2;

  $book_details_have_read = $attributes['haveRead'];
  $book_details_title = $attributes['title'];
  $book_details_author = $attributes['author'];
  $book_details_summary = $attributes['summary'];
  $book_details_quotes = $attributes['quotes'];

  ob_start(); // Turn on output buffering

  /* BEGIN HTML OUTPUT */
?>
  <div class="block-book-details">
    <?php if ($book_details_have_read) : ?>
      <p><em>This book has been read.</em></p>
    <?php endif; ?>

    <?php if ($book_details_image_url) : ?>
      <img class="book-details-image" src="<?php echo $book_details_image_url; ?>" alt="<?php echo $book_details_image_alt_text; ?>" width="<?php echo $book_details_image_width; ?>" />
    <?php endif; ?>

    <h3 class="block-book-details-title"><?php echo $book_details_title; ?></h3>
    <span class="block-book-details-author"><?php echo $book_details_author; ?></span>

    <div class="block-book-details-summary">
      <?php echo $book_details_summary; ?>
    </div>

    <div class="book-details-quotes">
		<?php
		  foreach($book_details_quotes as $quote) :
		?>
      <blockquote class="book-details-quote-<?php echo $quote['id']; ?> book-details-quote">
        <?php echo $quote['content']; ?>
        <cite><?php echo $quote['pageRef']; ?></cite>
      </blockquote>
    <?php
		  endforeach;
		?>
    </div>
  </div>
<?php
  /* END HTML OUTPUT */

  $output = ob_get_contents(); // collect output
  ob_end_clean(); // Turn off ouput buffer

  return $output; // Print output
}
