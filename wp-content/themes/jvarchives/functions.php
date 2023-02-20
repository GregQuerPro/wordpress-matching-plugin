<?php

require_once('walker/CommentWalker.php');
require_once('options/apparence.php');
require_once('options/cron.php');

// die('test');

use MBB\Extensions\Group;

require_once('functions-matcher.php');

// phpinfo();

function my_filter_header()
{
    wp_admin_bar_render();
}

// Vidéo
function remove_width_height_attributes($html)
{
    return preg_replace('/(width|height)="\d*"\s/', "", $html);
}

function montheme_supports()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
    add_theme_support('html5');
    register_nav_menu('header', 'En tête du menu');
    register_nav_menu('footer', 'Pied de page');

    add_image_size('card-header', 350, 215, true);
}

function montheme_register_assets()
{
    wp_register_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css');
    wp_enqueue_style('style', get_stylesheet_uri());
    wp_register_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js', [], false, true);
    wp_enqueue_script('bootstrap');
    wp_enqueue_style('bootstrap');
}

function montheme_title_separator()
{
    return '|';
}

function montheme_document_title_parts($title)
{
    if (!empty($title['tagline'])) {
        unset($title['tagline']);
    }

    return $title;
}

function montheme_menu_class($classes): array
{
    $classes[] = 'nav-item';

    return $classes;
}

function montheme_menu_link_class($attrs): array
{
    $attrs['class'] = 'nav-link';

    return $attrs;
}

function montheme_pagination()
{
    $pages = paginate_links(['type' => 'array']);
    if (!$pages) {
        return;
    }
    echo '<nav aria-label="Pagination" class="my-4">';
    echo '<ul class="pagination">';
    foreach ($pages as $page) {
        $active = strpos($page, 'current') !== false;
        $class = $active ? ' active' : '';
        echo '<li class="page-item' . $class . '">';
        echo str_replace('page-numbers', 'page-link', $page);
        echo '</li>';
    }
    echo '</ul>';
    echo '</nav>';
}

function montheme_add_custom_box()
{
    add_meta_box('montheme_sponso', 'Sponsoring', 'montheme_render_sponso_box', 'post', 'side');
}

function montheme_render_sponso_box()
{ ?>
    <input type="hidden" value="0" name="montheme_sponso">
    <input type="checkbox" value="1" name="montheme_sponso">
    <label for="montheme-sponso">Cet article est-il sponsorisé ?</label>
<?php
}

function montheme_init()
{
    register_taxonomy('sport', 'post', [
        'labels' => [
            'name' => 'Sport',
            'singular_name'     => 'Sport',
            'plural_name'       => 'Sports',
            'search_items'      => 'Rechercher des sports',
            'all_items'         => 'Tous les sports',
            'edit_item'         => 'Editer le sport',
            'update_item'       => 'Mettre à jour le sport',
            'add_new_item'      => 'Ajouter le sport',
            'new_item_name'     => 'Ajouter un nouveau sport',
            'menu_name'         => 'Sport',
        ],
        'show_in_rest' => true,
        'hierarchical' => true,
        'public' => true,
        'show_in_quick_edit' => true,
        'show_admin_column' => true,
    ]);
}

add_action('init', 'montheme_init');
add_filter('document_title_separator', 'montheme_title_separator');
add_filter('document_title_parts', 'montheme_document_title_parts');
add_filter('embed_oembed_html', 'remove_width_height_attributes');

add_filter('nav_menu_css_class', 'montheme_menu_class');
add_filter('nav_menu_link_attributes', 'montheme_menu_link_class');

add_action('wp_head', 'my_filter_header');
add_action('after_setup_theme', 'montheme_supports');
add_action('wp_enqueue_scripts', 'montheme_register_assets');

require_once('metaboxes/SponsoMetabox.php');
require_once('options/Agence.php');

SponsoMetaBox::register();
AgenceMenuPage::register();


// Colonnes Biens

add_filter('manage_bien_posts_columns', function ($columns) {
    return [
        'cb' => $columns['cb'],
        'thumbnail' => 'Miniature',
        'title' => $columns['title'],
        'date' => $columns['date']
    ];
});

add_filter('manage_bien_posts_custom_column', function ($column, $postId) {
    if ($column === 'thumbnail') {
        the_post_thumbnail('thumbnail', $postId);
    }
}, 10, 2);

add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('admin_montheme', get_template_directory_uri() . '/assets/admin.css');
});

// Colonnes Articles

