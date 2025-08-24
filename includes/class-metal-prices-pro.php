<?php

class Metal_Prices_Pro {
    
    protected $loader;
    protected $plugin_name;
    protected $version;
    
    public function __construct() {
        $this->version = METAL_PRICES_PRO_VERSION;
        $this->plugin_name = 'metal-prices-pro';
        
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->init_shortcodes();
        $this->init_ajax_handlers();
    }
    
    private function load_dependencies() {
        require_once METAL_PRICES_PRO_PLUGIN_DIR . 'includes/class-loader.php';
        
        $files_to_load = array(
            'includes/class-api-handler.php',
            'includes/class-ajax-handler.php',
            'includes/class-shortcodes.php',
            'admin/class-admin.php',
            'public/class-public.php'
        );
        
        foreach ($files_to_load as $file) {
            $file_path = METAL_PRICES_PRO_PLUGIN_DIR . $file;
            if (file_exists($file_path)) {
                require_once $file_path;
            }
        }
        
        $this->loader = new Metal_Prices_Pro_Loader();
    }
    
    private function define_admin_hooks() {
        if (class_exists('Metal_Prices_Pro_Admin')) {
            $plugin_admin = new Metal_Prices_Pro_Admin($this->get_plugin_name(), $this->get_version());
            
            $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
            $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
            $this->loader->add_action('admin_menu', $plugin_admin, 'add_admin_menu');
        }
    }
    
    private function define_public_hooks() {
        if (class_exists('Metal_Prices_Pro_Public')) {
            $plugin_public = new Metal_Prices_Pro_Public($this->get_plugin_name(), $this->get_version());
            
            $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
            $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        }
    }
    
    private function init_shortcodes() {
        if (class_exists('Metal_Prices_Pro_Shortcodes')) {
            $shortcodes = new Metal_Prices_Pro_Shortcodes();
            add_shortcode('metal_prices', array($shortcodes, 'display_prices'));
            add_shortcode('metal_calculator', array($shortcodes, 'display_calculator'));
        }
    }
    
    private function init_ajax_handlers() {
        if (class_exists('Metal_Prices_Pro_Ajax_Handler')) {
            new Metal_Prices_Pro_Ajax_Handler();
        }
    }
    
    public function run() {
        $this->loader->run();
    }
    
    public function get_plugin_name() {
        return $this->plugin_name;
    }
    
    public function get_version() {
        return $this->version;
    }
}