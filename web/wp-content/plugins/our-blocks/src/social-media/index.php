<?php defined( 'ABSPATH' ) || exit;

/**
 * Retrieves registers social media organizations for given org
 * 
 * @param id id of the specified org
 * 
 * @return array registered social media sites for org
 */
add_action( 'wp_ajax_block_social_media_data', 'block_social_media_data' );
function block_social_media_data( $id = false ){
    $isAjax = $id ? false : true;
    
    $id = $id ? absint( $id ) : absint( $_GET['id'] );

    $query = Database\Homepage::query("
        SELECT 
            homepage_connect_organizations_sites.url, 
            homepage_connect_organizations.title, 
            social_media_sites.name
        FROM 
            homepage_tools.homepage_connect_organizations_sites 
        RIGHT OUTER JOIN social_media_sites 
            ON homepage_connect_organizations_sites.site_id = social_media_sites.id 
        RIGHT OUTER JOIN homepage_connect_organizations 
            ON homepage_connect_organizations_sites.org_id = homepage_connect_organizations.id 
        WHERE homepage_connect_organizations.id = '{$id}' 
        ORDER BY sort_order;
    ");
    
    if( !$isAjax ) return $query;

    wp_send_json_success( $query );
}

/**
 * Retrieves all the options for registers social media organizations
 * @return array key => value pairs of organizations and their IDs
 */
add_action( 'wp_ajax_block_social_media_orgs', 'block_social_media_orgs' );
function block_social_media_orgs() {

    $results = Database\Homepage::query("
        SELECT id, title 
        FROM homepage_tools.homepage_connect_organizations
    ");
    if( $results ){
        $options = [[
            'label' => 'Select an Organization',
            'value' => ''
        ]];
        foreach( $results as $org ) {
            $options[] = [
                'label' => $org->title,
                'value' => $org->id
            ];
        }
        wp_send_json_success( $options );
    } else {
        wp_send_json_error();
    }
    
}