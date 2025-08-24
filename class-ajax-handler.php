<?php

class Metal_Prices_Pro_Ajax_Handler {
    
    public function __construct() {
        // Admin AJAX handlers
        add_action('wp_ajax_refresh_metal_prices', array($this, 'refresh_metal_prices'));
        add_action('wp_ajax_test_api_connection', array($this, 'test_api_connection'));
        
        // Public AJAX handlers for calculator
        add_action('wp_ajax_calculate_metal_value', array($this, 'calculate_metal_value'));
        add_action('wp_ajax_nopriv_calculate_metal_value', array($this, 'calculate_metal_value'));
    }
    
    public function refresh_metal_prices() {
        // Check nonce for security
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'metal_prices_nonce')) {
            wp_send_json_error('Invalid nonce');
            return;
        }
        
        // Check user permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
            return;
        }
        
        // Load API handler if not already loaded
        $plugin_dir = plugin_dir_path(dirname(__FILE__));
        if (!class_exists('Metal_Prices_Pro_API_Handler')) {
            require_once $plugin_dir . 'includes/class-api-handler.php';
        }
        
        // Clear the cache first
        delete_transient('metal_prices_cache');
        
        // Get fresh prices from API
        $api_handler = new Metal_Prices_Pro_API_Handler();
        $prices = $api_handler->get_metal_prices();
        
        if (!empty($prices)) {
            wp_send_json_success(array(
                'message' => 'Prices refreshed successfully',
                'prices' => $prices
            ));
        } else {
            wp_send_json_error('Failed to fetch prices from API');
        }
    }
    
    public function test_api_connection() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'metal_prices_admin_nonce')) {
            wp_send_json_error('Invalid nonce');
            return;
        }
        
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
            return;
        }
        
        $api_key = get_option('metal_prices_pro_api_key', '739661cfd3f17d7c2a6bfb743a9490b5');
        
        // Test API connection
        $api_url = 'https://api.metalpriceapi.com/v1/latest';
        $api_url .= '?api_key=' . $api_key;
        $api_url .= '&base=GBP';
        $api_url .= '&currencies=XAU';
        
        $response = wp_remote_get($api_url, array(
            'timeout' => 15,
            'headers' => array(
                'Accept' => 'application/json'
            )
        ));
        
        if (is_wp_error($response)) {
            wp_send_json_error('Connection failed: ' . $response->get_error_message());
            return;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (!empty($data) && isset($data['success']) && $data['success'] === true) {
            wp_send_json_success('API connection successful!');
        } else {
            $error_message = isset($data['error']) ? $data['error']['info'] : 'Unknown API error';
            wp_send_json_error('API error: ' . $error_message);
        }
    }
    
    public function calculate_metal_value() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'metal_calculator_nonce')) {
            wp_send_json_error('Invalid nonce');
            return;
        }
        
        $metal = sanitize_text_field($_POST['metal']);
        $weight = floatval($_POST['weight']);
        $unit = sanitize_text_field($_POST['unit']);
        $purity = floatval($_POST['purity']) / 100;
        
        // Load API handler if needed
        $plugin_dir = plugin_dir_path(dirname(__FILE__));
        if (!class_exists('Metal_Prices_Pro_API_Handler')) {
            require_once $plugin_dir . 'includes/class-api-handler.php';
        }
        
        // Get current prices
        $api_handler = new Metal_Prices_Pro_API_Handler();
        $prices = $api_handler->get_cached_prices();
        
        // Find the price for the selected metal
        $price_per_oz = 0;
        foreach ($prices as $price_data) {
            if ($price_data['metal_type'] === $metal) {
                $price_per_oz = floatval($price_data['price']);
                break;
            }
        }
        
        if ($price_per_oz === 0) {
            wp_send_json_error('Price not found for selected metal');
            return;
        }
        
        // Convert weight to troy ounces
        $weight_in_oz = $this->convert_to_troy_ounces($weight, $unit);
        
        // Calculate value
        $total_value = $weight_in_oz * $price_per_oz * $purity;
        
        wp_send_json_success(array(
            'value' => $total_value,
            'formatted' => '£' . number_format($total_value, 2),
            'price_per_oz' => $price_per_oz,
            'weight_in_oz' => $weight_in_oz,
            'metal' => ucfirst($metal),
            'purity_percentage' => ($purity * 100) . '%'
        ));
    }
    
    private function convert_to_troy_ounces($weight, $unit) {
        switch ($unit) {
            case 'oz':
                return $weight; // Already in troy ounces
            case 'g':
                return $weight * 0.0321507; // Grams to troy ounces
            case 'kg':
                return $weight * 32.1507; // Kilograms to troy ounces
            case 'lb':
                return $weight * 14.5833; // Pounds to troy ounces
            case 'dwt':
                return $weight * 0.05; // Pennyweight to troy ounces
            case 'grain':
                return $weight * 0.00208333; // Grains to troy ounces
            default:
                return $weight;
        }
    }
}
