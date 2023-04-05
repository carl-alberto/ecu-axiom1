<?php
	namespace Ecu_Plugins;

	class Feed extends Ecu_Database
	{
		// Type Options
		const BIG_CAROUSEL = 'big_carousel';
		const VERTICAL_LIST = 'vertical_list';
		const HORIZONTAL_LIST = 'horizontal_list';

		// Google Anayltics Data
		private $ga_category;
		private $ga_action;

		private $div_id;

		// Data
		private $title;
		private $feed;
		private $display;
		private $stories;
		private $story_header;
		private $center_text;
		private $center_list;
		private $show_image_video;
		private $image_location;

		/*
		 *	Include assets and activate widget
		 */
		public function init($options) {

			if (empty($options))
				return;

			$feed_id = (int) $options['feed_id'];

			$this->display = sanitize_text_field($options['list_display']);

			$this->title = sanitize_text_field($options['title']);

			$this->center_text = filter_var($options['center_text'], FILTER_VALIDATE_BOOLEAN);

			$this->center_list = filter_var($options['center_list'], FILTER_VALIDATE_BOOLEAN);

			$this->show_image_video = filter_var($options['show_image_video'], FILTER_VALIDATE_BOOLEAN);

			$this->image_location = sanitize_text_field($options['image_location']);

			if(empty($this->title)) {
				$this->story_header = 'h2';
			} else {
				$this->story_header = 'h3';
			}

			$this->feed = \Database\Homepage::query("
				SELECT *
					FROM homepage_tools.news_feeds
					WHERE news_feeds.id = ?
			", array($feed_id));

			$limit = (int) $options['show'];
			$this->stories = \Database\Homepage::query("
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
				ORDER BY news_feeds_stories.sort_order
				LIMIT ?
			", array($feed_id,$limit));

			if(!isset($this->ga_category)) {
				$this->ga_category = esc_attr('News Feed ' . $this->feed[0]->id);
			}

			if(!isset($this->ga_action)) {
				$this->ga_action = esc_attr($this->feed[0]->name);
			}

			$this->div_id = esc_attr('newsFeeds' . $this->feed[0]->id);
		}

		public function run()	{
			$mydb = $this->get_homepage_db();

			$mydb->update( 'news_feeds', array('accessed' => 'NOW()'), array('id' => $this->feed[0]->id));

			// Display the feed
			switch($this->display) {

				case self::BIG_CAROUSEL:
					return $this->printBigCarousel();
					break;

				default:
				case self::VERTICAL_LIST:
					return $this->printVerticalList();
					break;

				case self::HORIZONTAL_LIST:
					return $this->printHorizontalList();
					break;
			}

		}

		private function printHorizontalList() {
			$output = '';
			if (count($this->stories)) {

				$output .= '<div id="' . $this->div_id . '" class="ecu-news">
					<div class="ecu-news-column ';

				if ($this->center_list) {
					$output .= 'ecu-news-horizontal-list-center';
				}

				$output .= '">
						<div class="ecu-header-section">';
				if(!empty($this->title)) {
					$output .= '<h2>' . $this->title . '</h2>';
				}

				$output .=  '</div>';
				$output .= '<ul class="ecu-news-horizontal-list row">';

				foreach ($this->stories as $i => $article) {

					$output .= '<li class="ecu-news-horizontal-list col-sm-6 col-md-3">';
					$output .= $this->printHorizontalListSection($article, $i);
					$output .= '</li>';
				}

				$output .= '</ul>';
				$output .= '</div>
				</div>';
			}
			return $output;
		}

		private function printHorizontalListSection($story, $i) {

			$output = '<div class="ecu-news-item">';

			$output .= '<a class="ecu-event-tracking" data-ga-category="' . $this->ga_category . '" data-ga-action="' . $this->ga_action . '" data-ga-label="Story ' . $i . '" href="' . esc_url($story->url) . '">';

			if ($this->show_image_video) {
				if(('right' === $this->image_location) || ('left' === $this->image_location)) {
					$output .= '<div class="row">';
					if('right' === $this->image_location) {
						$output .= '<div class="col-md-3 col-md-push-6">';
					} else {
						$output .= '<div class="col-md-9 col-md-3">';
					}
				}

				$output .= '<div class="ecu-news-thumb">';

				if(!empty($story->list_video)) {
					$output .= '<video width="194px" controls';
					if (!empty($story->list_video_poster)) {
						$output .= " poster='" . esc_url(CDN_IMAGE_URL . $story->ecu_asset_dir . '/' . $story->list_video_poster) . "'";
					}
					$output .= '>
					  <source src="' . esc_url(CDN_VIDEO_URL . $story->ecu_asset_dir . '/' . $story->list_video) . '" type="video/mp4">
					  <img src="' . esc_url(CDN_IMAGE_URL . $story->ecu_asset_dir . '/' . $story->list_image) . '" alt="">
					</video>';
				} else {
					$output .= '<img src="' . esc_url(CDN_IMAGE_URL . $story->ecu_asset_dir . '/' . $story->list_image) . '" alt="">';
				}
				$output .= '</div>';
				if(('right' === $this->image_location) || ('left' === $this->image_location)) {
					$output .= '</div>';
					if('right' === $this->image_location) {
						$output .= '<div class="col-md-6 col-md-pull-3">';
					} else {
						$output .= '<div class="col-md-3 col-md-push-9">';
					}
				}
			}

			$output .= '<div class="ecu-news-info"';

			if($this->center_text) {
				$output .= ' style="text-align:center;"';
			}

			$output .= '>';

			if(!empty($story->list_video)  && $this->show_image_video) {
				$output .= '<a class="ecu-event-tracking" data-ga-category="' . $this->ga_category . '" data-ga-action="' . $this->ga_action . '" data-ga-label="Story ' . $i . '" href="' . esc_url($story->url) . '" title="' . esc_attr($story->title) . '">';
			}
			$output .= '<'.$this->story_header.'>' . esc_html($story->title) . '</'.$this->story_header.'>
				<p>' . esc_html($story->teaser) . '</p>
				<p class="read-more">Read the full story</p>';
			if(!empty($story->list_video)) {
				$output .= '</a>';
			}
			$output .= '</div>';

			if($this->show_image_video && (('right' === $this->image_location) || ('left' === $this->image_location))) {
				$output .= '</div></div>';
			}

			if(empty($story->list_video) && $this->show_image_video) {
				$output .= '</a>';
			}
			$output .= '</div>';
			return $output;
		}

		private function printBigCarousel() {

			$output = '';

			if (count($this->stories)) {

				$output .= "
					//Bootstrap carousel initialization
					!function ($) {
						$(function() {
							if($(window).width() > 500) {
						    	$('#" . $this->div_id . "').carousel({ interval: 6000 })
						    }
						});
					}(window.jQuery);

					$('#playButton').click(function() {
					    $('#" . $this->div_id . "').carousel('cycle');
					});
					$('#pauseButton').click(function() {
					    $('#" . $this->div_id . "').carousel('pause');
					});
					//End Bootstrap carousel initialization
				";

				$output .= '
					<div class="container">
						<div id="' . $this->div_id . '" class="carousel slide">

							<!-- Carousel items -->
							<div class="carousel-inner">';

				$controls = array();
				foreach ($this->stories as $i => $feature) {

					$controls[] = $feature;

					$id = esc_attr($feature->ecu_asset_dir . '-' . absint($feature->id));

					$position_class = $i . '_position';
					$indicator_class = $i . '_indicator';
					if($i==0) {
						$position_class .= ' active';
						$indicator_class	.= ' active';
					}

					$output .= '
					<div class="' . $position_class . ' item">
						<a class="ecu-event-tracking" data-ga-category="' . $this->ga_category . '" data-ga-action="' . $this->ga_action . '" data-ga-label="Carousel Slide ' . $i . '" href=" ' . esc_url($feature->url) . '" title="' . esc_attr($feature->title) .'">';
					if(!empty($feature->carousel_video)) {
						$output .= "<div class='ecu-video'>";
						$output .= '<video width="960px" id="video-' . $feature->id . '" class="hidden-phone" preload="auto" autoplay="" loop=""';
						if (!empty($feature->carousel_video_poster)) {
							$output .= " poster='" . esc_url(CDN_IMAGE_URL . $feature->ecu_asset_dir . '/' . $feature->carousel_video_poster) . "'";
						}
						$output .= '>
						  <source src="' . esc_url(CDN_VIDEO_URL . $feature->ecu_asset_dir . '/' . $feature->carousel_video) . '" type="video/mp4">
						  Your browser does not support the video tag.
						</video>';

						$output .= '<img class="visible-phone" src="' . esc_url(CDN_IMAGE_URL . $feature->ecu_asset_dir . '/' . $feature->carousel_video_poster) . '" alt="">';
						$output .= '</div>';
						$output .= "<script type='text/javascript'>
						      $(document).ready(function(){
						        var video" . $id . " = $('#video-" . $id . "').get(0),
						            cookie_id = 'carousel-video" . $id . "';

						        // Have to force autoplay because chrome safari have a bug that keeps autoplay from working when the DOM is modified after page load.
						        video" . $id . ".play();

						        // Pause button
						        $('#video-toggle" . $id . "').click(function(e){
						          btn = $(this);
						          e.preventDefault();
						          handleVideoToggle(btn);
						        });

						        function handleVideoToggle(btn){
						          //$('.hp-video-masthead').toggleClass('hide-video');

						          var expires = new Date();

						          if(btn.text() === ' Pause Video') {
						            video" . $id . ".pause();
						            expires.setTime(expires.getTime()+(365*24*60*60*1000));
						            document.cookie = cookie_id + '=1; expires=' + expires.toUTCString();
						            btn.text(' Play Video');
						            btn.removeClass('fa-pause').addClass('fa-play');
						          } else {
						            video" . $id . ".play();
						            expires.setTime(expires.getTime()-(1000));
						            document.cookie = cookie_id + '=0; expires=' + expires.toUTCString();
						            btn.text(' Pause Video');
						            btn.removeClass('fa-play').addClass('fa-pause');
						          }
						        };

						      });
						</script>";
					} else {
						$output .= '<img src="' . esc_url(CDN_IMAGE_URL . $feature->ecu_asset_dir . '/' . $feature->carousel_image) . '" alt="';
						if(!$feature->show_carousel_bar) {
							$output .= esc_attr($feature->title);
						}
						$output .= '">';
					}

					if(empty($feature->carousel_video)) {
						if($feature->show_carousel_bar) {
							$output .= '
							<div class="carousel-caption ecu-carousel-caption">
								<span class="ecu-carousel-title">' . esc_html($feature->title) . '</span>
								<p>' . esc_html($feature->teaser) . '</p>
							</div>';
						}
					} else {
						if($feature->show_carousel_bar) {
							$output .= '<div class="hidden-phone ecu-video-banner">
				           		<p class="ecu-video-banner-title">' . esc_html($feature->title) . '</p>
				            	<p class="ecu-video-banner-teaser">' . esc_html($feature->teaser) . '</p>
				         	</div>
				         	<div class="visible-phone carousel-caption ecu-carousel-caption">
								<span class="ecu-carousel-title">' . esc_html($feature->title) . '</span>
								<p>' . esc_html($feature->teaser) . '</p>
							</div>';
						}
					}
					$output .= '</a>';
					if(!empty($feature->carousel_video)) {
						$output .= '
						<div class="hidden-phone ecu-video-controls">
							<a class="fa fa-pause" href="#"" id="video-toggle' . $id . '"> Pause Video</a>
						</div>';
					}
					$output .= '</div>';
				}

				$output .= '
					</div>
					<!-- Carousel nav -->';

				$output .= '<ol class="carousel-indicators ecu-carousel-indicators">';
					foreach ($controls as $i => $feature) {
						$output .= '<li data-target="#' . $this->div_id . '" title="' . esc_attr($feature->title) . '" data-slide-to="' . $i . '" data-ga-category="' . $this->ga_category . '" data-ga-action="' . $this->ga_action . '" data-ga-label="Carousel Indicator ' . $i . '" class="ecu-event-tracking ' . $indicator_class . '"></li>';
					}
				$output .= '</ol>';

				$output .= '
				</div>
					<div class="row ecu-control-links">
						<div class="col-md-12 mobile-two ecu-play-pause">';


				$output .= '
					<a class="ecu-event-tracking" data-ga-category="' . $this->ga_category . '" data-ga-action="' . $this->ga_action . '" data-ga-label="Play Button" id="playButton"><img src="/images/ecu-play.png" class="ecu-play" alt="Start Carousel"></a>
					<a class="ecu-event-tracking" data-ga-category="' . $this->ga_category . '" data-ga-action="' . $this->ga_action . '" data-ga-label="Pause Button" id="pauseButton"><img src="/images/ecu-pause.png" alt="Pause Carousel" class="ecu-pause"></a>';


				$output .= '
					</div>
				</div>';

			}
			return $output;
		}

		private function printVerticalListSection($story, $i) {
			$output = '<div class="ecu-news-item"><div class="row">';

			if(empty($story->list_video)) {
				$output .= '<a class="ecu-event-tracking" data-ga-category="' . $this->ga_category . '" data-ga-action="' . $this->ga_action . '" data-ga-label="Story ' . $i . '" href="' . esc_url($story->url) . '" title="' . esc_html($story->title) . '">';
			}

			if ($this->show_image_video) {
				$output .= '<div class="col-md-3 mobile-one ecu-news-thumb" style="text-align:right;">';

				if(!empty($story->list_video)) {
					$output .= '<video width="194px" controls';
					if (!empty($story->list_video_poster)) {
						$output .= " poster='" . esc_url(CDN_IMAGE_URL . $story->ecu_asset_dir . '/' . $story->list_video_poster) . "'";
					}
					$output .= '>
					  <source src="' . esc_url(CDN_VIDEO_URL . $story->ecu_asset_dir . '/' . $story->list_video) . '" type="video/mp4">
					  <img src="' . esc_url(CDN_IMAGE_URL . $story->ecu_asset_dir . '/' . $story->list_image) . '" alt="">
					</video>';
				} else {
					$output .= '<img src="' . esc_url(CDN_IMAGE_URL . $story->ecu_asset_dir . '/' . $story->list_image) . '" alt="">';
				}
				$output .= '</div>';
				$output .= '<div class="col-md-9 mobile-three ecu-news-info"';
			} else{
				$output .= '<div class="col-md-12 mobile-three ecu-news-info"';
			}

			if($this->center_text) {
				$output .= ' style="text-align:center;"';
			}

			$output .= '>';

			if(!empty($story->list_video)) {
				$output .= '<a class="ecu-event-tracking" data-ga-category="' . $this->ga_category . '" data-ga-action="' . $this->ga_action . '" data-ga-label="Story ' . $i . '" href="' . esc_url($story->url) . '" title="' . esc_attr($story->title) . '">';
			}
			$output .= '<'.$this->story_header.'>' . esc_html($story->title) . '</'.$this->story_header.'>
				<p>' . esc_html($story->teaser) . '</p>
				<p class="read-more">Read the full story</p>';
			if(!empty($story->list_video)) {
				$output .= '<div class="clearfix"></div></a>';
			}
			$output .= '</div>';

			if(empty($story->list_video)) {
				$output .= '</a>';
			}
			$output .= '</div></div>';
			return $output;
		}

		private function printVerticalList() {

			$output = '';
			if (count($this->stories)) {

				$output .= '<div id="' . $this->div_id . '" class="ecu-news">
					<div class="ecu-news-column">
						<div class="ecu-header-section">';

				if(!empty($this->title)) {
					$output .= '<'.$this->story_header.'>' . $this->title . '</'.$this->story_header.'>';
				}

				$output .= '</div>';

				foreach ($this->stories as $i => $article) {
					$output .= $this->printVerticalListSection($article, $i);
				}

				$output .= '</div>
				</div>';
			}
			return $output;
		}

	}

?>
