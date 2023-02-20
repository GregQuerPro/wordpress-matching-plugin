<?php

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('jvarchives-enfant', get_stylesheet_uri());
    wp_deregister_style('bootstrap');
}, 11);

add_action('after_setup_theme', function () {
    load_child_theme_textdomain('montheme-enfant', get_stylesheet_directory() . '/languages');
}, 11);

add_filter('montheme_search_title', function () {
    return 'Recherche : %s';
});
