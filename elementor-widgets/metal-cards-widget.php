<?php
if (!defined('ABSPATH')) {
    exit;
}

class Metal_Prices_Cards_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'metal-prices-cards';
    }
    
    public function get_title() {
        return esc_html__('Metal Prices Cards', 'metal-prices-pro');
    }
    
    public function get_icon() {
        return 'eicon-gallery-grid';
    }
    
    public function get_categories() {
        return array('metal-prices-pro');
    }
    
    public function get_keywords() {
        return array('metal', 'prices', 'gold', 'silver', 'cards');
    }
    
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            array(
                'label' => esc_html__('Content', 'metal-prices-pro'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );
        
        $this->add_control(
            'metals_to_show',
            array(
                'label' => esc_html__('Metals to Display', 'metal-prices-pro'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => array(
                    'gold' => esc_html__('Gold', 'metal-prices-pro'),
                    'silver' => esc_html__('Silver', 'metal-prices-pro'),
                    'platinum' => esc_html__('Platinum', 'metal-prices-pro'),
                    'palladium' => esc_html__('Palladium', 'metal-prices-pro'),
                ),
                'default' => array('gold', 'silver', 'platinum', 'palladium'),
            )
        );
        
        $this->add_responsive_control(
            'columns',
            array(
                'label' => esc_html__('Columns', 'metal-prices-pro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '4',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .metal-prices-cards' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ),
            )
        );
        
        $this->add_control(
            'show_detailed_prices',
            array(
                'label' => esc_html__('Show Detailed Prices', 'metal-prices-pro'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'metal-prices-pro'),
                'label_off' => esc_html__('Hide', 'metal-prices-pro'),
                'return_value' => 'yes',
                'default' => 'yes',
            )
        );
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section(
            'style_section',
            array(
                'label' => esc_html__('Card Style', 'metal-prices-pro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );
        
        $this->add_responsive_control(
            'card_gap',
            array(
                'label' => esc_html__('Gap Between Cards', 'metal-prices-pro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ),
                ),
                'default' => array(
                    'unit' => 'px',
                    'size' => 20,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .metal-prices-cards' => 'gap: {{SIZE}}{{UNIT}};',
                ),
            )
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Get the shortcode class
        if (class_exists('Metal_Prices_Pro_Shortcodes')) {
            $shortcodes = new Metal_Prices_Pro_Shortcodes();
            
            // Build the metals parameter
            $metals = !empty($settings['metals_to_show']) ? implode(',', $settings['metals_to_show']) : 'all';
            
            // Generate the shortcode attributes
            $atts = array(
                'layout' => 'cards',
                'metal' => $metals === 'gold,silver,platinum,palladium' ? 'all' : $metals
            );
            
            // Display the cards
            echo $shortcodes->display_metal_prices($atts);
        } else {
            echo '<div class="elementor-alert elementor-alert-warning">Metal Prices Pro shortcodes not available.</div>';
        }
    }
    
    protected function content_template() {
        ?>
        <div class="metal-prices-cards">
            <div class="metal-card gold-card">
                <span class="metal-icon">🥇</span>
                <h3>Gold</h3>
                <div class="price-label">Spot Price</div>
                <div class="spot-price">£2,461.90</div>
                <div class="detailed-prices">
                    <div><span>9 Carat</span><span>£29.68</span></div>
                    <div><span>18 Carat</span><span>£59.36</span></div>
                </div>
            </div>
        </div>
        <?php
    }
}