<?php defined( 'ABSPATH' ) || exit;

/**
 * Register dynamic callback for block
 *
 * @return null
 */
add_action('plugins_loaded', function() {
    register_block_type( 'wp-blocks/iframe', [
        'render_callback' => 'block_iframe'
    ]);
});

/**
 * Helper function for dynamic block front-end output
 *
 * @param int $id   of the selected degree
 *
 * @return string   The markup for the block
 */
function block_iframe( $attributes ) {
    $url = esc_url_raw( $attributes['url'] );

    if( !$url ) wp_send_json_error('invalid url');

    $domain = parse_url($url);

    if($domain['host'] == 'public.tableau.com'){
        $url = str_replace('http:', 'https:', $url);

        if(strpos($domain['path'], 'profile') !== false){
            $url = 'https://public.tableau.com/views/' . str_replace('!/vizhome/', '', $domain['fragment']);
        }

        $explode = explode('?', $url);

        $url = $explode[0] . '?:embed=y&:showVizHome=no&:host_url=https&#37;3A&#37;2F&#37;2Fpublic.tableau.com&#37;2F&:embed_code_version=2&:tabs=yes&:toolbar=yes&:animate_transition=yes&:display_static_image=no&:display_spinner=no&:display_overlay=yes&:display_count=yes&:loadOrderID=0';
    }

    if($domain['host'] == 'www.imleagues.com'){
        $url = str_replace('WidgetLoader.ashx', 'IntramuralWidget.aspx', $url);
    }

    $height = isset( $attributes['height'] ) ? absint( $attributes['height']) : 750;
    $width = isset( $attributes['width'] ) ? absint( $attributes['width']) : 100;

    $scroll = isset( $attributes['disableScroll'] ) ? true : false;

    ob_start(); ?>
        <figure>
            <?php if($url ): ?>
                <iframe
                    src="<?php echo $url; ?>"
                    height="<?php echo $height; ?>"
                    width="<?php echo $width; ?>%"
                    frameborder='0'
                    scrolling="<?php echo $scroll; ?>"
                >
                </iframe>
            <?php else: ?>
                <p>No valid iframe URL provided</p>
            <?php endif; ?>
        </figure>
    <?php
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}
