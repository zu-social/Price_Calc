<?php

class Metal_Prices_Pro_Deactivator {
    
    public static function deactivate() {
        // Clear scheduled events
        wp_clear_scheduled_hook('metal_prices_pro_update_prices');
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
}