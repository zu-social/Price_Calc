<?php
/**
 * Plugin Name: Metal Prices Pro
 * Plugin URI: https://yourwebsite.com/metal-prices-pro
 * Description: A comprehensive metal prices plugin that displays real-time gold, silver, platinum, and palladium prices with calculator functionality.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: metal-prices-pro
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('METAL_PRICES_PRO_VERSION', '1.0.0');
define('METAL_PRICES_PRO_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('METAL_PRICES_PRO_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Plugin activation
 */
function metal_prices_pro_activate() {
    // Set default options
    if (get_option('metal_prices_pro_currency') === false) {
        add_option('metal_prices_pro_currency', 'GBP');
    }
    if (get_option('metal_prices_pro_cache_duration') === false) {
        add_option('metal_prices_pro_cache_duration', 300);
    }
    if (get_option('metal_prices_pro_display_decimals') === false) {
        add_option('metal_prices_pro_display_decimals', 2);
    }
    
    // Schedule daily refresh
    if (!wp_next_scheduled('metal_prices_daily_refresh')) {
        // Schedule for tomorrow at 9:30 AM
        $tomorrow_930 = strtotime('tomorrow 9:30 AM');
        wp_schedule_event($tomorrow_930, 'daily', 'metal_prices_daily_refresh');
    }
}

/**
 * Plugin deactivation
 */
function metal_prices_pro_deactivate() {
    // Clear scheduled events
    wp_clear_scheduled_hook('metal_prices_daily_refresh');
    
    // Clear transients
    delete_transient('metal_prices_pro_cached_prices');
}

/**
 * Plugin uninstall
 */
function metal_prices_pro_uninstall() {
    // Clear scheduled events
    wp_clear_scheduled_hook('metal_prices_daily_refresh');
    
    // Delete options
    delete_option('metal_prices_pro_currency');
    delete_option('metal_prices_pro_cache_duration');
    delete_option('metal_prices_pro_display_decimals');
    delete_option('metal_prices_pro_last_updated');
    delete_option('metal_prices_pro_last_daily_refresh');
    
    // Clear transients
    delete_transient('metal_prices_pro_cached_prices');
}

// Register hooks
register_activation_hook(__FILE__, 'metal_prices_pro_activate');
register_deactivation_hook(__FILE__, 'metal_prices_pro_deactivate');
register_uninstall_hook(__FILE__, 'metal_prices_pro_uninstall');

/**
 * Load plugin files
 */
function metal_prices_pro_init() {
    // Load API handler
    $api_handler_file = METAL_PRICES_PRO_PLUGIN_DIR . 'includes/class-api-handler.php';
    if (file_exists($api_handler_file)) {
        require_once $api_handler_file;
        if (class_exists('Metal_Prices_Pro_API_Handler')) {
            new Metal_Prices_Pro_API_Handler();
        }
    }
    
    // Load shortcodes
    $shortcodes_file = METAL_PRICES_PRO_PLUGIN_DIR . 'includes/class-shortcodes.php';
    if (file_exists($shortcodes_file)) {
        require_once $shortcodes_file;
        if (class_exists('Metal_Prices_Pro_Shortcodes')) {
            new Metal_Prices_Pro_Shortcodes();
        }
    }
}

// Initialize plugin
add_action('plugins_loaded', 'metal_prices_pro_init');

/**
 * Initialize Elementor widgets when Elementor is ready
 */
function metal_prices_pro_elementor_init() {
    // Check if Elementor is loaded
    if (!did_action('elementor/loaded')) {
        return;
    }

    // Load our Elementor extension
    $elementor_file = METAL_PRICES_PRO_PLUGIN_DIR . 'includes/class-elementor-widgets.php';
    if (file_exists($elementor_file)) {
        require_once $elementor_file;
    }
}

// Hook into Elementor - wait for it to be loaded
add_action('plugins_loaded', 'metal_prices_pro_elementor_init', 20);

/**
 * Add admin menu
 */
function metal_prices_pro_admin_menu() {
    // Main menu page
    add_menu_page(
        'Metal Prices Pro',
        'Metal Prices Pro',
        'manage_options',
        'metal-prices-pro',
        'metal_prices_pro_dashboard_page',
        'dashicons-chart-line',
        30
    );
    
    // Settings submenu
    add_submenu_page(
        'metal-prices-pro',
        'Settings',
        'Settings',
        'manage_options',
        'metal-prices-pro-settings',
        'metal_prices_pro_settings_page'
    );
    
    // Help submenu
    add_submenu_page(
        'metal-prices-pro',
        'Help & Shortcodes',
        'Help & Shortcodes',
        'manage_options',
        'metal-prices-pro-help',
        'metal_prices_pro_help_page'
    );
}

add_action('admin_menu', 'metal_prices_pro_admin_menu');

/**
 * Dashboard page callback
 */
function metal_prices_pro_dashboard_page() {
    $dashboard_file = METAL_PRICES_PRO_PLUGIN_DIR . 'admin/views/dashboard.php';
    
    if (file_exists($dashboard_file)) {
        include $dashboard_file;
    } else {
        echo '<div class="wrap">';
        echo '<h1>Metal Prices Pro Dashboard</h1>';
        echo '<div class="notice notice-error"><p>Dashboard file not found at: ' . esc_html($dashboard_file) . '</p></div>';
        echo '<p>Please ensure the dashboard.php file exists in the admin/views folder.</p>';
        echo '</div>';
    }
}

