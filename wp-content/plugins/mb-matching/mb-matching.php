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


?>
    <form class="user-searches-ctn">
        <?php
        foreach ($results as $result) {
            $name = lcfirst($result->name);
        ?>
            <div class="user-searches">
                <input type="hidden" name="<?= $name ?>">
                <label for="<?= $name ?>[importance]">Importance <?= $name ?></label><br>
                <div>
                    <input type="hidden" name="<?= $name ?>[sign]" value="<?= $result->sign ?>">
                    <label for="basic_<?= $name ?>">Important</label>
                    <input type="radio" name="<?= $name ?>" id="basic_<?= $name ?>" class="radio" value="<?= $name ?>" checked>
                    <label for="essential_<?= $name ?>">Essentiel</label>
                    <input type="radio" name="<?= $name ?>" id="essential_<?= $name ?>" value="<?= $name ?>" class="radio_essentiel">
                </div>
            </div>
        <?php
        }
        ?>
        <button id="btn" type="submit">Valider</button>
    </form>
    <script>
        const form = document.querySelector('.user-searches-ctn');
        form.addEventListener('submit', (e) => {
            e.preventDefault();

            // Récupérer les critères de la recherche dans l'url
            const queryString = window.location.search;

            // Récupérer la liste des critères essentiels
            const radioEssentiel = document.querySelectorAll('.radio_essentiel');
            const radioChecked = [];
            radioEssentiel.forEach(item => {
                if (item.checked) {
                    radioChecked.push(item.name)
                }
            })


            // console.log(params);
            // console.log(radioChecked);

            var formData = new FormData();
            formData.append('action', 'my_ajax_action');
            formData.append('params', queryString);
            formData.append('essentials', radioChecked);

            fetch('<?php echo admin_url("admin-ajax.php"); ?>?', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => console.log(data))
                .catch(error => console.log(error));
        });
    </script>

<?php

}
add_shortcode('matching', 'mon_shortcode_function');


function my_ajax_function()
{
    if (!empty($_POST)) {
        $queryString =  $_POST['params'];
        $essentials = $_POST['essentials'];
        $queryString = substr($queryString, 1);

        // Parse la chaîne de requête en tableau
        parse_str($queryString, $queryArray);

        require_once("class/UserSearch.php");
        $userSearch = new UserSearch($queryArray, $essentials);
        // var_dump($_GET);
        $exist = $userSearch->formatData();
        if ($exist) {
            $userSearch->insertData();
        }
    }
    // Affiche le tableau résultant
    // print_r($queryArray);
    // var_dump($essentials);
    die();
}
add_action('wp_ajax_my_ajax_action', 'my_ajax_function');
add_action('wp_ajax_nopriv_my_ajax_action', 'my_ajax_function');
