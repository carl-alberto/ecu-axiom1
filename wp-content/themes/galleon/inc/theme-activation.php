<?php

/**
 *
 * On theme activate...
 * Convert all ACF fields to custom meta fields for:
 *      - Theme
 *      - Pages
 *      - Slideshows / Banners
 */

// post types

if( !class_exists( 'ACF' ) ) return;

// array (size=36)
//   0 => string 'page' (length=4)
//   1 => string 'revision' (length=8)
//   2 => string 'nav_menu_item' (length=13)
//   3 => string 'oembed_cache' (length=12)
//   4 => string 'tablepress_table' (length=16)
//   5 => string 'attachment' (length=10)
//   6 => string 'nf_sub' (length=6)
//   7 => string 'sidebar' (length=7)
//   8 => string 'post' (length=4)
//   9 => string 'envira' (length=6)
//   10 => string 'ui-elements' (length=11)
//   11 => string 'competency' (length=10)
//   12 => string 'college' (length=7)
//   13 => string 'program' (length=7)
//   14 => string 'counselor' (length=9)
//   15 => string 'js_output_element' (length=17)
//   16 => string 'customize_changeset' (length=19)
//   17 => string 'mts_notification_bar' (length=20)
//   18 => string 'wprss_feed_template' (length=19)
//   19 => string 'wprss_feed' (length=10)
//   20 => string 'service' (length=7)
//   21 => string 'faq' (length=3)
//   22 => string 'tutorial' (length=8)
//   23 => string 'custom_css' (length=10)
//   24 => string 'project' (length=7)
//   25 => string 'wprss_feed_item' (length=15)
//   26 => string 'physician_location' (length=18)
//   27 => string 'physician' (length=9)
//   28 => string 'da_image' (length=8)
//   29 => string 'achievement-type' (length=16)
//   30 => string 'mycred_rank' (length=11)
//   31 => string 'badges' (length=6)
//   32 => string 'submission' (length=10)
//   33 => string 'et_pb_layout' (length=12)
//   34 => string 'tribe_venue' (length=11)
//   35 => string 'tribe_events' (length=12)


