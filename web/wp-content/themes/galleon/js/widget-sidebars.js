(function() {
    const sidebars = document.querySelectorAll('div.widgets-sortables')

    let isSidebar = false
    let n = 0
    let post_id = 0

    for (let i = 0; i < sidebars.length; i++) {

        if('default_sidebar' === sidebars[i].id) {
            isSidebar = true
            continue
        }

        if(isSidebar) {
            n =  sidebars[i].id.lastIndexOf('-');
            post_id =  sidebars[i].id.substring(n + 1);

            var elem = document.createElement('div');
            elem.innerHTML = '  <form action="' + _wp_.admin + '" method="post">     <input type="hidden" name="action" value="widget_crud">    <input type="hidden" name="method" value="delete">     <input type="hidden" name="post_id" value="' + post_id + '">     <button class="btn btn-primary" type="submit">Delete</button>  </form>'
            elem.style.cssText = 'margin-bottom:15px;text-align: right;';
            document.querySelector('div#' + sidebars[i].id).appendChild(elem);
        }
    }
})()