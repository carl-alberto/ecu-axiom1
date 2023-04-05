$(document).ready(function() {
    $('.internal-link').click(function (e) {
      e.preventDefault();
      var offset = $('a[name=' + $(this).attr('href').substr(1) + ']').offset();
      $('html, body').animate({ scrollTop: offset.top - $('#main-nav').outerHeight() - 15 }, 'fast');
    });

    $('.alpha-link').click(function (e) {
      e.preventDefault();
      if($(this).attr('href')){
          var link = $('a[name=' + $(this).attr('href').substr(1) + ']');
          var offset = link.offset();
          if($(this).hasClass('btt')){
              $('html, body').animate({ scrollTop: offset.top - link.outerHeight() - 133 }, 'fast');
          } else {
              $('html, body').animate({ scrollTop: offset.top - link.outerHeight() - 100 }, 'fast');
          }
      }

    });
});
