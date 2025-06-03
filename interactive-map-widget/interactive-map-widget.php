<?php
/**
 * Plugin Name: Interactive Map Elementor Widget
 * Plugin URI: https://www.linkedin.com/in/austin-web-design/
 * Description: Custom Elementor widget that creates an interactive map with clickable location hotspots
 * Version: 1.0.0
 * Author: Jeff Tom
 * Text Domain: interactive-map-widget
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * Elementor tested up to: 3.18
 * Elementor Pro tested up to: 3.18
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('INTERACTIVE_MAP_WIDGET_VERSION', '1.0.0');
define('INTERACTIVE_MAP_WIDGET_FILE', __FILE__);
define('INTERACTIVE_MAP_WIDGET_PATH', plugin_dir_path(__FILE__));
define('INTERACTIVE_MAP_WIDGET_URL', plugin_dir_url(__FILE__));

/**
 * Main Interactive Map Widget Plugin Class
 */
final class Interactive_Map_Widget_Plugin {

    /**
     * Plugin Version
     */
    const VERSION = '1.0.0';

    /**
     * Minimum Elementor Version
     */
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

    /**
     * Minimum PHP Version
     */
    const MINIMUM_PHP_VERSION = '7.4';

    /**
     * Instance
     */
    private static $_instance = null;

    /**
     * Instance
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', [$this, 'i18n']);
        add_action('plugins_loaded', [$this, 'on_plugins_loaded']);
    }

    /**
     * Load Textdomain
     */
    public function i18n() {
        load_plugin_textdomain('interactive-map-widget', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /**
     * On Plugins Loaded
     */
    public function on_plugins_loaded() {
        // Check if Elementor installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return;
        }

        // Check for required Elementor version
        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return;
        }

        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return;
        }

        // Add Plugin actions
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('elementor/elements/categories/register', [$this, 'add_elementor_widget_categories']);
    }

    /**
     * Admin notice
     * Warning when the site doesn't have Elementor installed or activated.
     */
    public function admin_notice_missing_main_plugin() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'interactive-map-widget'),
            '<strong>' . esc_html__('Interactive Map Widget', 'interactive-map-widget') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'interactive-map-widget') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     * Warning when the site doesn't have a minimum required Elementor version.
     */
    public function admin_notice_minimum_elementor_version() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'interactive-map-widget'),
            '<strong>' . esc_html__('Interactive Map Widget', 'interactive-map-widget') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'interactive-map-widget') . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     * Warning when the site doesn't have a minimum required PHP version.
     */
    public function admin_notice_minimum_php_version() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'interactive-map-widget'),
            '<strong>' . esc_html__('Interactive Map Widget', 'interactive-map-widget') . '</strong>',
            '<strong>' . esc_html__('PHP', 'interactive-map-widget') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Register Widgets
     */
    public function register_widgets($widgets_manager) {
        // Include Widget files
        $widget_file = INTERACTIVE_MAP_WIDGET_PATH . 'widgets/interactive-map-widget.php';
        
        if (file_exists($widget_file)) {
            require_once $widget_file;
            
            // Register widget
            if (class_exists('Interactive_Map_Widget')) {
                $widgets_manager->register(new Interactive_Map_Widget());
            }
        } else {
            // Show admin notice if widget file is missing
            add_action('admin_notices', function() {
                $message = sprintf(
                    esc_html__('Interactive Map Widget: Widget file not found at %s', 'interactive-map-widget'),
                    '<code>widgets/interactive-map-widget.php</code>'
                );
                printf('<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', $message);
            });
        }
    }

    /**
     * Add Elementor Widget Categories
     */
    public function add_elementor_widget_categories($elements_manager) {
        $elements_manager->add_category(
            'interactive-widgets',
            [
                'title' => esc_html__('Interactive Widgets', 'interactive-map-widget'),
                'icon' => 'fa fa-plug',
            ]
        );
    }
}

// Initialize the plugin
Interactive_Map_Widget_Plugin::instance();
