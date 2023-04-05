<?php

/**
 * Makes AD call to retrieve content for given pirateId
 */
add_action( 'wp_ajax_block_profile', 'block_profile' );
function block_profile( $pirateID = false ) {
    $isAjax = $pirateID ? false : true;
    $pirateID = $pirateID ? $pirateID : sanitize_text_field( $_GET['pirateID'] );

    /**
     * Base case
     */
    if( !$pirateID ) wp_send_json_error( 'No Pirate ID entered');

    $user = new \Ldap\Ad_User($pirateID);

    if( !$user->is_valid() ) wp_send_json_error( 'Invalid Pirate ID');
    $data = [
        'name'          =>  $user->get_user()->getFirstName() . ' ' . $user->get_user()->getLastName(),
        'title'         =>  $user->get_user()->getTitle(),
        'email'         =>  $user->get_user()->getEmail(),
        'phone'         =>  $user->get_user()->getTelephoneNumber(),
        'department'    =>  $user->get_user()->getDepartment(),
        'office'        =>  $user->get_user()->getPhysicalDeliveryOfficeName(),
        'mailstop'      =>  $user->get_user()->getCompany()
    ];

    /**
     * Return data object
     */
    if( !$isAjax ) return $data;

    wp_send_json_success( $data );
};