<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
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
define( 'DB_NAME', 'wp_test' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'wz*~Zg&.CoIXTVh>~zE:O;7FRIw&Ddjq.};~j{DxQahs!i=}h997/W,4%e1f:+4q' );
define( 'SECURE_AUTH_KEY',  '~:*6I>B-&SaH>Z&i*T)tm+Z1&SYam!]:P-bj|dLONJN{)sy;(1XA6;S#^U.Z7I(~' );
define( 'LOGGED_IN_KEY',    'C)8Z@~/(#Gz~I2gZ8[d0Qds0i`sH%%@Nx)]X:u;8Uy+nxv3TU<u(!lvjv`+fNa; ' );
define( 'NONCE_KEY',        '%90%8.rH?3Zztd=?UO2NdH>O7ilh(?y[;YdKwL!:,&nUyz;:.mVnu$5w=uu~T-?R' );
define( 'AUTH_SALT',        '12yC@?fd+cU,$]g4OSx_T=~rY1#e.vpw}_cOTG&_4+}M+5Fn-V0cJlWH:hWwc//D' );
define( 'SECURE_AUTH_SALT', 'Ya!1AAR%Wv,c1J5.!~DxZ/Wa]-pSy6Eu5MTl2PCT:RQ.,1:8eCs!vb!Z`q:!.zze' );
define( 'LOGGED_IN_SALT',   '`GR5-e7wV0AalUb?ca?#V%e6~yq!+Z<r:n?mWKi/f %__~5~r4:J0YrWMr^7|wKg' );
define( 'NONCE_SALT',       '^A/KG<VeVUM2xBvk<^WzWaAS`f;f0^^vf7Akt;6QIU?;Xm3$6=dKIQX=K 7o1BL|' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
