$(document).ready(function() {

    var cookie = getCookie('searchfilters'),
    p_audience = getParam('audience'),
    p_category = getParam('category'),
    p_tags = getParam('tags'),
    ref_page = document.referrer;
    referer = false;

    if(ref_page.indexOf('/services/') != -1){
        referer = true;
        document.cookie = "searchfilters=view=detailed&sort=term";
    }

    if(cookie && (p_audience == undefined) && (p_category == undefined) && (p_tags == undefined) && (referer = true)){
        var c_view = getParam('view', cookie),
        c_sort = getParam('sort', cookie),
        c_audience = getParam('audience', cookie),
        c_category = getParam('category', cookie),
        c_tags = decodeURIComponent(getParam('tags', cookie));

        if(c_audience != undefined){
            var audience = ['audience', '.' + c_audience];
        }
        if(c_category != undefined){
            var category = ['category', '.' + c_category];
        }
        if(c_tags != undefined){
            var tags = c_tags;
        }
    } else {
        if(p_audience != undefined){
            var audience = ['audience', '.' + p_audience];
        }
        if(p_category != undefined){
            var category = ['category', '.' + p_category];
        }
        if(p_tags != undefined){
            var tags = p_tags;
        }
    }

    var qsRegex;
    var buttonFilter;
    var filters = {};
    var view = c_view ? c_view : $('.button-toggle .switch-field[data-action="view"].active').attr('data-value');
    var sort = c_sort ? c_sort : $('.button-toggle .switch-field[data-action="sort"].active').attr('data-value');
    var headers = $('.service-header');

    var $services = $('#services').isotope({
        itemSelector: '.service-item',
        layoutMode: 'fitRows',
        transitionDuration: 0,
        getSortData: {
            alpha: '[data-alpha-sort]',
        },
        filter: function() {
            var $this = $(this);
            var buttonResult = buttonFilter ? $this.is( buttonFilter ) : true;
            return buttonResult;
      }
    });

    var isoData = $services.data('isotope');

    var totalCount = ($('.service-item').length - $('.service-header').length);
    $('.service-header').hide();
    $services.on('arrangeComplete', updateView());

    var total = ($('.service-item').length - $('.service-header').length);

    if(c_view){
        $('.button-toggle .switch-field[data-action="view"].active').removeClass('active');
        let el = $('.button-toggle .switch-field[data-value="'+c_view+'"]');
        el.addClass('active');
        switch_field(el);
    }
    if(c_sort){
        $('.button-toggle .switch-field[data-action="sort"].active').removeClass('active');
        let el = $('.button-toggle .switch-field[data-value="'+c_sort+'"]');
        el.addClass('active');
        switch_field(el);
        if(c_sort == 'alpha'){
            $('#alpha-nav').addClass('display-none');
        }
    }

    if(audience || category){
        if(audience && category){
            var el_audience = $('.filter-services[data-filter="'+audience[1]+'"]');
            var audience_label = el_audience.attr('data-label');
            el_audience.addClass('active');

            var el_cat = $('.filter-services[data-filter="'+category[1]+'"]');
            var cat_label = el_cat.attr('data-label');
            el_cat.addClass('active');

            filters[ audience[0] ] = {group:  audience[0] , filter: audience[1], label: audience_label};
            filters[ category[0] ] = {group:  category[0] , filter: category[1], label: cat_label};
        } else {
            var filter = audience ? audience : category;
            var el = $('.filter-services[data-filter="'+filter[1]+'"]');
            var label = el.attr('data-label');
            el.addClass('active');

            filters[ filter[0] ] = {group:  filter[0] , filter: filter[1], label: label};
        }
        var filterValue = concatValues( filters );
        $services.isotope({ filter: filterValue });
        $services.on('arrangeComplete', updateView());
    } else if(tags !== 'undefined' && tags != undefined){
        $('#search-results').removeClass('d-none');
        $('#search-query').html('"'+tags+'"');
        $('#quicksearch').val(tags);
        $services.isotope({filter: function() {
            var query = $(this).find('.searchable').text();
            return query.match(new RegExp(tags, 'gi'));
        }});
        updateView(tags);
    }

    var $quicksearch = $('#quicksearch').keyup( debounce( function() {
        // remove all applied filters
        $('.filter-services.active').removeClass('active');
        filters = {};
        var filterValue = concatValues( filters );
        $services.isotope({ filter: filterValue });

        let search = $quicksearch.val();
        if(search != '' && search != 'undefined'){
            $('#search-results').removeClass('d-none');
            $('#search-query').html("'"+search+"'");
            $('#alpha-nav').addClass('display-none');
        } else {
            $('#search-results').addClass('d-none');
            $('#alpha-nav').removeClass('display-none');
        }
        $services.isotope({filter: function() {
            // search only 'searchable' fields
            var query = $(this).find('.searchable').text();
            return query.match(new RegExp(search, 'gi'));
        }});
        updateView(search);
    }) );

    $(document).on( 'click', '.filter-services', function() {
        $('#quicksearch').val('');
        var group = $(this).parent().attr('data-filter-group');
        if($(this).hasClass('active')){
            $(this).removeClass('active');
            delete filters[group];
            var filterValue = concatValues( filters );
            $services.isotope({ filter: filterValue });
        } else {
            $('.filter-group[data-filter-group='+group+'] .filter-services.active').removeClass('active');
            $(this).addClass('active');

            var filter = $(this).attr('data-filter'),
            label = $(this).attr('data-label');

            filters[ group ] = {group:  group , filter: filter, label: label};

            var filterValue = concatValues( filters );
            $services.isotope({ filter: filterValue });
        }
        $services.on('arrangeComplete', updateView());
    });

    $('.switch-field').click(function(e){
        switch_field($(this));
    });

    function switch_field(el){
        var parent = el.parent();
        $('button', parent).removeClass('active');
        el.addClass('active');

        var action = el.attr('data-action');
        var value = el.attr('data-value');

        if(action == 'sort'){
            if(value == 'alpha'){
                $('#alpha-nav').removeClass('d-none');
            } else {
                $('#alpha-nav').addClass('d-none');
            }
        }

        if(action == 'view'){
            view = value;
            if(value == 'detailed'){
                $('.service-details').show();
                $services.isotope('layout');
            } else {
                $('.service-details').hide();
                $services.isotope('layout');
            }
        } else {
            sort = value;
            if(value == 'term'){
                $services.isotope({sortBy: 'original-order'});
            } else {
                $services.isotope({sortBy: 'alpha'});
            }
            $services.on('arrangeComplete', updateView());
        }
    }

    function updateView(query){
        updateHeaders(query);
        updateFilterCount();
        $services.isotope('layout');
    }

    function updateHeaders(query) {
        var display = [],
        terms = [],
        filtered = isoData.filteredItems;
        for (var i = 0, l = filtered.length; i < l; i++) {
            let term = sort == 'term' ? filtered[i].element.dataset.term : filtered[i].element.dataset.alpha;
            let pos = $.inArray(term, terms);

            if(typeof term != 'undefined'){
                if(pos == -1){
                    terms.push(term);
                }
            }
        };
        var services = $('.service-header');

        if(!query || query == ''){
            $('.alpha-nav').addClass('disabled');
            $('.alpha-nav').attr('href', '');
            $services.isotope('hideItemElements', services);
            for (var i = 0, l = terms.length; i < l; i++) {
                if(sort == 'alpha'){
                    $('.alpha-'+terms[i]).removeClass('disabled');
                    $('.alpha-'+terms[i]).attr('href', '#alpha-'+terms[i]);
                }
                let service = $('.service-header[data-type="'+terms[i]+'"]');
                display.push(service[0]);
            };
            $services.isotope('revealItemElements', display);
        }
        $services.isotope('layout');
    }

    function updateFilterCount() {
        var filtered = isoData.filteredItems;
        var headers = 0;
        for (var i = 0, l = filtered.length; i < l; i++) {
            let classes = filtered[i].element.classList;
            // if (Object.values(classes).indexOf('service-header') > -1) {
            //     headers++;
            // }
            if (classes[1] == 'service-header') {
                headers++;
            }
        };
        var displayed = filtered.length - headers;
        $('.counter-displayed').text(displayed);

        $('.counter-total').text(totalCount);
        if(displayed == 0){
            $('#services .none-found').remove();
            $('#services').append('<p class="none-found">No services found. Please refine your search.</p>');
        } else {
            $('#services .none-found').remove();
        }
    }

    $('.service .link.searchable').click(function(e) {
        e.preventDefault();
        let href = $(this).attr('href');
        let cookie = getCookie('searchfilters');
        let search = encodeURIComponent($('#quicksearch').val());
        let audience = $('#audiences .filter-services.active').attr('data-filter');
        let category = $('#categories .filter-services.active').attr('data-filter');

        let cookiedata = 'view='+view+'&sort='+sort;
        if(search != ''){
            cookiedata += '&tags=' + search;
        }
        if(audience != undefined){
            cookiedata += '&audience=' + audience.substr(1);
        }
        if(category != undefined){
            cookiedata += '&category=' + category.substr(1);
        }
        document.cookie = "searchfilters="+cookiedata;

        window.location.href = href;
    });

    $(window).bind('resize load', function() {
        if ($(this).width() < 991) {
            if(p_audience != undefined){
                $('#categories').removeClass('show');
            } else if(p_category != undefined){
                $('#audiences').removeClass('show');
            } else {
                $('aside .collapse').removeClass('show');
            }
        } else {
            if(p_audience != undefined){
                $('#categories').addClass('show');
            } else if(p_category != undefined){
                $('#audiences').addClass('show');
            } else {
                $('aside .collapse').addClass('show');
            }
        }
    });
});

function getCookie(name) {
  var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
  if (match){
      return match[2];
  }
}

function debounce( fn, threshold ) {
  var timeout;
  threshold = threshold || 100;
  return function debounced() {
    clearTimeout( timeout );
    var args = arguments;
    var _this = this;
    function delayed() {
      fn.apply( _this, args );
    }
    timeout = setTimeout( delayed, threshold );
  };
}

function concatValues( obj ) {
    var value = '';
    for ( var prop in obj ) {
        value += obj[prop].filter;
    }
    return value;
}

function getParam(sParam, string) {
    if(!string){
        var sPageURL = decodeURIComponent(window.location.search.substring(1));
    } else {
        var sPageURL = string;
    }
        var sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};
