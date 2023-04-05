<?php defined( 'ABSPATH' ) || exit;

/**
 *
 * Defines functions used throughout the theme
 *
 */

/**
 * Add the Google Tag Manager script.
 */
add_action( 'wp_head', __NAMESPACE__ . '\add_google_tag_script', 1, 0);
function add_google_tag_script() {
  echo "
  <!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i) {w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-PBWB2DS');</script>
  <!-- End Google Tag Manager -->";
}

/**
 * Add Google Tag code which is supposed to be placed after opening body tag.
 *
 * @see https://developer.wordpress.org/reference/hooks/wp_body_open/
 */
add_action( 'wp_body_open',  __NAMESPACE__ . '\add_google_tag_iframe', 10, 0);
function add_google_tag_iframe() {
    echo '
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBWB2DS"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->';
}

/**
 * Prints title
 *
 * @param   int         $id         ID of post, page
 * @param   bool        $echo       Whether to return or display title
 * @param   string      $before     HTML to prepend to title
 * @param   string      $after      HTML to append to title
 *
 * @return  string
 */
function print_title( $id = false, $echo = true, $before = '<h1>', $after = '</h1>' ) {
    global $post;

    if( $id ) {

        // Fetches title if ID is provided or if page is posts page
        $h1_title = get_meta( 'ecu_alt_title', $id );
        $title = $h1_title ? $h1_title : get_the_title( $id );

    } elseif( is_singular( ) ) {

        // Fetches title if post is singular of any post type
        $h1_title = get_meta( 'ecu_alt_title', $post->ID );
        $title = $h1_title ? $h1_title : $post->post_title;

    } elseif( is_archive( ) || is_home( ) ) {

        // Fetches title for archives
        if( is_home( ) ) {
            $id = get_option( 'page_for_posts' );
            $h1_title = get_meta( 'ecu_alt_title', $id );
            $title = $h1_title ? $h1_title : get_the_title( $id );
        } elseif( is_search( ) ) {
            $title = 'Search Results';
        } else {
            if($slug = get_query_var('featured-author', false)) {
                $term = get_term_by('slug', $slug, 'featured-author');
                $title = $term->name;
            } else {
                $title = get_the_archive_title( );
            }
        }

    } else {
        $title = '404 Page Not Found';
    }

    if( $hide = get_meta( 'ecu_hide_h1', $id ) ) {
        $before = '<h1 class="sr-only">';
        $after = '</h1>';
    }

    $title = $before . $title . $after;

    if( $echo ) {
        echo $title;
    } else {
        return $title;
    }
}

/**
 * Prints subtitle on posts page
 *
 * @return  string
 */
function print_subtitle( ) {
    global $post;
    if( !$subtitle = get_meta( 'ecu_subtitle', $post->ID ) )
        return;

    echo '<h2>' . $subtitle . '</h2>';
}

/**
 * Prints date and authors for post
 *
 * @return  string
 */
function print_post_meta( ) {
    if( get_option( 'ecu_hide_meta' ) )
        return;

    echo '<div id="post_meta">
        <div class="left"><p class="post-date">' . get_the_date( ) . '</p></div>
        <div class="right">' . get_post_authors( ) . '</div>
    </div>';
}

/**
 * Prints tools menu
 *
 * @param   int     $id         ID of menu in tools db
 * @param   bool    $columns    Whether to break up menu into column chunks
 */