/**
 * Settings page callback
 */
function metal_prices_pro_settings_page() {
    $settings_file = METAL_PRICES_PRO_PLUGIN_DIR . 'admin/views/settings.php';
    
    if (file_exists($settings_file)) {
        include $settings_file;
    } else {
        echo '<div class="wrap">';
        echo '<h1>Metal Prices Pro Settings</h1>';
        echo '<div class="notice notice-error"><p>Settings file not found at: ' . esc_html($settings_file) . '</p></div>';
        echo '<p>Please ensure the settings.php file exists in the admin/views folder.</p>';
        echo '</div>';
    }
}

/**
 * Help page callback
 */
function metal_prices_pro_help_page() {
    $help_file = METAL_PRICES_PRO_PLUGIN_DIR . 'admin/views/help.php';
    
    if (file_exists($help_file)) {
        include $help_file;
    } else {
        // Create a basic help page if file doesn't exist
        echo '<div class="wrap">';
        echo '<h1>Metal Prices Pro - Help & Shortcodes</h1>';
        
        echo '<h2>Available Shortcodes</h2>';
        echo '<table class="widefat striped">';
        echo '<thead><tr><th>Shortcode</th><th>Description</th><th>Example</th></tr></thead>';
        echo '<tbody>';
        echo '<tr><td><code>[metal_prices]</code></td><td>Display all metal prices</td><td><code>[metal_prices]</code></td></tr>';
        echo '<tr><td><code>[metal_prices layout="cards"]</code></td><td>Display prices in card format</td><td><code>[metal_prices layout="cards"]</code></td></tr>';
        echo '<tr><td><code>[metal_prices layout="grid"]</code></td><td>Display prices in grid format</td><td><code>[metal_prices layout="grid"]</code></td></tr>';
        echo '<tr><td><code>[metal_prices metal="gold"]</code></td><td>Display only gold price</td><td><code>[metal_prices metal="gold"]</code></td></tr>';
        echo '<tr><td><code>[metal_calculator]</code></td><td>Display metal calculator</td><td><code>[metal_calculator]</code></td></tr>';
        echo '</tbody>';
        echo '</table>';
        
        echo '<h2>Individual Price Shortcodes</h2>';
        echo '<table class="widefat striped">';
        echo '<thead><tr><th>Shortcode</th><th>Description</th></tr></thead>';
        echo '<tbody>';
        echo '<tr><td><code>[gold_18_carat]</code></td><td>18 Carat gold price per gram</td></tr>';
        echo '<tr><td><code>[gold_krugerrand]</code></td><td>Krugerrand price per coin</td></tr>';
        echo '<tr><td><code>[silver_925]</code></td><td>Sterling silver price per gram</td></tr>';
        echo '<tr><td><code>[gold_spot_price]</code></td><td>Gold spot price per troy oz</td></tr>';
        echo '</tbody>';
        echo '</table>';
        
        echo '<h2>Elementor Widgets</h2>';
        if (did_action('elementor/loaded')) {
            echo '<p style="color: green;">✅ Elementor is active! You can use our custom widgets in the "Metal Prices Pro" category.</p>';
        } else {
            echo '<p style="color: orange;">ℹ️ Install Elementor to use our custom widgets for even better displays!</p>';
        }
        
        echo '</div>';
    }
}

/**
 * Enqueue admin styles
 */
function metal_prices_pro_admin_scripts($hook) {
    if (strpos($hook, 'metal-prices-pro') !== false) {
        wp_enqueue_style('dashicons');
        
        // Add inline CSS for metal icons
        wp_add_inline_style('dashicons', '
            .admin-metal-icon,
            .help-metal-icon,
            .category-icon,
            .metal-icon {
                width: 100px !important;
                height: 100px !important;
                object-fit: contain !important;
                max-width: 100px !important;
                max-height: 100px !important;
                display: block !important;
                margin: 0 0 15px 0 !important;
                border-radius: 4px;
                float: none !important;
                clear: both;
            }
            
            .admin-metal-card {
                text-align: left;
                padding: 20px;
                display: flex;
                flex-direction: column;
            }
            
            .help-metal-item {
                text-align: left;
                display: flex;
                flex-direction: column;
            }
            
            .price-category {
                display: flex;
                flex-direction: column;
            }
            
            .price-category h3 {
                display: flex;
                align-items: center;
                gap: 15px;
                margin-top: 10px;
            }
            
            .admin-metal-card h3,
            .help-metal-item h4 {
                margin-top: 0;
                margin-bottom: 10px;
            }
        ');
    }
}

add_action('admin_enqueue_scripts', 'metal_prices_pro_admin_scripts');

/**
 * Add plugin action links
 */
function metal_prices_pro_action_links($links) {
    $action_links = array(
        'settings' => '<a href="' . admin_url('admin.php?page=metal-prices-pro-settings') . '">Settings</a>',
        'help' => '<a href="' . admin_url('admin.php?page=metal-prices-pro-help') . '">Help</a>',
    );
    return array_merge($action_links, $links);
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'metal_prices_pro_action_links');
