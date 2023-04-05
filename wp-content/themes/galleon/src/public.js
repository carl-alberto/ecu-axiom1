import $ from "jquery"
import "slick-slider/slick/slick.min.js"

import './scss/public.scss'

// Bootstrap JS bundle
import "bootstrap/dist/js/bootstrap.bundle"

var slideCount = $("#hero .slide-wrap").length > 1 ? true : false,
    options = {
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
    };
if (slideCount) options.dots = true;

$("#hero").slick(options);

if (slideCount) {
    $(".slick-dots").append(
        '<li class="controls"> \
            <button type="button" aria-controls="hero"> \
                <span class="fa fa-pause" aria-hidden="true"></span> \
            </button> \
        </li>'
    );
}

$(".slick-dots .controls button").on("click", function() {
    var icon = $("span", this),
        playIcon = $(icon).hasClass("fa-play");
    if (playIcon) {
        icon.removeClass("fa-play").addClass("fa-pause");
        $("#hero").slick("slickPlay");
    } else {
        icon.removeClass("fa-pause").addClass("fa-play");
        $("#hero").slick("slickPause");
    }
});

