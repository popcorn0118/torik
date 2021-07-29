<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'torik' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost:3306' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         'X5rH7>QR&h-C|VA-Mh0-SvCbN]j1`tInJC3SIn9D4eE<Thx@;qr6}rOsf$ICcsOz' );
define( 'SECURE_AUTH_KEY',  'zJky(G$,!hu]d|=bh;E|xaj`h*F;:1nvR.vCKFtfZamJcgI0?&5x2W.c$&[YBxn:' );
define( 'LOGGED_IN_KEY',    '!{Qu7F]<MT})Ju#ddq>%6/ps7^CZ9s7HRKl,+[5[lqI9s=^d3lTe`R4+!LSzyR08' );
define( 'NONCE_KEY',        '}k~L!38|4wrO;3RxsR65`,#_uHc$GRen51,;QRB[[X_8+:F!pQ.M6m$3z,0<WF,4' );
define( 'AUTH_SALT',        'msYj,W+M,mzN^]9y;8TUtTLo/=llzJGgFc?61p4ZmwiZpLhu.jLcM)PO}j(&5t>y' );
define( 'SECURE_AUTH_SALT', 'y!w,8:kw|@m05dy(fEEl44PFi3bg 1/q_15v[TGG0p4;;a &Qv`oT_OuQ!~n$)`|' );
define( 'LOGGED_IN_SALT',   'yOoS;eGNi@}Dk$u8TEwjf^]Ri(0(4r6k4Fx-;!R^}ykBJ||#lv&_Uq91:z4W^x`B' );
define( 'NONCE_SALT',       '>UL8{H 3!=-(:Dr+@)A&$|:pE~`B{Z$QUr#oWYnU9F3HS*F3m|e2S+rfYPM,}}Ma' );

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
