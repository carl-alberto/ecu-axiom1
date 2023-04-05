//jQuery is required to run this code
$( document ).ready(function() {
    //Prevent Clicking of dropdown to scroll to top of page.
    $('#btnAudienceDropdown').click(function(e){
        e.preventDefault();
    });

    //Show accessibility links when tabbing
    $('.accessibility-link').focus(function(){
        $('#ecu-skip-links').addClass('active');
    });
    $('.accessibility-link').blur(function(){
        $('#ecu-skip-links').removeClass('active');
    });

    //Show Resources Tray
    $('#btn-resources').click(function(){
        if ($('#nav-quicklinks').hasClass('menu-open')){
            $('#nav-quicklinks').removeClass('menu-open');
            $(this).html('<span class="fa fa-search" aria-hidden="true"></span> | <span class="fa fa-navicon" aria-hidden="true"></span>');
            $('#nav-quicklinks').stop().slideUp();
            $('#search-input').blur();
        } else {
            $('#nav-quicklinks').addClass('menu-open');
            $(this).html('<span class="fa fa-search" aria-hidden="true"></span> | <span class="fa fa-close" aria-hidden="true"></span>');
            $('#nav-quicklinks').stop().slideDown();
            $('#search-input').focus();
        }
    });

});

function isMobile(){
    return ( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent));
}
