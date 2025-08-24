<?php
if (!defined('ABSPATH')) {
    exit;
}

class Metal_Prices_Pro_API_Handler {
    
    private $api_url = 'https://api.metals.live/v1/spot';
    private $cache_duration = 300; // 5 minutes
    
    public function __construct() {
        // Schedule daily price refresh
        add_action('wp', array($this, 'schedule_daily_refresh'));
        add_action('metal_prices_daily_refresh', array($this, 'daily_price_refresh'));
    }
    
    /**
     * Schedule the daily refresh event
     */
    public function schedule_daily_refresh() {
        if (!wp_next_scheduled('metal_prices_daily_refresh')) {
            // Calculate next 9:30 AM
            $next_930am = $this->get_next_930am();
            wp_schedule_event($next_930am, 'daily', 'metal_prices_daily_refresh');
        }
    }
    
    /**
     * Get the timestamp for the next 9:30 AM
     */
    private function get_next_930am() {
        $timezone = wp_timezone();
        $now = new DateTime('now', $timezone);
        $target = new DateTime('today 9:30', $timezone);
        
        // If it's already past 9:30 AM today, schedule for tomorrow
        if ($now > $target) {
            $target->add(new DateInterval('P1D'));
        }
        
        return $target->getTimestamp();
    }
    
    /**
     * Daily refresh callback - clears cache and gets fresh prices
     */
    public function daily_price_refresh() {
        // Clear the cache
        delete_transient('metal_prices_pro_cached_prices');
        
        // Get fresh prices (this will cache them automatically)
        $this->get_cached_prices();
        
        // Log the refresh
        error_log('Metal Prices Pro: Daily price refresh completed at ' . current_time('Y-m-d H:i:s'));
        
        // Update last refresh time
        update_option('metal_prices_pro_last_daily_refresh', time());
    }
    
    /**
     * Manually trigger daily refresh (for testing)
     */
    public function trigger_manual_refresh() {
        $this->daily_price_refresh();
        return true;
    }
    
    /**
     * Get info about the scheduled refresh
     */
    public function get_refresh_info() {
        $next_scheduled = wp_next_scheduled('metal_prices_daily_refresh');
        $last_refresh = get_option('metal_prices_pro_last_daily_refresh', 0);
        
        return array(
            'next_refresh' => $next_scheduled,
            'last_refresh' => $last_refresh,
            'is_scheduled' => $next_scheduled !== false
        );
    }
    
    // ... rest of your existing methods stay the same ...
    
    public function get_current_prices() {
        $response = wp_remote_get($this->api_url, array(
            'timeout' => 30,
            'headers' => array(
                'User-Agent' => 'Metal Prices Pro WordPress Plugin'
            )
        ));
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code !== 200) {
            return false;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }
        
        // Convert USD to GBP (simplified)
        $usd_to_gbp = 0.79; // You can make this dynamic later
        
        $prices = array();
        foreach ($data as $metal_data) {
            if (isset($metal_data['metal']) && isset($metal_data['price'])) {
                $metal = strtolower($metal_data['metal']);
                $price_usd = floatval($metal_data['price']);
                $prices[$metal] = round($price_usd * $usd_to_gbp, 2);
            }
        }
        
        return $prices;
    }
    
    public function get_cached_prices() {
        $cached_prices = get_transient('metal_prices_pro_cached_prices');
        
        if ($cached_prices === false) {
            $prices = $this->get_current_prices();
            
            if ($prices === false) {
                // Use default prices if API fails
                $prices = array(
                    'gold' => 2461.90,
                    'silver' => 28.05,
                    'platinum' => 990.25,
                    'palladium' => 822.89
                );
            }
            
            set_transient('metal_prices_pro_cached_prices', $prices, $this->cache_duration);
            update_option('metal_prices_pro_last_updated', time());
            
            return $prices;
        }
        
        return $cached_prices;
    }
    
    public function refresh_prices() {
        delete_transient('metal_prices_pro_cached_prices');
        return $this->get_cached_prices();
    }
}