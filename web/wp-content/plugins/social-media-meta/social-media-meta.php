<?php
/**
 * WordPress plugin "ECU Social Media Meta" main file, responsible for initiating the plugin
 *
 * @package ECU Social Media Meta
 * @author atwebdev
 * @version 1
 */

/*
Plugin Name: ECU Social Media Meta
Plugin URI: https://www.ecu.edu/
Description: Inject meta tags for LinkedIn, Twitter and Facebook to the head of the html document
Version: 1
Author: atwebdev
*/


// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );


//add action to
add_action('wp_head', 'social_media_meta');
function social_media_meta() {
	//get the post
	global $post;
	$permalink = get_permalink($post->ID);
	$blogname = get_bloginfo('name');

	if (function_exists("get_field")){
		if (get_field('banner_type') !== 'none') {
			switch(get_field('banner_type')){
				case 'image':
				default:
					if($banner_image = get_field('banner_image', $post->ID)){
						$image = $banner_image;
					}
					break;
				case 'posts':
					$cat = get_field('category');
					$args = array(
						'post_status' => 'publish',
						'posts_per_page' => 1,
						'category__in' => $cat
					);
					$slides = get_posts($args);
					if ( $banner = get_field('banner_image', $slides[0]->ID)){
						$image = $banner;
					}
					break;
				case 'video':
					$video = get_field('video');
					break;
				case 'slideshow':
					$slides = get_field('slideshow');
					$image = $slides[0]['image'];						
					break;
			}
		} else {
			return;
		}
	} else {
		return;
	}
	
	if(!isset($image) && !isset($video)) {
		return;
	}

	//facebook/LinkedIn
	$meta =
	'<meta property="og:title" content="'.$post->post_title.'">
	<meta property="og:description" content="'.$post->post_excerpt.'">
	<meta property="og:url" content="'.$permalink.'">
	<meta property="og:site_name" content="'.$blogname.'">';
	
	if (is_null($video)){
		$meta .= '<meta property="og:image" content="' . $image['url'] . '">';
	} else {
		$meta .= '<meta property="og:video" content="' . $video . '">';
	}

	//twitter
	$meta .= '
	<meta name="twitter:title" content="'.$post->post_title.'">
	<meta name="twitter:description" content="'.$post->post_excerpt.'">';
	
	if (is_null($video)){
		$meta .= '<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:image" content="' . $image['url'] . '">';
	} else {
		$meta .= '<meta name="twitter:card" content="player">
		<meta property="twitter:player" content="' . $video . '">
		<meta name="twitter:player:width" content="480" />
		<meta name="twitter:player:height" content="480" />
		<meta name="twitter:image" content="https://cdn.ecu.edu/images/logo/ECU_lockup_primary_White.svg">'; // Image is required if video is select. I set it to ECU logo from homepage.
	}
	echo $meta;

	return;
}
