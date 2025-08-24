<?php
if (!defined('ABSPATH')) {
    exit;
}

class Metal_Calculator_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'metal-calculator';
    }

    public function get_title() {
        return esc_html__('Metal Price Calculator', 'metal-prices-pro');
    }

    public function get_icon() {
        return 'eicon-calculator';
    }

    public function get_categories() {
        return array('metal-prices-pro');
    }

    public function get_keywords() {
        return array('metal', 'calculator', 'price', 'gold', 'silver');
    }

    protected function register_controls() {
        $this->register_style_controls();
        $this->start_controls_section(
            'settings_section',
            array(
                'label' => esc_html__('Settings', 'metal-prices-pro'),
            )
        );

        $this->add_control(
            'default_metal',
            array(
                'label' => esc_html__('Default Metal', 'metal-prices-pro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'gold' => esc_html__('Gold', 'metal-prices-pro'),
                    'silver' => esc_html__('Silver', 'metal-prices-pro'),
                ),
                'default' => 'gold',
            )
        );

        $this->end_controls_section();
    }

    public function register_style_controls() {
        $this->start_controls_section(
            'style_section',
            array(
                'label' => esc_html__('Style', 'metal-prices-pro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'background_color',
            array(
                'label' => esc_html__('Background Color', 'metal-prices-pro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
            )
        );

        $this->end_controls_section();
    }

    public function enqueue_metal_calculator_styles() {
        wp_enqueue_style('metal-calculator-styles', plugins_url('css/metal-calculator.css', __FILE__));
    }

    public function enqueue_metal_calculator_scripts() {
        wp_enqueue_script('metal-calculator-scripts', plugins_url('js/metal-calculator.js', __FILE__), array('jquery'), '1.0', true);
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if (class_exists('Metal_Prices_Pro_Shortcodes')) {
            $shortcodes = new Metal_Prices_Pro_Shortcodes();

            $atts = array(
                'metal' => $settings['default_metal']
            );

            echo $shortcodes->display_calculator($atts);
        } else {
            echo '<div class="elementor-alert elementor-alert-warning">Metal Prices Pro calculator not available.</div>';
        }
    }
}