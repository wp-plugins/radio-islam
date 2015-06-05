<?php

/**
 * Plugin Name: 	Radio Islam
 * Plugin URI: 		http://radioislam.or.id/
 * Description: 	Radio Islam Indonesia (RII)
 * Version: 		1.0
 * Author: 			Oasemedia Developer
 * Author URI: 		http://radioislam.or.id/
 * Text Domain:     rii
 * Domain Path:     /languages
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// define plugin constants
define( 'RII_PLUGIN_FILE', __FILE__ );
define( 'RII_PLUGIN_VERSION', '1.0' );

// include widget class
require_once( 'includes/widget.php' );
