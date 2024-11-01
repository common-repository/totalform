<?php
! defined( 'ABSPATH' ) && exit();

/*
 * Plugin Name: TotalForm
 * Plugin URI: https://totalsuite.net/totalform/
 * Description: TotalForm is a powerful WordPress form plugin that helps you collect entries with a few seconds using an intuitive and interactive editor. Preview release.
 * Version: 1.2.0
 * Author: TotalSuite
 * Author URI: https://totalsuite.net/
 * Text Domain: totalform
 * Domain Path: languages
 * Requires at least: 5.4
 * Requires PHP: 7.0
 * Tested up to: 6.6.1
 *
 * @package TotalForm
 * @category Core
 * @author TotalSuite
 * @version 1.2.0
 */


// Environment
$env = require 'environment.php';

// Bootstrap
\TotalForm\Plugin::instance($env);
