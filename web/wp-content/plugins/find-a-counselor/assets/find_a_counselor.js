$(document).ready(function () {
    $('#ecu_find_a_counselor .btn').click(function () {
        var name = $(this).data('name'),
        $search = $('#counselor_search');

        $search.removeClass(name);

        if(name === 'nc_student'){
            var group = $(this).data('group')
            inverse = group === 'counselor_county_group' ? 'counselor_state_group' : 'counselor_county_group';

            $('#' + group).removeClass('d-none');
            $('#' + group.replace('_group', '')).prop('disabled', false);

            $('#' + inverse).addClass('d-none');
            $('#' + inverse.replace('_group', '')).prop('disabled', true);
        }

        if( !$search.hasClass('student_status') && !$search.hasClass('nc_student') ) $search.prop('disabled', false);
    });
});