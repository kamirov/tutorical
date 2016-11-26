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
define('DB_NAME', 'aeterna_tutorical');

/** MySQL database username */
define('DB_USER', '');

/** MySQL database password */
define('DB_PASSWORD', 'nbcb1056');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '-&!9]@azqDO#n2HE*3Qaw<Jhi&0*vMm9GGGu_u`LP_go1+R5~<U:=*ap>,NkGggf');
define('SECURE_AUTH_KEY',  ' lT9+S+{sV!W+bK~]ceEeeM{8o>O$[m!)cTW}+8VMAq/J8j*ahieI`U@QT eP1^`');
define('LOGGED_IN_KEY',    '^s?>qBRi_@LJ:I@[<?_`jJF$:`*%W+=yY$Qw?AWYB.X~,OcQl-rtjzN&0`l.GE|F');
define('NONCE_KEY',        '3Wsyld3@,3K?XHqyhH{a2a^6%[Tii$k$`n.3u6$G2F:&*S4|L#eQ xHG>}4|RM5,');
define('AUTH_SALT',        'RH|P#DU.a]oK5m8<z4$7QYG5saf^rUZNViq&rc-hJ}npbLwE5h{&dJg> 4u@_fk ');
define('SECURE_AUTH_SALT', 'QN`6><Q+3~Y$bKx?$5[MfCp(Z5dQ[H?3YVfy7jW?lrHNAPN4C{Nk*!?DEH@w(V0E');
define('LOGGED_IN_SALT',   'f0hr87i|h#oNtTJ7.|9xIrltLFeJ_V^}>U$c 1-Uxs:7sNvAJS}C(w+ox2I{Ql{*');
define('NONCE_SALT',       '[1>&gT47I3~?h.x0=3`7Wi;n):5UBMqEb}j?aT Ynn*Y4WsII!RLb3wd&<>5ookR');

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
