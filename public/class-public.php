<?php

class Metal_Prices_Pro_Public {
    
    private $plugin_name;
    private $version;
    
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    public function enqueue_styles() {
        // Only enqueue if file exists
        $css_file = METAL_PRICES_PRO_PLUGIN_DIR . 'public/css/public.css';
        if (file_exists($css_file)) {
            wp_enqueue_style($this->plugin_name, METAL_PRICES_PRO_PLUGIN_URL . 'public/css/public.css', array(), $this->version, 'all');
        }
    }
    
    public function enqueue_scripts() {
        // Only enqueue if file exists
        $js_file = METAL_PRICES_PRO_PLUGIN_DIR . 'public/js/public.js';
        if (file_exists($js_file)) {
            wp_enqueue_script($this->plugin_name, METAL_PRICES_PRO_PLUGIN_URL . 'public/js/public.js', array('jquery'), $this->version, false);
            
            wp_localize_script($this->plugin_name, 'metal_prices_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('metal_prices_nonce')
            ));
        }
    }
}