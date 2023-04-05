$(document).ready(function() {
    $('.view.entry').hide();

    $('.change-view').click(function(){
        var viewID = $(this).attr('data-view');
        $('.view').hide();
        $('.view[data-view="'+viewID+'"]').show();
        $("html, body").animate({ scrollTop: 0 }, "slow");
    });
});
