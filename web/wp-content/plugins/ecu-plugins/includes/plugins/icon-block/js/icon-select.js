

 /**
  * For fresh widgets dropped on the sidebar
  * https://wordpress.stackexchange.com/a/37707/12615
  */
 jQuery( document ).ajaxComplete( function( event, XMLHttpRequest, ajaxOptions )
 {
    if(ajaxOptions.data !== undefined){
       // determine which ajax request is this (we're after "save-widget")
       var request = {}, pairs = ajaxOptions.data.split('&'), i, split, widget;
       for( i in pairs )
       {
           split = pairs[i].split( '=' );
           request[decodeURIComponent( split[0] )] = decodeURIComponent( split[1] );
       }
       // only proceed if this was a widget-save request
       if( request.action && ( request.action === 'save-widget' ) )
       {
           // locate the widget block
           widget = jQuery('input.widget-id[value="' + request['widget-id'] + '"]').parents('.widget');

           // trigger manual save, if this was the save request
           // and if we didn't get the form html response (the wp bug)
           if( !XMLHttpRequest.responseText )
               wpWidgets.save(widget, 0, 1, 0);
       }
     }

 });