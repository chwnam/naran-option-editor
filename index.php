<?php
/*
 * Plugin Name:       Naran Option Editor
 * Description:       Edit option in the admin screen.
 * Author:            Changwoo
 * Plugin URI:        https://github.com/chwnam/naran-option-editor
 * Version:           1.2.0
 * Requires PHP:      7.4
 * Requires at least: 5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NOE_MAIN', __FILE__ );
define( 'NOE_VERSION', '1.2.0' );

require_once __DIR__ . '/vendor/autoload.php';

noe();
