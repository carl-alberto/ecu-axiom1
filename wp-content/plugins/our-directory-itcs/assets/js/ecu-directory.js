function directory_search_people(firstName, lastName, filter, nonce) {
    $('#people-results').empty();
    $('.loading-gif').show();
    jQuery.ajax({
        method: 'POST',
        url: directory_search_people_ajax.ajaxurl,
        data: {
            action: 'directory_search_people',
            firstName: firstName,
            lastName: lastName,
            filter: filter,
            nonce: nonce
        },
        success: function(response) {
            $('.loading-gif').hide();
            $('#people-results').empty();
            if (response.user_found) {
                if (response.message) {
                    $('#people-results').append('<div class="alert alert-warning" role="alert">' + response.message + '</div>');
                }
                $('#people-results').append(response.users);
            }  else {
                $('#people-results').append('<div class="alert alert-danger" role="alert">' + response.message + '</div>');
            }
        },
        error: function(xhr, status, error) {
            $('.loading-gif').hide();
            var errorMessage = xhr.status + ': ' + xhr.statusText
            alert('Error - ' + errorMessage);
        }
    });
}

function directory_search_department(dept, nonce) {
    $('#department-results').empty();
    $('.loading-gif').show();
    jQuery.ajax({
        method: 'POST',
        url: directory_search_department_ajax.ajaxurl,
        data: {
            action: 'directory_search_department',
            dept: dept,
            nonce: nonce
        },
        success: function(response) {
            $('.loading-gif').hide();
            $('#department-results').empty();
            if (response.dept_found) {
                if (response.message) {
                    $('#department-results').append('<div class="alert alert-warning" role="alert">' + response.message + '</div>');
                }
                $('#department-results').append(response.depts);
            }  else {
                $('#department-results').append('<div class="alert alert-danger" role="alert">' + response.message + '</div>');
            }
        },
       error: function(xhr, status, error) {
            $('.loading-gif').hide();
            var errorMessage = xhr.status + ': ' + xhr.statusText
            alert('Error - ' + errorMessage);
        }
    });
}

function directory_browse_depts(letter, nonce) {
    $('#department-results').empty();
    $('.loading-gif').show();
    jQuery.ajax({
        method: 'POST',
        url: directory_search_people_ajax.ajaxurl,
        data: {
            action: 'directory_browse_depts',
            letter: letter,
            nonce: nonce
        },
        success: function(response) {
            $('.loading-gif').hide();
            $('#department-results').empty();
            $('#department-results').append(response.depts);
        },
       error: function(xhr, status, error) {
            $('.loading-gif').hide();
            var errorMessage = xhr.status + ': ' + xhr.statusText
            alert('Error - ' + errorMessage);
        }
    });
}

function directory_reverse_search(phoneNumber, nonce) {
    $('#reverse-results').empty();
    $('.loading-gif').show();
    jQuery.ajax({
        method: 'POST',
        url: directory_reverse_search_ajax.ajaxurl,
        data: {
            action: 'directory_reverse_search',
            phoneNumber: phoneNumber,
            nonce: nonce
        },
        success: function(response) {
            $('.loading-gif').hide();
            $('#reverse-results').empty();
            if (response.message) {
               $('#reverse-results').append('<div class="alert alert-danger" role="alert">' + response.message + '</div>');
            }
            if (response.users) {
                $('#reverse-results').append(response.users);
            }
            if (response.depts) {
                if (response.depts && response.users) {
                    output = '<br />' + response.depts;
                }   else   {
                    output = response.depts;
                }
                $('#reverse-results').append(output);
            }
        },
       error: function(xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText
            alert('Error - ' + errorMessage);
        }
    });
}

function directory_search_pirate_id(pirate_id) {
    jQuery.ajax({
        method: 'POST',
        url: directory_search_pirate_id_ajax.ajaxurl,
        data: {
            action: 'directory_search_pirate_id',
            pirate_id: pirate_id,
        },
        success: function(response) {
            if (response.user_found) {
                $('#firstName').val(response.first_name);
                $('#lastName').val(response.last_name);
                $("#searchPeople").click();
            }  else {
                $('#people-results').append('<div class="alert alert-danger" role="alert">' + response.message + '</div>');
            }
        },
        error: function(xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText
            alert('Error - ' + errorMessage);
        }
    });
}

function getQueryParams(qs) {
    qs = qs.split("+").join(" ");
    var params = {},
        tokens,
        re = /[?&]?([^=]+)=([^&]*)/g;

    while (tokens = re.exec(qs)) {
        params[decodeURIComponent(tokens[1])]
            = decodeURIComponent(tokens[2]);
    }

    return params;
}

/*
 * Register AJAX call events
 *
 */
jQuery(document).ready(function ($) {
    $('#firstName').focus();
    $('.loading-gif').hide();

    //var person = this.href.substring(this.href.lastIndexOf('/') + 1);
    var $_GET = getQueryParams(document.location.search);

    if ($_GET['person']) {
        directory_search_pirate_id($_GET['person']);
    }

/*    if (person != 'directory') {
         directory_search_pirate_id(person);
    }*/

    $("#searchPeople").on('click', function() {
        directory_search_people($('#firstName').val(),$('#lastName').val(),$("input[name='filter']:checked").val(),$('#nonce').val());
        return false;
    });

    $("#searchDepartment").on('click', function() {
        directory_search_department($('#departmentTitle').val(),$('#nonce').val());
        return false;
    });
    $("#reverseSearch").on('click', function() {
        directory_reverse_search($('#phoneNumber').val(),$('#nonce').val());
        return false;
    });

    $("#browseDepts li").on('click', function() {
        directory_browse_depts($(this).attr('data-value'),$('#nonce').val());
    });


    $('#firstName, #lastName').keypress(function (event) {
        if (event.keyCode == '13') { //jquery normalizes the keycode
            event.preventDefault(); //avoids default action
            $('#searchPeople').click();
        }
    });
    $('#departmentTitle').keypress(function (event) {
        if (event.keyCode == '13') { //jquery normalizes the keycode
            event.preventDefault(); //avoids default action
            $('#searchDepartment').click();
        }
    });
    $('#phoneNumber').keypress(function (event) {
        if (event.keyCode == '13') { //jquery normalizes the keycode
            event.preventDefault(); //avoids default action
            $('#reverseSearch').click();
        }
    });
    $('#department-tab').click(function() {
        $('#reverse-results').empty();
        $('#people-results').empty();
        $('#firstName').val('');
        $('#lastName').val('');
        $('#phoneNumber').val('');
        setTimeout(function() {
            $('#departmentTitle').focus();
        }, 200);
    });
    $('#people-tab').click(function() {
        $('#reverse-results').empty();
        $('#department-results').empty();
        $('#phoneNumber').val('');
        $('#departmentTitle').val('');
        setTimeout(function() {
            $('#firstName').focus();
        }, 200);
    });
    $('#reverse-tab').click(function() {
        $('#department-results').empty();
        $('#people-results').empty();
        $('#firstName').val('');
        $('#lastName').val('');
        $('#departmentTitle').val('');
        setTimeout(function() {
            $('#phoneNumber').focus();
        }, 200);
    });
});