function tools_menu( $id, $columns = false ) {
    $id = ( int ) $id;
    $menu = Database\Homepage::query( "
        SELECT menus_items.link, menus_items.link_text, menus_items.is_external, menus.title
        FROM `menus_items`
        INNER JOIN `menus`
        ON menus.id = menus_items.homepage_menus_id
        WHERE menus.id = ?
        ORDER BY menus_items.sort_order ASC
    ", array($id) );

    // If menu returns false or there are no menu items exit
    if( empty( $menu ) ) return;

    $output = "";
    if( !$columns ) {
        foreach( $menu as $item ) {
            $output .= "<li><a href='{$item->link}' target='" . ( $item->is_external ? "_blank" : "" ) . "'>{$item->link_text}</a></li>";
        }
    } else {

        // Resource menu returns 4 columns
        $colcount = $id == 8 ? 2 : 4;

        $columns = count( $menu ) > 0 ? array_chunk( $menu, ceil( count( $menu ) / $colcount ) ) : false;
        $output .= "<div class='row'>";
        foreach( $columns as $column ) {
            $output .= "<div class='col-6 col-md-3'>
                <ul>";
                foreach( $column as $item ) {
                    $output .= "<li><a href='{$item->link}' target='" . ( $item->is_external ? "_blank" : "" ) . "'>{$item->link_text}</a></li>";
                }
            $output .= "</ul>
            </div>";
        }
        $output .= '</div>';
    }
    echo $output;
}

if ( !function_exists( 'get_page_title' ) ) {
    /**
     * Gets the page title for <title></title> tags
     * @return string
     */
    function get_page_title() {
        global $wp_query;

        // If no search results are returned from our-google-search plugin, 
        // then "404 Not Found" message is displayed in tab title.
        $is_pagename_search_results = $wp_query->query_vars['pagename'] === 'search-results' ? true : false;

		$page_title = '';
    
        if ( is_search() || $is_pagename_search_results ) {
            $page_title = 'Search Results';
        } elseif ( is_404() ) {
            $page_title = 'Page not found';
        } elseif ( is_archive() ) {
            $page_title = get_the_archive_title();
        } else {
            $page_title = print_title( false, false );
        }

        return $page_title;
    }
}

/**
 * Prints title rendered in <title></title> tags
 */
function print_page_title( ) {
    // id, display, before html, after html
    $title_array = [
        'pieces'    =>  [
            get_page_title(),
            get_bloginfo( 'title' ),
            'ECU'
        ],
        'sep'       =>  ' | '
    ];

    /**
     * Modifies parts of title / separator
     *
     * @param   array       $title_array    Title parts and separator for title
     */
    apply_filters( 'edit_print_page_title', $title_array );

    $title = implode( $title_array['sep'], $title_array['pieces'] );

    echo strip_tags( $title );
}

/**
 * Prints address and contact information for footer
 */
function footer_contact( ) {

    $address = json_decode( get_option( 'ecu_address' ) );
    $output = "<p class='h4'>" . ( $address->location === '' ? 'East Carolina University' : 'East Carolina University<br />' . $address->location ) . "</p>";    
    $output .= "<address>" . ( $address->address === '' ? 'East 5th Street' : $address->address ) . ', ' . ( $address->city === '' ? 'Greenville' : $address->city ) . ', ' . ( $address->state === '' ? 'NC' : $address->state ) . ' ' .  ( $address->zip === '' ? '27858' : $address->zip ) . "</address>";

    if( $phone = get_option( 'ecu_phone' ) ) {
        $output .= "<a href='tel:${phone}'>" . display_phone( $phone ) . "</a>";
    } else {
        if( is_user_logged_in() ){
            $output .= "<a href='" . admin_url( 'admin.php?page=theme_options' ) . "'>Set your contact phone number</a>";
        } else {
            $output .= "<a href='tel:12523286131'>" . display_phone( '2523286131' ) . "</a>";
        }
    }


    if( $contact_page = get_option( 'ecu_contact' ) ) {
        $output .= " | <a href='" . get_the_permalink( $contact_page ) . "'>Contact Us</a>";
    } else {
        if( is_user_logged_in() ) $output .= " | <a href='" . admin_url( 'admin.php?page=theme_options' ) . "'>Set your contact page</a>";
    }

    echo $output;
}

/**
 * Prints an unordered list of hyperlinked category terms
 *
 * @return string
 */
function print_category_list( ) {
    $terms = get_terms( [
        'taxonomy'      =>      'category',
        'hide_empty'    =>      true
    ] );
    if( count( $terms ) > 0 ) {
        $output = "<div class='category-list'>
            <h3>Categories</h3>
            <ul>";
                foreach( $terms as $term ) {
                    $output .= "<li><a href='" . get_term_link( $term ) . "' class='reset'>{$term->name}</a></li>";
                }
            $output .= '</ul>
        </div>';
    } else {
        $output = '<p>No categories are available</p>';
    }
    echo $output;
}

/**
 * Outputs back to top button if option is active sitewide or on that page
 *
 * @return null
 */
function back_to_top( ) {
    global $post;
    $back_to_top = get_option( 'ecu_to_top' );
    $post = get_meta( 'ecu_to_top', $post->ID );

    if( ($back_to_top && !$post) || (!$back_to_top && !$post) )
        return;

    echo '<a href="#top" id="to-top"><span class="fa fa-chevron-up" aria-hidden="true" aria-label=”back to top”></span></a>';
}

/**
 * Prints an unordered list of post categories
 *
 * @return string
 */
function print_post_category_list() {
    if( get_option( 'ecu_hide_meta' ) )
        return;

    $cats = get_the_category();
    if(empty($cats))
        return;

    $output = '<ul class="post-categories">
        <li>Categories:</li>';
    foreach($cats as $cat) {
        $output .= '<li><a href="' . get_term_link( $cat->term_id ) . '">' . $cat->name . '</a></li>';
    }
    $output .= '</ul>';
    echo $output;
}

/**
 * Prints a hero / banner above content
 *
 * @return null
 */
function print_hero() {
    global $post;

    $meta = get_post_meta( $post->ID, 'ecu_banner', true );
    $banner_full_width = wp_validate_boolean( get_post_meta( $post->ID, 'ecu_banner_full', true ) );
    $banner = json_decode( $meta );
    $banner_image = get_field('banner_image', $post->ID);
    $description = $banner_image['caption'] ? $banner_image['caption'] : $banner_image['description'];

    if ( $banner == null ) { 
        return; 
    };
?>
    <section id="hero-wrapper">
<?php 
    if ( !$banner_full_width ) { 
        echo '<div class="container">'; 
    } 
?>
        <div id="hero">
<?php
    

    if ( $banner->type === 'slideshow' ) {
        

        if ( count( $banner->slides ) === 0 ) {
            return;
        }
        
        foreach ( $banner->slides as $slide ) {
            output_slide( 'slide_' . $slide->type, $slide );
        }
    } else {
        output_slide( $banner->type, $banner );
    }
?>
        </div> <!-- #hero -->
<?php 
    if ( !$banner_full_width ) { 
        echo '</div>'; 
    } 

    if ( is_singular('post') && $description ) { 
?>
        <!-- Output caption on single posts -->
        <div class="banner-caption">
            <div class="container">
                <figcaption>
                    <?php echo $description; ?>
                </figcaption>
            </div>
        </div>
<?php
    }
?>
    </section>
<?php
}

/**
 * Trims page excerpt to 30 words and adds trailing characters
 */
function generate_excerpt( $content ) {
    $excerpt = wp_trim_words( strip_tags($content), 30, ' […]' );
    return $excerpt;
}

if ( !function_exists( 'can_show_post_thumbnail' ) ) {
	/**
	 * Determines if post thumbnail can be displayed.
	 *
	 * @since Galleon 1.0
	 *
	 * @return bool
	 */
	function can_show_post_thumbnail() {
		/**
		 * Filters whether post thumbnail can be displayed.
		 *
		 * @since Galleon 1.0
		 *
		 * @param bool $show_post_thumbnail Whether to show post thumbnail.
		 */
		return apply_filters(
			'can_show_post_thumbnail',
			! post_password_required() && ! is_attachment() && has_post_thumbnail()
		);
	}
}

if ( !function_exists( 'display_search_post_thumbnail' ) ) {
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 *
	 * @since Galleon 1.0
	 *
	 * @return void
	 */
	function display_search_post_thumbnail() {
		if ( !can_show_post_thumbnail() ) {
			return;
		}

        if ( is_singular() ) { 
            ?>
			<figure class="post-thumbnail">
                <?php
                // Lazy-loading attributes should be skipped for thumbnails since they are immediately in the viewport.
                the_post_thumbnail( 'search-post-thumbnail', array( 'loading' => false ) );

                if ( wp_get_attachment_caption( get_post_thumbnail_id() ) ) {
                ?>
                <figcaption class="wp-caption-text">
                    <?php echo wp_kses_post( wp_get_attachment_caption( get_post_thumbnail_id() ) ); ?>
                </figcaption>
            <?php } ?>
			</figure> <!-- .post-thumbnail -->
        <?php } else { ?>
			<figure class="post-thumbnail">
				<a class="post-thumbnail-inner alignwide" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                    <?php the_post_thumbnail( 'search-post-thumbnail' ); ?>
				</a>
                <?php if ( wp_get_attachment_caption( get_post_thumbnail_id() ) ) { ?>
                    <figcaption class="wp-caption-text">
                        <?php echo wp_kses_post( wp_get_attachment_caption( get_post_thumbnail_id() ) ); ?>
                    </figcaption>
                <?php } ?>
			</figure> <!-- .post-thumbnail -->
<?php
		}
	}
}

if ( !function_exists( 'get_pagination' ) ) {
	function get_pagination() {
		ob_start();

		global $wp_query;

        $big = 999999999; // need an unlikely integer
		$pagination_base = str_replace( PHP_INT_MAX, '%#%', esc_url( get_pagenum_link( PHP_INT_MAX ) ) );
		$pagination_format = '?paged=%#%';
		$pagination_total = $wp_query->max_num_pages;
		$pagination_current = max( 1, absint( get_query_var( 'paged' ) ) );

		// https://developer.wordpress.org/reference/functions/paginate_links/
		$pagination = paginate_links( array(
			'base' => $pagination_base, // http://example.com/all_posts.php%_% : %_% is replaced by format (below).
			'format' => $pagination_format, // ?page=%#% : %#% is replaced by the page number.
			'total' => $pagination_total,
			'current' => $pagination_current,
			'aria_current' => 'page',
			'show_all' => false,
			'prev_next' => true,
			'prev_text' => __( '&laquo; Previous' ),
			'next_text' => __( 'Next &raquo;' ),
			'end_size' => 1,
			'mid_size' => 2,
			'type' => 'array',
			'add_args' => array(), // Array of query args to add.
			'add_fragment' => '',
			'before_page_number' => '',
			'after_page_number' => '',
		) );

        if ( is_array( $pagination ) && count( $pagination ) > 0) {
			$pagination_page = ( get_query_var('page') == 0 ) ? 1 : get_query_var('page');

			echo '<nav aria-label="Search Results Pagination">';
            echo '<ul class="pagination">';

            foreach ( $pagination as $key => $page_link ) {
                ?>
                <li class="page-item<?php if ( strpos( $page_link, 'current' ) !== false ) { echo ' active'; } ?>">
                    <?php $page_link = str_replace('page-numbers', 'page-link', $page_link); ?>
                    <?php echo $page_link; ?>
                </li>
                <?php
            }

            echo '</ul>';
            echo '</nav>';
        }

		$links = ob_get_clean();

		return apply_filters( 'display_pagination', $links );
	}
}

if ( ! function_exists( 'display_pagination' ) ) {
	function display_pagination() {
		echo get_pagination();
	}
}
