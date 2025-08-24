<?php
if (!defined('ABSPATH')) {
    exit;
}

class Individual_Metal_Price_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'individual-metal-price';
    }
    
    public function get_title() {
        return esc_html__('Individual Metal Price', 'metal-prices-pro');
    }
    
    public function get_icon() {
        return 'eicon-number-field';
    }
    
    public function get_categories() {
        return array('metal-prices-pro');
    }
    
    public function get_keywords() {
        return array('metal', 'price', 'individual', 'gold', 'silver', 'single');
    }
    
    protected function register_controls() {
        
        $this->start_controls_section(
            'content_section',
            array(
                'label' => esc_html__('Price Settings', 'metal-prices-pro'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );
        
        $this->add_control(
            'price_type',
            array(
                'label' => esc_html__('Price Type', 'metal-prices-pro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'gold_spot_price' => esc_html__('Gold Spot Price', 'metal-prices-pro'),
                    'gold_9_carat' => esc_html__('Gold 9 Carat', 'metal-prices-pro'),
                    'gold_14_carat' => esc_html__('Gold 14 Carat', 'metal-prices-pro'),
                    'gold_18_carat' => esc_html__('Gold 18 Carat', 'metal-prices-pro'),
                    'gold_22_carat' => esc_html__('Gold 22 Carat', 'metal-prices-pro'),
                    'gold_krugerrand' => esc_html__('Gold Krugerrand', 'metal-prices-pro'),
                    'gold_full_sovereign' => esc_html__('Gold Full Sovereign', 'metal-prices-pro'),
                    'silver_spot_price' => esc_html__('Silver Spot Price', 'metal-prices-pro'),
                    'silver_pure' => esc_html__('Pure Silver', 'metal-prices-pro'),
                    'silver_925' => esc_html__('Sterling Silver (925)', 'metal-prices-pro'),
                    'platinum_spot_price' => esc_html__('Platinum Spot Price', 'metal-prices-pro'),
                    'platinum_90' => esc_html__('90% Platinum', 'metal-prices-pro'),
                    'palladium_spot_price' => esc_html__('Palladium Spot Price', 'metal-prices-pro'),
                ),
                'default' => 'gold_18_carat',
            )
        );
        
        $this->add_control(
            'show_label',
            array(
                'label' => esc_html__('Show Label', 'metal-prices-pro'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'metal-prices-pro'),
                'label_off' => esc_html__('Hide', 'metal-prices-pro'),
                'return_value' => 'yes',
                'default' => 'yes',
            )
        );
        
        $this->add_control(
            'custom_label',
            array(
                'label' => esc_html__('Custom Label', 'metal-prices-pro'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => esc_html__('Leave empty for default label', 'metal-prices-pro'),
                'condition' => array(
                    'show_label' => 'yes',
                ),
            )
        );
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section(
            'style_section',
            array(
                'label' => esc_html__('Price Style', 'metal-prices-pro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name' => 'price_typography',
                'label' => esc_html__('Price Typography', 'metal-prices-pro'),
                'selector' => '{{WRAPPER}} .individual-metal-price',
            )
        );
        
        $this->add_control(
            'price_color',
            array(
                'label' => esc_html__('Price Color', 'metal-prices-pro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .individual-metal-price' => 'color: {{VALUE}};',
                ),
            )
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        if (class_exists('Metal_Prices_Pro_Shortcodes')) {
            $shortcodes = new Metal_Prices_Pro_Shortcodes();
            $price_type = $settings['price_type'];
            
            // Get the price using the appropriate method
            if (method_exists($shortcodes, $price_type)) {
                $price = $shortcodes->$price_type(array());
                
                echo '<div class="individual-metal-price-wrapper">';
                
                if ($settings['show_label'] === 'yes') {
                    $label = !empty($settings['custom_label']) ? $settings['custom_label'] : $this->get_default_label($price_type);
                    echo '<span class="metal-price-label">' . esc_html($label) . ': </span>';
                }
                
                echo '<span class="individual-metal-price">' . $price . '</span>';
                echo '</div>';
            } else {
                echo '<div class="elementor-alert elementor-alert-warning">Price type not found.</div>';
            }
        } else {
            echo '<div class="elementor-alert elementor-alert-warning">Metal Prices Pro not available.</div>';
        }
    }
    
    private function get_default_label($price_type) {
        $labels = array(
            'gold_spot_price' => 'Gold Spot Price',
            'gold_9_carat' => '9 Carat Gold',
            'gold_14_carat' => '14 Carat Gold',
            'gold_18_carat' => '18 Carat Gold',
            'gold_22_carat' => '22 Carat Gold',
            'gold_krugerrand' => 'Krugerrand',
            'gold_full_sovereign' => 'Full Sovereign',
            'silver_spot_price' => 'Silver Spot Price',
            'silver_pure' => 'Pure Silver',
            'silver_925' => 'Sterling Silver',
            'platinum_spot_price' => 'Platinum Spot Price',
            'platinum_90' => '90% Platinum',
            'palladium_spot_price' => 'Palladium Spot Price',
        );
        
        return isset($labels[$price_type]) ? $labels[$price_type] : ucfirst(str_replace('_', ' ', $price_type));
    }
}