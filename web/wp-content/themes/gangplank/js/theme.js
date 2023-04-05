$(document).ready(function() {
    if(window.location.hash){
        var jump = window.location.hash.replace("#", ""),
        target = $("[id=" + jump + "]");
        if (target[0]) {
            var header = $("#main-nav").height();
            // requires delay before execution
            setTimeout(function () {
                window.scrollTo(0, getOffsetTop(target[0]) - header);
            }, 500);
        } else {
            console.log('Missing anchor tag for #' + jump);
        }
    }

    $("#skip-to-content").click(function(e) {
        e.preventDefault();
        var offset = $("main").offset();
        $("#ecu-skip-links").removeClass("active");
        $("html, body").animate(
            { scrollTop: offset.top - $("#main-nav").outerHeight() - 15 },
            "fast"
        );
    });

    $(".accessibility-link").focus(function() {
        $("#ecu-skip-links").addClass("active");
    });
    $(".accessibility-link").blur(function() {
        $("#ecu-skip-links").removeClass("active");
    });

    // Prevent clicking of "I am..." or search/menu button to scroll to top of page and from adding '/#/' to the URL
    $("#btnAudienceDropdown, #btn-resources").click(function(e) {
        e.preventDefault();
    });

    $(".social-link span").attr("data-menu", "resources");

    $("#btnAudienceDropdown span").attr("id", "iamchevon");

    //Show/Hide "I am" Menu
    $("html").click(function(e) {
        if (
            $(e.target).attr("id") == "btnAudienceDropdown" ||
            $(e.target).attr("id") == "iamchevon"
        ) {
            if ($("#audience-dropdown").hasClass("menu-open")) {
                $("#audience-dropdown").removeClass("menu-open");
                $("#audience-dropdown").hide();
                $("#btnAudienceDropdown").html(
                    'I am...  <span id="iamchevon" class="fa fa-chevron-down" aria-hidden="true"></span>'
                );
            } else {
                $("#audience-dropdown").addClass("menu-open");
                $("#audience-dropdown").show();
                $("#btnAudienceDropdown").html(
                    'I am...  <span id="iamchevon" class="fa fa-chevron-up" aria-hidden="true"></span>'
                );
            }
        } else {
            if ($("#audience-dropdown").hasClass("menu-open")) {
                $("#audience-dropdown").removeClass("menu-open");
                $("#audience-dropdown").hide();
                $("#btnAudienceDropdown").html(
                    'I am...  <span id="iamchevon" class="fa fa-chevron-down" aria-hidden="true"></span>'
                );
            }
        }
    });

    //If something gets focus thats not in a menu, close the menu
    $("*").focus(function() {
        var menu = $(document.activeElement).data("menu");
        if (menu != "audience") {
            if ($("#audience-dropdown").hasClass("menu-open")) {
                $("#audience-dropdown").removeClass("menu-open");
                $("#audience-dropdown").hide();
            }
        }
        if (menu != "resources") {
            if ($("#resources-menu").hasClass("show")) {
                $("#resource-toggle").removeClass("activated");
                $("#resource-icon")
                    .addClass("fa-navicon")
                    .removeClass("fa-close");
                $("#resources-menu").collapse("hide");
            }
        }
    });

    $("body").click(function() {
        if ($("#audience-dropdown").hasClass("active")) {
            $("#audience-dropdown").removeClass("active");
        }
    });

    $("a.jump-link").click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var hash = $(this)
            .prop("hash")
            .replace("#", "");
        var target = $("[id=" + hash + "]");
        if (target[0]) {
            var header = $("#main-nav").height();
            window.scrollTo(0, getOffsetTop(target[0]) - header);
        } else {
            window.location.href = url;
        }
    });




    if ($("#main-nav").hasClass("appleOS")) {
        var height = $("#main-nav").height();
        $("header").css("padding-top", height);
    }

    $("#resources-menu").on("show.bs.collapse", function() {
        $("#resource-toggle").addClass("activated");
        $("#resource-icon")
            .addClass("fa-close")
            .removeClass("fa-navicon");
        setTimeout(function() {
            $("#search-input").focus();
        }, 500);
    });

    $("#resources-menu").on("hide.bs.collapse", function() {
        $("#resource-toggle").removeClass("activated");
        $("#resource-icon")
            .addClass("fa-navicon")
            .removeClass("fa-close");
    });

    $(".accordion .card-header button").click(function() {
        $(".accordion .card-header button").removeClass("active");
        var body = $(this)
            .parent()
            .parent()
            .parent()
            .find("div.collapse");
        if (!body.hasClass("show")) {
            $(this).addClass("active");
        }
    });

    //Back to top
    $(window).scroll(function() {
        if ($(this).scrollTop() >= 50) {
            // If page is scrolled more than 50px
            $("#back-to-top").fadeIn(200); // Fade in the arrow
        } else {
            $("#back-to-top").fadeOut(200); // Else fade out the arrow
        }
    });
    $("#back-to-top").click(function() {
        // When arrow is clicked
        $("body,html").animate(
            {
                scrollTop: 0 // Scroll to top of body
            },
            500
        );
    });

    $(".wp-video").attr("tabindex", "0");

    $("#specialty").change(function() {
        $("#specialty-form").submit();
    });

    function getOffsetTop(elem) {
        var offsetTop = 0;
        do {
            if (!isNaN(elem.offsetTop)) {
                offsetTop += elem.offsetTop;
            }
        } while ((elem = elem.offsetParent));
        return offsetTop;
    }

    function getUrlVars() {
        var vars = {};
        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
            vars[key] = value;
        });
        return vars;
    }
});