add_action('after_switch_theme', function() {
    class Galleon_Activation {

        /**
         * Custom post types to convert on theme init
         */
        public const ALLOWED_TYPES = [
            'page',
            'post',
            'counselor',
            'service',
            'faq',
            'tutorial',
            'project',
            'physician_location',
            'physician',
            'competency',
            'college',
            'program'
        ];

        /**
         * Array of posts that matches the above post type
         */
        public $posts = [];

        /**
         * Sets the current page being converted
         */
        public $current = [];

        /**
         * ID of the set front page on current site
         */
        public $front_page = 0;

        public function __construct(){

            /**
             * Fetches all pages based on allowed post types
             */
            //$this->posts = get_posts( [ 'numberposts'   => -1, 'post_type' => self::ALLOWED_TYPES ] );

            /**
             * Sets front page
             */
            $this->front_page = absint( get_option( 'page_on_front' ) );
        }

        /**
         * Initiates conversion process
         */
        public function exec(){
            $this->migrate_options();

            // ENABLE THIS TO PROCESS ALL POSTS ON THEME ACTIVATION
            // foreach( $this->posts as $post ){
            //     $this->current = $post;
            //     $this->migrate_post();
            // }
        }

        /**
         * Migrates all sitewide ACF options to custom meta fields
         */
        private function migrate_options(){
            $theme_mods = get_option( 'theme_mods_gangplank ');
            update_option( 'theme_mods_galleon', $theme_mods );

            $admin_page_template = get_option( 'options_default_templates_admin_page_template' );
            $admin_post_template = get_option( 'options_default_templates_admin_post_template' );
            $location_name = get_option( 'options_contact_location_name', 'East Carolina University');
            $address = get_option( 'options_contact_address' );
            $locality = get_option( 'options_contact_locality' );
            $phone = get_option( 'options_contact_phone' );
            $link = get_option( 'options_contact_link' );

            $expand_posts = get_field('expand_posts', 'option' );
            $hide_post_meta = get_field('hide_post_meta', 'option' );
            $blog_sidebar = get_field('blog_sidebar', 'option');
            $category_sidebar = get_field('category_sidebar', 'option');
            $author_sidebar = get_field('author_sidebar', 'option');
            $date_sidebar = get_field('date_sidebar', 'option');
            $footer_type = get_field('footer_type', 'option');

            $admin_page_template = empty( $admin_page_template ) ? 'full-width' : str_replace( ['page-', '.php'], '', $admin_page_template );
            $admin_post_template = empty( $admin_post_template ) ? 'full-width' : str_replace( ['page-', '.php'], '', $admin_post_template );
            $phone = empty( $phone ) ? '' : preg_replace( '/[^0-9]/', '', $phone );
            $address = empty( $address ) ? 'East 5th Street' : $address;
            if(empty($locality)) {
               $city = 'Greenville';
               $state = 'NC';
               $zipcode = '27858';
            } else {
                $locality = explode(' ', $locality); // ex Greenville, NC 27858-4353 USA
                $city = empty( $locality[0] ) ? 'Greenville' : preg_replace("/[^A-Za-z ]/", '', $locality[0]);
                $state = empty( $locality[1] ) ? 'NC' : substr(preg_replace("/[^A-Za-z ]/", '', $locality[1]),0,2);
                $zipcode = empty( $locality[2] ) ? '27858' : substr(preg_replace("/[^0-9 ]/", '', $locality[2]),0,5);
            }
            $link = isset( $link['title'] ) ? get_page_by_title( $link['title'] ) : 0;
            $link = is_object( $link ) ? $link->ID : 0;

            $expand_posts = $expand_posts === false ? 0 : 1;
            $hide_post_meta = $hide_post_meta === false ? 0 : 1;

            $blog_sidebar = $blog_sidebar === false ? 0 : absint( $blog_sidebar );
            $category_sidebar = $category_sidebar === false ? 0 : absint( $category_sidebar );
            $author_sidebar = $author_sidebar === false ? 0 : absint( $author_sidebar );
            $footer_type = $footer_type === false ? 0 : absint( $footer_type );

            update_option( 'ecu_page_template', $admin_page_template );   // 'page-sidebar-left.php'
            update_option( 'ecu_post_template', $admin_post_template );   // 'page-sidebar-right.php'

            update_option( 'ecu_blog_sidebar', $blog_sidebar );                  // 1834
            update_option( 'ecu_category_sidebar', $category_sidebar );      // 1834
            update_option( 'ecu_author_sidebar', $author_sidebar );            // 1834
            update_option( 'ecu_date_sidebar', $date_sidebar );                  // 1834

            update_option( 'ecu_expand_posts', $expand_posts );                     // true
            update_option( 'ecu_hide_meta', $hide_post_meta );                 // true
            update_option( 'ecu_phone', $phone );                          // 'none'
            update_option( 'ecu_contact', $link );                          // 'none'
            update_option(
                'ecu_address',
                json_encode ( [
                    'location' => $location_name,
                    'address' => $address,
                    'city' => $city,
                    'state' => $state,
                    'zip'   => $zipcode
                ] )
            );
        }

    /**
     * Migrates individual ACF post fields to custom meta fields
     * This code is a refactor of /themes/galleon/inc/theme-activation.php
     */
    private function migrate_post( $post ){

        $hasBanner = $this->migrate_banner( $post );

        $template = get_page_template_slug( $post->ID );

        if( $template === 'page-sidebar-left.php' || $template === 'page-sidebar-right.php' ){
            update_post_meta( $post->ID, '_wp_page_template', 'page-sidebar.php' );
        }

        if( $template === 'page-full-width.php' ){
            update_post_meta( $post->ID, '_wp_page_template', 'page.php' );
        }

        // pages
        $banner_image = get_field( 'banner_image', $post->ID );            // 760
        $h1_title = get_field( 'h1_title', $post->ID );                    // 'Alternate Page Title'
        $banner_full_width = get_field( 'banner_full_width', $post->ID );  // false
        $sidebar_selector = get_field( 'sidebar_selector', $post->ID );    // 1834 || false
        $hide_h1_title = get_field( 'hide_h1_title', $post->ID );          // false
        $back_to_top = get_field( 'back_to_top', $post->ID );              // false

        if( !$hasBanner && !empty( $banner_image ) )
            update_post_meta( $post->ID, 'ecu_banner', json_encode(
                [
                    'type' => 'image',
                    'image' => $banner_image['url']
                ]
            ) );

        update_post_meta( $post->ID, 'ecu_alt_title', $h1_title );
        update_post_meta( $post->ID, 'ecu_banner_full', $banner_full_width );
        update_post_meta( $post->ID, 'ecu_sidebar', $sidebar_selector );
        update_post_meta( $post->ID, 'ecu_hide_h1', $hide_h1_title );
        update_post_meta( $post->ID, 'ecu_to_top', $back_to_top );
        update_post_meta( $post->ID, 'ecu_sidebar_position', $template === 'page-sidebar-left.php' ? false : true );

        if( $post->post_type !== 'post' ) return;

        // posts
        $secondary_title = get_field( 'secondary_title', $post->ID );      // 'Secondary Title'
        $external_post = get_field( 'external_post', $post->ID );          // 'https://www.google.com/'

        update_post_meta( $post->ID, 'ecu_subtitle', $secondary_title );
        update_post_meta( $post->ID, 'ecu_spark', $external_post );
    }

    private function migrate_banner( $post ){

        $banner_type = get_field( 'banner_type', $post->ID );

        switch( $banner_type ){
            case 'slideshow':
                $data['type'] = 'slideshow';
                $data['slides'] = [];
                $slides = get_field( 'slideshow', $post->ID );

                foreach( $slides as $slide ){
                    $slide_obj = [];
                    $slide_obj['type'] = $slide['slide_type'];

                    if( $slide['slide_type'] === 'image' ){
                        $slide_obj['image'] = $slide['image']['url'];

                        if( $slide['enable_caption'] ){
                            $slide_obj['caption'] = $slide['caption']['description'];
                            $slide_obj['caption_position'] = $slide['caption']['position'];
                            $slide_obj['heading'] = $slide['caption']['title'];
                        }
                    } else {
                        $slide_obj['post'] = $slide['post']->ID;
                        $slide_obj['caption_position'] = $slide['caption_position'];
                    }
                    $data['slides'][] = $slide_obj;
                }
                break;
            case 'video':
                $data['type'] = 'video';
                $banner_video = get_field( 'video', $post->ID );
                $data['video'] = attachment_url_to_postid( $banner_video );
                break;
            case 'posts':
                $data['type'] = 'posts';
                $data['category'] = get_field( 'category', $post->ID );
                $data['caption_position'] = get_field( 'caption_position', $post->ID );
                $data['number_of_posts'] = get_field( 'number_of_posts', $post->ID );
                break;
            case 'image':
                $data['type'] = 'image';
                $banner_image = get_field( 'banner_image', $post->ID );
                $data['image'] = $banner_image['url'];
                break;
            default:
                return false;

        }

        update_post_meta( $post->ID, 'ecu_banner', json_encode( $data ) );
        return true;
    }
}

    $galleon = new Galleon_Activation();
    $galleon->exec();
}, 10, 0);