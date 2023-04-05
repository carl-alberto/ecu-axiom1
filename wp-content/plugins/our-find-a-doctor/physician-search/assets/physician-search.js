$( document ).ready( function() {
    $( '#specialty' ).on( 'change', function() {
        $( '#specialty-form' ).submit();
    });
} );