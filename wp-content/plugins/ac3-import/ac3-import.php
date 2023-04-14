<?php

/**
 * Plugin Name: AC3 Import
 */


require("class/AC3MenuPage.php");
AC3MenuPage::register();


/**
 * On n'importe pas le code de ce fichier
 * Il est exécuté par un cron sur le serveur
 * Seulement pour les tests
 */
require("ac3-cron.php");
