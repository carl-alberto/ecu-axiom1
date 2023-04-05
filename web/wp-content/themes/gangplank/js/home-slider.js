var pausedVideos = [];
$(document).ready(function () {
  var nav = $('.home-hero .slide').length > 1 ? true : false;
  var options = {
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
    autoplay: true,
    autoplaySpeed: 10000,
    speed: 500,
    adaptiveHeight: true,
    accessibility: false
    //dots: true,
  };
  if (nav) { options.dots = true; }

  $('.home-hero').on('init', function (event, slick) {

    if ($('#' + slick.$slides[0].id).has('.caption').length) {
      $('#' + slick.$slides[0].id).find('.caption').addClass('visible');
    }
  });

  $('.home-hero').slick(options);
  if ($('.home-hero video').parent().hasClass('slick-active')) {
    if ($('.slick-slide').length > 1) {
      $('#slick-slide00 video').get(0).play();
    } else {
      $('.slick-slide video').get(0).play();
    }

  }

  if (nav) {
    $('.slick-dots').append("<li><button type='button' role='playpause' class='playpause pause' aria-controls='feature-carousel'><span class='fa fa-play' aria-hidden='true'></span></button></li>");
  }

  //Play/Pause Carousel
  $('.playpause').on('click', function () {
    if ($(this).hasClass('play')) {
      $(this).removeClass('play').addClass('pause');
      $('#feature-carousel').slick('slickPlay');
    } else {
      $(this).removeClass('pause').addClass('play');
      $('#feature-carousel').slick('slickPause');
    }
  });

  $('.video-playpause').click(function () {
    var child = $(this).children(".fa");
    if (child.hasClass('fa-pause')) {
      //Pausing
      child.removeClass('fa-pause').addClass('fa-play');
      $('#' + $(this).data('videoid')).get(0).pause();
      pausedVideos.push($(this).data('videoid'));
      console.log($(this).data('videoid'));
    } else {
      //Playing
      child.removeClass('fa-play').addClass('fa-pause');
      $('#' + $(this).data('videoid')).get(0).play();
      pausedVideos.splice(pausedVideos.indexOf($(this).data('videoid')), 1);
    }
  });

  $('.home-hero').on('afterChange', function (event, slick, currentSlide, nextSlide) {
    if ($('#' + slick.$slides[currentSlide].id).has('video').length) {
      if (!(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))) {
        //Dont play video if user already paused it.
        if (pausedVideos.indexOf($('#' + slick.$slides[currentSlide].id).find('video').get(0).id) == -1) {
          $('#' + slick.$slides[currentSlide].id).find('video').get(0).play();
        }
      }
    }
    if ($('#' + slick.$slides[currentSlide].id).has('.caption').length) {
      $('#' + slick.$slides[currentSlide].id).find('.caption').addClass('visible')
    }
  });

  $('.home-hero').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
    if ($('#' + slick.$slides[currentSlide].id).has('video').length) {
      $('#' + slick.$slides[currentSlide].id).find('video').get(0).pause();
    }
    if ($('#' + slick.$slides[currentSlide].id).has('.caption').length) {
      $('#' + slick.$slides[currentSlide].id).find('.caption').removeClass('visible')
    }
  });

  $('#video-controls').click(function () {
    var controls = $('#video-controls');
    var video = $('#hero-video').get(0);
    if (video.paused) {
      controls.removeClass('fa-play').addClass('fa-pause');
      video.play();
    } else {
      controls.removeClass('fa-pause').addClass('fa-play');
      video.pause();
    }
  });
});
