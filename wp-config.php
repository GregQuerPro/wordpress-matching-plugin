<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */

define('DISABLE_WP_CRON', true);

// algo-metabox
define('DB_NAME', 'wordpress');

/** Database username */
define('DB_USER', 'root');

/** Database password */
define('DB_PASSWORD', 'root');

/** Database hostname */
define('DB_HOST', 'localhost:3306');

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '_cH#bVo=Ug:m0O~pcm}hbpfL>X$L3f:( Q7rNSZ2<=9=2Y$+]D;Z7weTzG1rdxi3');
define('SECURE_AUTH_KEY',  'tv@)9oD j80B]}1]d}tc)^Tey4< lL5Xie S0jnv4*<$[I)p%Jw8{8Kof2y ^LId');
define('LOGGED_IN_KEY',    '$?^_xGfVzj$):s%Z_$%.Z_p6sx^E7(ZSdHslf4Mk_CKAEJ79B2sZS7x^dd8py$ ,');
define('NONCE_KEY',        '(Mr{HXV^J^q2=Xma 38[aJeu*+2m 3(}p3Yl_[38oC}#sxLhi.J8lgb`y7{D,8R_');
define('AUTH_SALT',        's&=FTH#!bV0PZ#$Wf/ZNCcO|9_{@$4 j92W}Os.jtwuwR/xjH[xdQ%:.?Gj4q@$!');
define('SECURE_AUTH_SALT', '-na$!A$tV)F4Rxk `qrQ_*?41ksJ>x|v2}.c/pX8ee~]<@mr&A(}zZ^P Dg$RS1!');
define('LOGGED_IN_SALT',   '_BX%+|Zv``Pgr_+m@dvdGRRr~D>D]koEKHl?[iEP353128Z#~bi> _gStdX3R>ML');
define('NONCE_SALT',       '+7ecU%wYzr$aas3WdrcvS=d<9{< >(}!~1cnuTl<c}k[+^;)9kUo_`Y5oahs:<.m');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
// define('WP_DEBUG', true);
// define('WP_DEBUG_LOG', true);


/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
	define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