add_filter('manage_post_posts_columns', function ($columns) {
    $newColumn = [];
    foreach ($columns as $key => $value) {
        if ($key === 'date') {
            $newColumn['sponsoring'] = 'Sponsorisé ?';
        }
        $newColumn[$key] = $value;
    }
    return $newColumn;
});

add_filter('manage_post_posts_custom_column', function ($column, $postId) {
    if ($column === 'sponsoring') {
        echo !empty(get_post_meta($postId, SponsoMetaBox::META_KEY, true))
            ? '<div class="bullet bullet-yes">Oui</div>'
            : '<div class="bullet bullet-no">Non</div>';
    }
}, 10, 2);


function montheme_pre_get_posts(WP_Query $query)
{
    if (is_admin() || !is_search() || !$query->is_main_query()) {
        return;
    }
    if (get_query_var('sponso') === '1') {
        $metaQuery = $query->get('meta_query', []);
        $metaQuery[] = [
            'key' => SponsoMetaBox::META_KEY,
            'compare' => 'EXISTS'
        ];
    }
    $query->set('meta_query', $metaQuery);
}

function montheme_query_vars($params)
{
    $params[] = 'sponso';
    return $params;
}

add_action('pre_get_posts', 'montheme_pre_get_posts');
add_filter('query_vars', 'montheme_query_vars');

require_once 'widgets/YoutubeWidget.php';

function montheme_register_widget()
{
    register_widget(YoutubeWidget::class);
    register_sidebar([
        'id' => 'homepage',
        'name' => 'Sidebar Accueil', 'montheme',
        'before_widget' =>  '<div class="p-4 %2$s" id="%1$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 style="font-style:italic">',
        'after_title' => '</h4>'
    ]);
}

add_action('widgets_init', 'montheme_register_widget');


add_filter('comment_form_default_fields', function ($fields) {
    $fields['email'] = <<<HTML
    <div class="class-group">
        <label for="email">Email</label>
        <input type="text" class="form-control" name="email" id="email" required>
    </div>  
HTML;
    return $fields;
});

add_action('after_switch_theme', function () {
    wp_insert_term('Volleyball', 'sport');
    wp_insert_term('Natation', 'sport');
    flush_rewrite_rules();
});

add_action('switch_theme', function () {
    flush_rewrite_rules();
});

add_action('after_setup_theme', function () {
    load_theme_textdomain('montheme', get_template_directory() . '/languages');
});

add_action('rest_api_init', function () {
    register_rest_route('montheme/v1', '/demo/(?P<id>\d+)', [
        "methods" => 'GET',
        'callback' => function (WP_REST_Request $request) {
            $postID = (int)$request->get_param('id');
            $post = get_post($postID);
            if ($post === null) {
                return new WP_Error('Pas de titre', 'Aucun titre n\'a été trouvé pour l\'article spécifié');
            }
            return $post->post_title;
        },
        'permission_callback' => function () {
            return current_user_can('publish_posts');
        }
    ]);
});

add_filter('rest_authentication_errors', function ($result) {
    if (true === $result || is_wp_error($result)) {
        return $result;
    }
    /** @var wp $wp */
    global $wp;
    if (strpos($wp->query_vars['rest_route'], 'montheme/v1') !== false) {
        return true;
    }
    return $result;
}, 9);

function monthemeReadData()
{
    $data = wp_cache_get('data', 'montheme');
    if ($data === false) {
        var_dump('Je lis');
        $data = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'data');
        wp_cache_set('data', $data, 'montheme', 60);
    }
    return $data;
}

if (isset($_GET['cachetest'])) {
    var_dump(monthemeReadData());
    var_dump(monthemeReadData());
    var_dump(monthemeReadData());
    die();
}


add_action('init', function () {
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page(
            [
                'page_title' => 'Options de l\'agence'
            ]
        );
    }
});

add_filter('block_categories', function ($categories) {
    $categories[] = [
        'slug' => 'theme',
        'title' => 'Theme',
        'icon' => null
    ];
    return $categories;
});


if (function_exists('acf_register_block_type')) {
    add_action('acf/init', function () {
        acf_register_block_type([
            'name' => 'highlighted_posts',
            'title' => 'Article mis en avant',
            'icon' => 'welcome-widgets-menus',
            'render_template' => 'blocs/highlighted.php',
            'enqueue_style' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css',
            'category' => 'theme',
            'supports' => [
                'align' => false,
                'mode' => true,
                'multiple' => false
            ]
        ]);
    });
}
