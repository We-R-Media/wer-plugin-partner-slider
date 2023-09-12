<?php

/**
 * Plugin Name: Partner Slider
 * Description: Slider for adding partners as custom post types and show them with the use of a shortcode.
 * Version: 0.0.1
 * Author: We'r Media
 * Author URI: https://wermedia.nl
 * Text Domain: wer-partner-slider
 * License: GPL2
 */

use Wer\PartnerSlider\Autoloader;
use Wer\PartnerSlider\PostTypeManager;
use Wer\PartnerSlider\Slider;

if (!defined('WER_PLUGIN_PATH')) {
    define('WER_PLUGIN_PATH', plugin_dir_path(__FILE__));
}

if (!defined('WER_PLUGIN_PATH')) {
    define('WER_PLUGIN_URL', plugin_dir_url(__FILE__));
}


// Require autoload file.
if ( file_exists( WER_PLUGIN_PATH . 'includes/Utils/Autoloader.php' ) ) {
    require_once WER_PLUGIN_PATH . 'includes/Utils/Autoloader.php';
}

/**
 * Initialize the plugin.
 * First we create and call an autoloader to automatically add new classes
 */
function wer_plugin_slider_init()
{
    Autoloader::register();

    PostTypeManager::initialize();
    
    $slider = new Slider();
}
add_action('plugins_loaded', 'wer_plugin_slider_init');
