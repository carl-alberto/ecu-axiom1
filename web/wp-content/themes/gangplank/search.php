<?php get_header(); ?>

  <div class="container">
    <main id="main">
			<div class="row">
				<div class="col-md-8 col-lg-9">
					<h1>Search results for: "<?php echo get_search_query(); ?>"</h1>
				</div>
				<div class="col-md-4 col-lg-3">
					<?php get_search_form(true); ?>
				</div>
			</div>
			<div class="results">
			<?php if (have_posts()) : while (have_posts()) : the_post();?>
				<div class="search-result">
					<a href="<?php the_permalink(); ?>"><h2><?php the_title(); ?></h2></a>
					<span class="search-link"><?php the_permalink(); ?></span>
					<p><?php gp_excerpt(get_the_id()); ?></p>
				</div>
			<?php endwhile; ?>
			<?php
global $wp_query;

$big = 999999999; // need an unlikely integer

echo paginate_links( array(
	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	'format' => '?paged=%#%',
	'current' => max( 1, get_query_var('paged') ),
	'total' => $wp_query->max_num_pages
) );
?>
		<?php else: ?>
		<h2>No results found.</h2>
		<p>Please refine your search criteria</p>
	<?php endif; ?>
		</div>
    </main>
  </div>

<?php get_footer(); ?>
