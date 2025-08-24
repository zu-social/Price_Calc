<?php

class Metal_Prices_Pro_Admin {
    
    private $plugin_name;
    private $version;
    
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, METAL_PRICES_PRO_PLUGIN_URL . 'admin/css/admin.css', array(), $this->version, 'all');
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, METAL_PRICES_PRO_PLUGIN_URL . 'admin/js/admin.js', array('jquery'), $this->version, false);
        
        // Localize script for AJAX
        wp_localize_script($this->plugin_name, 'metal_prices_admin', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('metal_prices_nonce')
        ));
    }
    
    public function add_admin_menu() {
        // Main menu
        add_menu_page(
            'Metal Prices Pro',
            'Metal Prices',
            'manage_options',
            'metal-prices-pro',
            array($this, 'display_dashboard_page'),
            'dashicons-chart-line',
            30
        );
        
        // Dashboard submenu (rename the first item)
        add_submenu_page(
            'metal-prices-pro',
            'Dashboard',
            'Dashboard',
            'manage_options',
            'metal-prices-pro',
            array($this, 'display_dashboard_page')
        );
        
        // Settings submenu
        add_submenu_page(
            'metal-prices-pro',
            'Settings',
            'Settings',
            'manage_options',
            'metal-prices-pro-settings',
            array($this, 'display_settings_page')
        );
    }
    
    public function display_dashboard_page() {
        require_once METAL_PRICES_PRO_PLUGIN_DIR . 'admin/views/dashboard.php';
    }
    
    public function display_settings_page() {
        require_once METAL_PRICES_PRO_PLUGIN_DIR . 'admin/views/settings.php';
    }
}