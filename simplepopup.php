<?php
/*
 * Plugin Name:       Simple Popup
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       A simple lightweight popup, fast, and powerful. Create anything on text editor, and woolaaa.. your popup is captivating audiences effortlessly
 * Version:           0.0.1
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            Sonny Michael
 * Author URI:        https://github.com/isonnymichael
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://github.com/isonnymichael/simplepopup-framework
 * Text Domain:       simple-popup-plugin
 * Domain Path:       /languages
 */

! defined( 'WPINC ' ) || die;

/** Load Composer Vendor */
require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/** Initiate Plugin */
$simplepopup = new SimplePopup\Plugin();
$simplepopup->run();
