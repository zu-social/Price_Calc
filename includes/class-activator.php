<?php

class Metal_Prices_Pro_Activator {
    
    public static function activate() {
        // Create database tables
        self::create_tables();
        
        // Set default options
        self::set_default_options();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    private static function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $table_name = $wpdb->prefix . 'metal_prices';
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            metal_type varchar(50) NOT NULL,
            price decimal(10,2) NOT NULL,
            currency varchar(10) NOT NULL,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    private static function set_default_options() {
        add_option('metal_prices_pro_api_key', '');
        add_option('metal_prices_pro_update_interval', 3600);
        add_option('metal_prices_pro_display_currency', 'USD');
    }
}