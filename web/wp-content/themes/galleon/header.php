<!doctype html>
<!--
                       ____________________  ____ ____
                       \_   _____/\_   ___ \|    |    \
                        |    __)_ /    \  \/|    |    /
                        |        \\     \___|    |   /
                       /________  /\______  /_______/
                                \/        \/

                                       |~
                                 |/    w
                                / (   (|   \
                                /( (/   |)  |\
                        ____  ( (/    (|   | )  ,
                        |----\ (/ |    /|   |'\ /^;
                      \---*---Y--+-----+---+--/(
                        \------*---*--*---*--/
                         '~~ ~~~~~~~~~~~~~~~
  ________         __________.__               __                ._._.
 /  _____/  ____   \______   \__|___________ _/  |_  ____   _____| | |
/   \  ___ /  _ \   |     ___/  \_  __ \__  \\   __\/ __ \ /  ___/ | |
\    \_\  (  <_> )  |    |   |  ||  | \// __ \|  | \  ___/ \___ \ \|\|
 \______  /\____/   |____|   |__||__|  (____  /__|  \___  >____  >____
        \/                                  \/          \/     \/ \/\/
-->
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?php print_page_title(); ?></title>
        <link rel="shortcut icon" href="<?php echo get_template_directory_uri() . '/images/favicon.png'; ?>">
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <div class="content-wrap" id="top">
        <?php include_once('template-parts/navigation/navigation.php'); ?>
        <?php print_hero(); ?>
        <?php // TODO: Delete Me
        if( !is_ecu_post_migrated() ) include_once('inc/legacy/heros/hero.php'); ?>
        <main role="main" id="main">
            <?php main_content_wrap(); ?>
                <div class="container">