<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if Elementor is active
 */
if (!did_action('elementor/loaded')) {
    return;
}

/**
 * Main Elementor Integration Class
 */
class Metal_Prices_Pro_Elementor {
    
    public function __construct() {
        add_action('elementor/widgets/widgets_registered', array($this, 'register_widgets'));
        add_action('elementor/elements/categories_registered', array($this, 'add_widget_categories'));
    }
    
    /**
     * Add widget category
     */
    public function add_widget_categories($elements_manager) {
        $elements_manager->add_category(
            'metal-prices-pro',
            array(
                'title' => esc_html__('Metal Prices Pro', 'metal-prices-pro'),
                'icon' => 'fa fa-coins',
            )
        );
    }
    
    /**
     * Register widgets
     */
    public function register_widgets() {
        // Include widget files
        require_once METAL_PRICES_PRO_PLUGIN_DIR . 'includes/elementor-widgets/metal-cards-widget.php';
        require_once METAL_PRICES_PRO_PLUGIN_DIR . 'includes/elementor-widgets/metal-calculator-widget.php';
        require_once METAL_PRICES_PRO_PLUGIN_DIR . 'includes/elementor-widgets/individual-price-widget.php';
        
        // Register widgets
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Metal_Prices_Cards_Widget());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Metal_Calculator_Widget());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Individual_Metal_Price_Widget());
    }
}

// Initialize
new Metal_Prices_Pro_Elementor();