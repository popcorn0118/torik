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
define( 'DB_HOST', 'localhost:8889' );

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
define( 'AUTH_KEY',         'WW1!<USYK6sS`OQdS{fjn#I3V@xV:_<~uKcwH7+v1{ !s8skD8e&QLG.y&  m408' );
define( 'SECURE_AUTH_KEY',  '7q(83D!1C`zq,Gro_?l[9HJWOM/M$!1_a<I]X*VMpQ+rI,j.5ur~OTwFErhdXXGA' );
define( 'LOGGED_IN_KEY',    '^,w(RXK5z4e,Yv/Q6v:f4E6{<b)!1Pu}@yaOzjNQ#fp@X3.fe y^I A595DEMCTw' );
define( 'NONCE_KEY',        '>Hb^*N6g!&.}9i%T*pJ!9]m7x$NgV*mtI7>#Qx>I9|`ZkwyM(-J9|)L$DRSOO3E ' );
define( 'AUTH_SALT',        'u_)jyt)J_J>Q}.r=I|Yz<jW*`&H;Yp[Y-gCq5sniWvq}dk=uPF9}od^D(A}htX7x' );
define( 'SECURE_AUTH_SALT', 'DSb`4PHF3-_v|E2(DVv&t9T209T*aAk+Kc.@TQ5~Pgg]4jreqxbj(S*nHkLM;vGL' );
define( 'LOGGED_IN_SALT',   'P<N}tZ}Fuu$*p^xc>mi%iotmt4&yp<t2idrl|[ziHvO<TPH]RN.2#Q1sCZ*LSpfd' );
define( 'NONCE_SALT',       'D$^$Vo/?{!;v>v_4&:TDf$CgiKfFTow^T=/gZ3cR/s9HO/+B./WrNQS^pZi.~@^T' );

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
