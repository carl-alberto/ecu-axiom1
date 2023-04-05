<?php /* Template Name: News Feed Template */ ?>
<?php get_header(); ?>
<?php
	$feed_id = absint(get_query_var('news_feed_id', 1));
	$page = absint(get_query_var('page', 1));
	$per_page = 10;
	$offset = ( $page * $per_page ) - $per_page;

	$total = \Database\Homepage::query("
		SELECT COUNT(id) FROM homepage_tools.news_feeds_stories WHERE feed_id = ?
	", array($feed_id));

	$results = \Database\Homepage::query("
		SELECT news_feeds.name, news_feeds_stories.feed_id, news_feeds_stories.story_id, news_feeds_stories.added, news_and_ecu_stories.title, news_and_ecu_stories.teaser, news_and_ecu_stories.carousel_image, news_and_ecu_stories.carousel_video, news_and_ecu_stories.carousel_video_poster, news_and_ecu_stories.status, news_and_ecu_stories.created, news_and_ecu_stories.published, news_and_ecu_stories.list_image, news_and_ecu_stories.list_video, news_and_ecu_stories.list_video_poster, news_and_ecu_stories.url,
		CASE news_and_ecu_stories.model
		WHEN 'NewsStories' THEN 'news'
		WHEN 'EcuStories' THEN 'ecu-stories'
		END AS ecu_asset_dir
		FROM homepage_tools.news_feeds_stories
		LEFT JOIN homepage_tools.news_and_ecu_stories ON news_feeds_stories.story_id = news_and_ecu_stories.id AND news_feeds_stories.model = news_and_ecu_stories.model
		LEFT JOIN homepage_tools.news_feeds ON news_feeds_stories.feed_id = news_feeds.id
		WHERE news_feeds_stories.feed_id = ?
		AND news_and_ecu_stories.status = 'Published'
		ORDER BY news_feeds_stories.sort_order LIMIT ?, ?
	", array($feed_id, $offset, $per_page));


	if(empty($results)) {
		$results = array();
		$title = "News Feed";
	} else {
		$title = $results[0]->name;
	}

	$num_pages = floor($total[0] / $per_page);
?>

<div class="container">

	<h1><?php echo esc_html($title); ?></h1>

	<?php foreach($results as $item):?>
		<a href="<?php echo esc_url($item->url); ?>" class="blog-post no-text-decoration" style="display:block;">
			<div class="row">
		    	<div class="col-md-3">
		      		<img src="<?php echo esc_url(CDN_IMAGE_URL . $item->ecu_asset_dir . '/' . $item->list_image); ?>" alt="" class="img-stretch" />
		    	</div>
		    	<div class="col-md-9">
		      		<div class="blog-content">
		        		<h2><?php echo esc_html($item->title); ?></h2>
		        		<p><?php echo esc_html($item->teaser) . ' <i class="fa fa-chevron-right" aria-hidden="true"></i>'; ?></p>
		      		</div>
		    	</div>
		  	</div>
		</a>
	<?php
	endforeach;

	echo OUR\NEWS\FEED\pagination($feed_id, $num_pages, $page);
	?>

</div>


<?php get_footer(); ?>