<?php
/*
 * Plugin Name:       Naran Option Editor
 * Description:       Edit option in the admin screen.
 * Author:            Changwoo
 * Author URI:        https://blog.changwoo.pe.kr
 * Plugin URI:        https://github.com/chwnam/naran-option-editor
 * Version:           1.8.1
 * Requires PHP:      7.4
 * Requires at least: 5.0
 * Text Domain:       noe
 * Domain Path:       /languages
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const NOE_MAIN    = __FILE__;
const NOE_VERSION = '1.8.1';

require_once __DIR__ . '/vendor/autoload.php';

noe();
