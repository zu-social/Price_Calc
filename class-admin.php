<?php

class Metal_Prices_Pro_Admin {
    
    private $plugin_name;
    private $version;
    
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    public function enqueue_styles() {
        // Only enqueue if file exists
        $css_file = METAL_PRICES_PRO_PLUGIN_DIR . 'admin/css/admin.css';
        if (file_exists($css_file)) {
            wp_enqueue_style($this->plugin_name, METAL_PRICES_PRO_PLUGIN_URL . 'admin/css/admin.css', array(), $this->version, 'all');
        }
    }
    
    public function enqueue_scripts() {
        // Only enqueue if file exists
        $js_file = METAL_PRICES_PRO_PLUGIN_DIR . 'admin/js/admin.js';
        if (file_exists($js_file)) {
            wp_enqueue_script($this->plugin_name, METAL_PRICES_PRO_PLUGIN_URL . 'admin/js/admin.js', array('jquery'), $this->version, false);
        }
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'Metal Prices Pro',
            'Metal Prices',
            'manage_options',
            'metal-prices-pro',
            array($this, 'display_dashboard_page'),
            'dashicons-chart-line',
            30
        );
    }
    
    public function display_dashboard_page() {
        echo '<div class="wrap">';
        echo '<h1>Metal Prices Pro Dashboard</h1>';
        echo '<p>Welcome to Metal Prices Pro plugin.</p>';
        
        // Check if view file exists
        $view_file = METAL_PRICES_PRO_PLUGIN_DIR . 'admin/views/dashboard.php';
        if (file_exists($view_file)) {
            include_once $view_file;
        }
        
        echo '</div>';
    }
}