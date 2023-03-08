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
        global $wpdb;
        $table_name = $wpdb->prefix . 'ac3_importer_crons';
        $results = $wpdb->get_results("SELECT * FROM $table_name");
        $result = $results[0];

        if (isset($_POST['submit'])) {

            // Le formulaire a été soumis, récupérer les valeurs des champs du formulaire
            $interval = sanitize_text_field($_POST['interval']);
            $url = sanitize_text_field($_POST['random']);
            $to_download = sanitize_textarea_field($_POST['to_download']);
            $to_send = sanitize_textarea_field($_POST['to_send']);

            // Valider les données du formulaire
            if (!empty($interval) && !empty($url) && !empty($to_download) && !empty($to_send)) {
                // Les données du formulaire sont valides, les stocker dans la table MySQL
                // var_dump($results);

                $data = [
                    'exe_interval' => $interval,
                    'url' => $url,
                    'to_download' => $to_download,
                    'to_send' => $to_send,
                ];
                if (empty($results)) {
                    $wpdb->insert($table_name, $data);
                    $results = $wpdb->get_results("SELECT * FROM $table_name");
                    $result = $results[0];
                    echo "<div class='notice notice-success is-dismissible'><p>Données enregistrées avec succès!</p></div>";
                } else {
                    $where = ['id' => $results[0]->id];
                    $wpdb->update($table_name, $data, $where);
                    $results = $wpdb->get_results("SELECT * FROM $table_name");
                    $result = $results[0];
                    echo "<div class='notice notice-success is-dismissible'><p>Données enregistrées avec succès!</p></div>";
                }
            } else {
                echo "<div class='notice notice-error is-dismissible'><p>Veuillez remplir tous les champs du formulaire.</p></div>";
            }
        }

        // Afficher le formulaire HTML
?>
        <div class="wrap">
            <h1>AC3 Importer</h1>
            <form method="POST">
                <?php wp_nonce_field('submit-form', 'submit-form-nonce'); ?>
                <p>
                    <label for="interval"><strong>Intervalle (en secondes)</strong></label><br>
                    <input type="number" name="interval" id="interval" value="<?= isset($result) ? $result->exe_interval : '' ?>">
                </p>
                <p>
                    <label for="random"><strong>URL</strong></label><br>
                    <input type="text" name="random" id="random" value="<?= isset($result) ? $result->url : '' ?>">
                </p>
                <p>
                    <label for="to_download"><strong>Emplacement où télécharger le fichier</strong></label><br>
                    <input type="text" name="to_download" id="to_download" value="<?= isset($result) ? $result->to_download : '/' ?>">
                </p>
                <p>
                    <label for="to_send"><strong>Emplacement où envoyer le fichier formaté</strong></label><br>
                    <input type="text" name="to_send" value="<?= isset($result) ? $result->to_send : '/' ?>" id="to_send" required>
                </p>
                <input type="submit" name="submit" value="Enregistrer">
            </form>
        </div>
<?php
    }
}

?>