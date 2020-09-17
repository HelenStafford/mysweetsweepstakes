<?php
/**
 Plugin Name: AffiliateESY
 Plugin URI: http://ideasinsider.com
 Description: AffiliateESY is a brand new WordPress plugin that creates a fully automated affiliate site for you in just 30 seconds. It's Automatically imports recurring commission paying offers from Clickbank.
 Version: 1.0
 Author: Venkatesh Kumar
 Author URI: http://ideasinsider.com
 Text Domain: easy-recurring-paydays
 Domain Path: /asset/ln
 License: GPLv2
 License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
if (!defined('ABSPATH')) exit;

defined('AFFILIATE_ESY_DEBUG') or define('AFFILIATE_ESY_DEBUG', false);

defined('AFFILIATE_ESY_PATH') or define('AFFILIATE_ESY_PATH', plugin_dir_path(__FILE__));
defined('AFFILIATE_ESY_FILE') or define('AFFILIATE_ESY_FILE', plugin_basename(__FILE__));

defined('AFFILIATE_ESY_TRANSLATE') or define('AFFILIATE_ESY_TRANSLATE', plugin_basename( plugin_dir_path(__FILE__).'asset/ln/'));
defined('AFFILIATE_ESY_IMAGE') or define('AFFILIATE_ESY_IMAGE', plugins_url('/asset/img/', __FILE__));


//The Plugin
require_once('autoload.php');
if ( class_exists( 'AFFILIATE_ESY_BUILD' ) ) new AFFILIATE_ESY_BUILD(); ?>
