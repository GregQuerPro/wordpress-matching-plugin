<?php

/**
 * Plugin Name: MB Matching
 */

// ?_geolocalisation=48.539927%2C2.660817%2C100%2CMelun%2C%20Seine-et-Marne%2C%20France&_price=2000%2C1407000&_surface=235%2C550&_room=2&price%5Bsign%5D=>&price%5Bimportant%5D%5Bweight%5D=10&price%5Bessentiel%5D%5Bweight%5D=20&price%5Bessentiel%5D%5Bchoice%5D=on&surface%5Bsign%5D=<&surface%5Bimportant%5D%5Bweight%5D=30&surface%5Bessentiel%5D%5Bweight%5D=40&surface%5Bessentiel%5D%5Bchoice%5D=on

// die();

defined('ABSPATH') or die('rien à voir');


/**
 * Register a custom menu page.
 */
function custom_menu_page()
{
    add_menu_page(
        'MB Matching Title',
        'MB Matching',
        'manage_options',
        'mb-matching/mb-matching-admin.php',
        '',
        'dashicons-networking',
        100
    );
}
add_action('admin_menu', 'custom_menu_page');


function mb_matching_scripts()
{
    $plugin_url = plugin_dir_url(__FILE__);

    wp_enqueue_style('style',  $plugin_url . "/css/style.css");
    wp_enqueue_script('script',  $plugin_url . "/js/script.js");
}

add_action('admin_print_styles', 'mb_matching_scripts');


function mon_shortcode_function()
{
    global $wpdb;
    $results = $wpdb->get_results("SELECT * FROM user_searches_options WHERE to_hide = 0");


    if (!empty($_GET)) {
        require_once("class/UserSearch.php");
        // var_dump($_POST);
        $userSearch = new UserSearch($_GET);
        // var_dump($_GET);
        $exist = $userSearch->formatData();
        if ($exist) {
            $userSearch->insertData();
        }
    }
?>
    <form class="user-searches-ctn" action="/" method="GET">
        <?php
        foreach ($results as $result) {
            $name = lcfirst($result->name);
        ?>
            <div class="user-searches">
                <input type="hidden" name="<?= $name ?>">
                <label for="<?= $name ?>[importance]">Importance <?= $name ?></label><br>
                <div>
                    <input type="hidden" name="<?= $name ?>[sign]" value="<?= $result->sign ?>">
                    <label for="<?= $name ?>[importance]">Important</label>
                    <input type="hidden" name="<?= $name ?>[important][weight]" value="<?= $result->weight_base ?>">
                    <input type="checkbox" name="<?= $name ?>[important][choice]" id="<?= $name ?>[importance]">
                    <label for="<?= $name ?>[essentiel]">Essentiel</label>
                    <input type="hidden" name="<?= $name ?>[essentiel][weight]" value="<?= $result->weight_essential ?>">
                    <input type="checkbox" name="<?= $name ?>[essentiel][choice]" id="<?= $name ?>[essentiel]">
                </div>
            </div>
        <?php
        }
        ?>
        <button type="submit">Valider</button>
    </form>
<?php

}
add_shortcode('matching', 'mon_shortcode_function');
