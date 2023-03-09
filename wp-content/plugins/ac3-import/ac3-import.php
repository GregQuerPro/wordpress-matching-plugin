<?php

/**
 * Plugin Name: AC3 Import
 */


require("class/AC3MenuPage.php");

AC3MenuPage::register();

// require("ac3-cron.php");
require("register-mb-fields.php");

// wget -q -O - http://localhost.com/wp-content/plugins/ac3-import/ac3-cron.php >/dev/null 2>&1

// /home/gregquer/wordpress/wp-content/plugins/ac3-import