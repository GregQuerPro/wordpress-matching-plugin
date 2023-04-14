<?php

class AC3MenuPage
{

    const GROUP = 'ac3_import_settings';

    public static function register()
    {
        add_action('admin_menu', [self::class, 'addMenuPage']);
    }


    public static function addMenuPage()
    {
        add_menu_page(
            'AC3 Importer Titre',
            'AC3 Importer',
            'manage_options',
            'ac3-import/ac3-import-admin.php',
            [self::class, 'renderMenuPage'],
            'dashicons-download',
            101
        );
    }


    public static function renderMenuPage()
    {

        if (isset($_POST['submit'])) {
            // Le formulaire a été soumis, récupérer les valeurs des champs du formulaire
            $url = sanitize_text_field($_POST['url']);
            // Valider les données du formulaire
            if (!empty($url)) {
                update_option('ac3_importer_url', $url);
            }
        }

        $url = get_option('ac3_importer_url');
        // Afficher le formulaire HTML
?>
        <div class="wrap">
            <h1>AC3 Importer</h1>
            <form method="POST">
                <?php wp_nonce_field('submit-form', 'submit-form-nonce'); ?>
                <p>
                    <label for="url"><strong>URL</strong></label><br>
                    <input type="text" name="url" id="url" value="<?= isset($url) ? $url : '' ?>" style="width: 300px">
                </p>
                <input type="submit" name="submit" value="Enregistrer">
            </form>
        </div>
<?php
    }
}

?>