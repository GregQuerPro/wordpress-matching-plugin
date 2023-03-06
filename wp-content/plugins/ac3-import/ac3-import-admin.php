<?php
add_action('admin_menu', 'montheme_addMenu');

function montheme_addMenu()
{
    add_options_page("Importation de AC3", "AC3", "manage_options", "ac3_options", 'montheme_render');
}

function montheme_render()
{
?>
    <h1>AC3 Import</h1>
    <form action="options.php" method="POST">
        <?php settings_fields("ac3_options") ?>
        <?php do_settings_sections("ac3_options") ?>
        <?php submit_button() ?>
    </form>
<?php
}

?>