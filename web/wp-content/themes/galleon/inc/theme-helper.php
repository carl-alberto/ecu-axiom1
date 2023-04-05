<?php defined( 'ABSPATH' ) || exit;

/**
 *
 * Defines helper functions used by other functions
 *
 */

/**
 * Returns URL of asset from theme assets folder
 *
 * @param   string      $file           Name of file to fetch
 * @param   bool        $dir            Returns absolute file path of file on filesystem
 *
 * @return  string
 */
if (!function_exists('fetch_asset')){
    function fetch_asset( $file = false, $dir = false ){
        if( !$file )
            return;

        if($dir){
            return get_template_directory() . '/assets/' . trim( $file, "/" );
        } else {
            return get_template_directory_uri() . '/assets/' . trim( $file, "/" );
        }
    }
}


function main_content_wrap(){
    echo is_singular() ? '<div id="entry-content">' : '<div class="container">';
}

/**
 * Returns whether or not current site should load second level functionality
 *
 * @return  bool
 */
function is_second_level(){
    return get_option("ecu_second_level");
}

/**
 * Simplified version of ACF's get_field; fetches single meta value for post / page
 *
 * @param   string     $key     Label for meta value to be returned
 * @param   int         $id     Post ID, defaults to current post
 *
 * @return  mixed
 */
function get_meta( $key, $id = false ){
    if( is_404() ) return;

    if($id){
        return get_post_meta( $id, $key, true );
    } else {
        global $post;
        //var_dump($post->ID);
        //var_dump(get_post_meta( $post->ID));
        return get_post_meta( $post->ID, $key, true );
    }
}

/**
 * Generates list of featured authors for post
 *
 * @return  string
 */
function get_post_authors(){
    global $post;

    $authors = wp_get_post_terms( $post->ID, 'featured-author' );

    $output = '<ul id="post-authors">';
        if(empty($authors)){
            $output .= '<li><a href="' . get_author_posts_url( $post->post_author ) . '">' . get_the_author_meta( 'display_name' ) . '</a></li>';
        } else {
            foreach($authors as $author){
                $output .= '<li><a href="' . get_term_link( $author->term_id ) . '">' . $author->name . '</a></li>';
            }
        }
    $output .= '</ul>';

    return $output;
}

/**
 * Formats 10 digit numerical string to phone number format
 *
 * @return  string
 */
function display_phone( $phone ){
    $formatted_phone = "(" . substr( $phone, 0, 3 ) . ") " . substr( $phone, 3, 3) . "-" . substr( $phone, 6, 4);
    return $formatted_phone;
}

/**
 * Checks if the current post has a hero_image meta field set
 *
 * @return bool     Whether or not the page has a banner
 */
function has_post_banner(){
    global $post;
    return get_meta('hero_image', $post->ID) ? true : false;
}

/**
 * Returns markup for page banner if image selected for banner type
 *
 * @return string   HTML markup for banner
 */
function the_post_banner($size = 'thumbnail', $class = false){
    global $post;
    echo wp_get_attachment_image( get_meta('hero_image', $post->ID), $size, false, ['class' => $class] );
}

/**
 * Outputs the markup for a slide based on its type
 *
 * @return string   HTML markup for designated slide
 */
function output_slide( $type = 'image', $meta, $i = false ){
    switch( $type ){

        /**
         * Banner is set to image
         */
        case 'image':
            $image = attachment_url_to_postid( $meta->image );
            if( $image ) echo wp_get_attachment_image( $image, 'full', false, ['class' => 'img-fluid'] );
            break;

        /**
         * Banner is set to slideshow; slide type is set to image
         */
        case 'slide_image':
            $image = attachment_url_to_postid( $meta->image );
            $banner = wp_get_attachment_image( $image, 'full', false, ['class' => 'img-fluid'] );            

            echo "<div class='slide-wrap slide-image'>" . $banner . slide_description( 'image', $meta ) . "</div>";
            break;

        /**
         * Banner is set to video
         */
        case 'video':
            if(!$meta->video) return;
            echo "<div id='video-wrap' class='slide-wrap'>
                <video id='hero-video' autoplay muted loop src='{$meta->video}'></video>
                <span id='video-controls' class='fas fa-pause'></span>
            </div>";
            break;

        /**
         * Banner is set to slideshow; slide type is set to post
         */
        case 'slide_post':
            if( !$meta->post ) return;
            $post = get_post( $meta->post );
            if( $json = get_post_meta( $post->ID, 'ecu_slideshow', true ) ){
                $json = json_decode( $json );
                if( $json->type === 'image' ){
                    $image = attachment_url_to_postid( $json->image );
                    $banner = wp_get_attachment_image( $image, 'full', false, ['class' => 'img-fluid'] );
                    echo "<div class='slide-wrap ugh'>" . $banner . slide_description( 'post', $meta, $post ) . "</div>";
                }
            } else {
                $featured = get_post_thumbnail_id( $post->ID );
                if( $featured ){
                    $banner = wp_get_attachment_image( $featured, 'full', false, ['class' => 'img-fluid'] );
                    echo "<div class='slide-wrap ugh-ugh'>" . $banner . slide_description( 'post', $meta, $post ) . "</div>";
                }
            }
            break;

        /**
         * Banner is set to posts
         */
        case 'posts':
            $posts = get_posts( [ 'category' => $meta->category, 'posts_per_page' => $meta->number_of_posts ] );
            foreach( $posts as $post ){
                if( $json = get_post_meta( $post->ID, 'ecu_slideshow', true ) ){
                    $json = json_decode( $json );
                    if( $json->type === 'image' ){
                        $image = attachment_url_to_postid( $json->image );
                        $banner = wp_get_attachment_image( $image, 'full', false, ['class' => 'img-fluid'] );
                        echo "<div class='slide-wrap'>" . $banner . slide_description( 'post', $meta, $post ) . "</div>";
                    }
                } else {
                    $featured = get_post_thumbnail_id( $post->ID );
                    if( $featured ){
                        $banner = wp_get_attachment_image( $featured, 'full', false, ['class' => 'img-fluid'] );
                        echo "<div class='slide-wrap'>" . $banner . slide_description( 'post', $meta, $post ) . "</div>";
                    }
                }
            }
            break;
        default:
            return null;
    }
}

/**
 * Outputs the markup for slideshow description boxes
 *
 * @return string   HTML markup for slide descriptions
 */
function slide_description( $type, $meta = false, $post = NULL ) {
    if (false === $post || false === $meta) {        
        return;
    }
    $positions = [
        'top-left',
        'top-right',
        'bottom-left',
        'bottom-right'
    ];
    if($type === 'image'){
        $title = $meta->heading;
        $caption = $meta->caption;
        $position = $meta->caption_position;
    } else {
        $caption = generate_excerpt( $post->post_content ) ;
        $title = $post->post_title;
        $position = $meta->caption_position;
        $more = "<br />
            <a
                href='" . get_the_permalink( $post->ID ) . "'
                aria-label='{$post->post_title}'
                title='Read more about {$post->post_title}'
                class='btn btn-sm'
            >
                Read More
                <span class='fas fa-chevron-right ml-2'></span>
            </a>"; 
    }
    if( !$position ) $position = $positions[ rand(0, 3)];

    return "<div class='slide-caption {$position}'>
        <div class='slide-caption-title'>
            {$title}
        </div>
        <p class='slide-caption-description'>
            {$caption}
            {$more}
        </p>
    </div>";
}
