<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'sandbox6_skopes');

/** MySQL database username */
define('DB_USER', 'sandbox6_skopes');

/** MySQL database password */
define('DB_PASSWORD', 'Rvtech4you#');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('CONCATENATE_SCRIPTS', false);
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'E&VBZ.-o(&gHO%Nks1bHyLb{QJOL dc~+-4:~,x|[dc$Uk,CMcddMHKJVbXYyPHX');
define('SECURE_AUTH_KEY',  '{0Zk,yKZ>.Lxg<9((Sm#NM[^6f`|Q!lWl1Vadi0>V+6|%b4*Zy-!6-b|3T[z|c2D');
define('LOGGED_IN_KEY',    'gJ)U?.v&xS}-uwQ$+-mXy1{|O<}*0)#?-B(*>9@sWnw7X=~D74cHp,t?vQWj;/$C');
define('NONCE_KEY',        'Ro[tE )~b7!!DTg/c#w,+L5^OIAb$i<m&s%#mer0KrIxb/l<CuD? 3<-O=hJB`k1');
define('AUTH_SALT',        '@M*f.+*v?$cvO+rd2oIuRmBL=,$t?yb+cg{reX|MQPU|N*EBgW#+ %>#!jywuQ0Y');
define('SECURE_AUTH_SALT', 'ke&->Gd&&6;WiBVkf.(MIo|nrX!u([0TqIt,~GD^w~oNl_|D#N()lx YpPKhqQqW');
define('LOGGED_IN_SALT',   'KO- xkSOVw20.F Hq-mSSFL|+`Zt5xp )wP2GV`3nDY]0Or=)Pcu8i+V@k@Wg+.W');
define('NONCE_SALT',       '1|@^V.AIRcIQ;juO3Z}t(-_Gm4{`-MR^)Zd]-R%%OfcA8:cg6<}aHfjw(4t!Alw/');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);
putenv('TMPDIR=/tmp');
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
?>
