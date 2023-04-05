<?php
add_action( 'admin_init', 'add_cpt_caps');
function add_cpt_caps() {
    $caps = array(
        'delete_others_services',
        'delete_private_services',
        'delete_published_services',
        'delete_services',
        'edit_others_services',
        'edit_private_services',
        'edit_published_services',
        'edit_services',
        'publish_services',
        'read_private_services',
        'delete_others_tutorials',
        'delete_private_tutorials',
        'delete_published_tutorials',
        'delete_tutorials',
        'edit_others_tutorials',
        'edit_private_tutorials',
        'edit_published_tutorials',
        'edit_tutorials',
        'publish_tutorials',
        'read_private_tutorials',
        'delete_others_faqs',
        'delete_private_faqs',
        'delete_published_faqs',
        'delete_faqs',
        'edit_others_faqs',
        'edit_private_faqs',
        'edit_published_faqs',
        'edit_faqs',
        'publish_faqs',
        'read_private_faqs',
        'delete_others_projects',
        'delete_private_projects',
        'delete_published_projects',
        'delete_projects',
        'edit_others_projects',
        'edit_private_projects',
        'edit_published_projects',
        'edit_projects',
        'publish_projects',
        'read_private_projects',
    );
    $itcs = get_role('itcs_support');
    $owner = get_role('blog_owner');
    foreach($caps as $cap){
        $itcs->add_cap($cap);
        $owner->add_cap($cap);
    }
}
