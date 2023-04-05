<?php defined( 'ABSPATH' ) || exit;

/**
 * Register dynamic callback for block
 * 
 * @return null
 */
add_action( 'plugins_loaded', function() {
    register_block_type( 'wp-blocks/news-feed', [
        'render_callback' => 'block_news_feed'
    ]);
});

/**
 * Fetches all news feeds from tools DB
 * 
 * @return array Array of value label options 
 */
add_action( 'wp_ajax_block_news_feed_feeds', 'block_news_feed_feeds' );
function block_news_feed_feeds() {
    $results = Database\Homepage::query("
        SELECT * 
        FROM homepage_tools.news_feeds 
        WHERE show_commonspot = 1
        ORDER BY name
    ");

    $options = [
        ['value' => null, 'label' => 'Select a News Feed' ]
    ];

    if( is_array( $results ) ) {
        foreach($results as $feed) {
            $options[] = [
                'value' => $feed->id,
                'label' => $feed->name,
            ];
        }
    }

    wp_send_json_success( $options );
}

/**
 * Returns news feed posts for block editor
 * 
 * @return array Array of posts from news feed
 */
add_action( 'wp_ajax_block_news_feed_posts', 'block_news_feed_posts' );
function block_news_feed_posts() {

    $feed = absint( $_GET[ 'feed' ] );
    $limit = absint( $_GET[ 'limit' ] );

    if( !$feed ) wp_send_json_error( 'Please specify a feed ID.' );

    $posts = get_news_feed_posts( $feed, $limit );

    wp_send_json_success( $posts );
}

/**
 * Returns news feed posts from database
 * 
 * @return array Array of posts from feed
 */
function get_news_feed_posts( $feed, $limit ){
    $posts = Database\Homepage::query("
        SELECT news_feeds.name, news_feeds_stories.feed_id, news_feeds_stories.story_id, news_feeds_stories.added, news_and_ecu_stories.title, news_and_ecu_stories.teaser, news_and_ecu_stories.carousel_image, news_and_ecu_stories.carousel_video, news_and_ecu_stories.carousel_video_poster, news_and_ecu_stories.status, news_and_ecu_stories.created, news_and_ecu_stories.published, news_and_ecu_stories.list_image, news_and_ecu_stories.list_video, news_and_ecu_stories.list_video_poster, news_and_ecu_stories.url,
        CASE news_and_ecu_stories.model
                    WHEN 'NewsStories' THEN 'news'
                    WHEN 'EcuStories' THEN 'ecu-stories'
            END AS ecu_asset_dir
        FROM homepage_tools.news_feeds_stories
        LEFT JOIN homepage_tools.news_and_ecu_stories ON news_feeds_stories.story_id = news_and_ecu_stories.id AND news_feeds_stories.model = news_and_ecu_stories.model
        LEFT JOIN homepage_tools.news_feeds ON news_feeds_stories.feed_id = news_feeds.id
        WHERE news_feeds_stories.feed_id = {$feed}
        AND news_and_ecu_stories.status = 'Published'
        ORDER BY news_feeds_stories.sort_order
        LIMIT {$limit}
    ");

    $output = [];

    foreach( $posts as $post ){
        $output[] = [
            'image'     => sprintf( 'https://cdn.ecu.edu/images/%s/%s', $post->ecu_asset_dir, $post->list_image ),
            'title'     => $post->title,
            'excerpt'   => $post->teaser,
            'url'       => $post->url
        ];
    }

    return $output;
}

/**
 * Renders markup for block
 * 
 * @return string The HTML for the block
 */
function block_news_feed( $attributes ) {
    
    $title = isset( $attributes['title'] ) ? sanitize_text_field( $attributes['title'] ) : false;
    $feed = isset( $attributes['feed'] ) ? absint( $attributes['feed'] ) : false;
    $limit = isset( $attributes['limit'] ) ? absint( $attributes['limit'] ) : 3;
    $class = isset( $attributes['className'] ) ? sanitize_text_field( $attributes['className'] ) : '';
    $center = wp_validate_boolean( $attributes['center'] ) ? 'text-center' : '';
    $media = isset( $attributes['media'] ) ? true : false;

    $posts = get_news_feed_posts( $feed, $limit );
    ob_start(); ?>
        <div class="wp-block-wp-blocks-news-feed public <?php echo $class; ?>">
        
            <?php if( $title ): ?>
                <h2><?php echo $title; ?></h2>
            <?php endif; ?>

            <div class="news-feed row">
                <?php foreach( $posts as $post ): ?>
                <div class="news-feed-item <?php echo $class ? '' : 'col-md-4'; ?> <?php echo $center; ?>">
                    <?php if( $media ): ?>
                        <figure>
                            <a href="<?php echo $post['url']; ?>" target="_blank">
                                <img src=<?php echo $post['image']; ?> />
                            </a>
                        </figure>
                    <?php endif; ?>
                    <div class="content">
                        <a href="<?php echo $post['url']; ?>" target="_blank">
                            <h3><?php echo $post['title']; ?></h3>
                        </a>
                        <p><?php echo $post['excerpt']; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php
    $output = ob_get_contents();
    ob_end_clean();
    
    return $output;
}
