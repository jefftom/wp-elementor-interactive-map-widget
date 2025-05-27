<?php
/**
 * Interactive Map Widget for Elementor - Complete Rebuild
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Interactive Map Widget Class
 */
class Interactive_Map_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     */
    public function get_name() {
        return 'interactive_map';
    }

    /**
     * Get widget title.
     */
    public function get_title() {
        return esc_html__('Interactive Map', 'interactive-map-widget');
    }

    /**
     * Get widget icon.
     */
    public function get_icon() {
        return 'eicon-google-maps';
    }

    /**
     * Get widget categories.
     */
    public function get_categories() {
        return ['interactive-widgets'];
    }

    /**
     * Get widget keywords.
     */
    public function get_keywords() {
        return ['map', 'location', 'interactive', 'hotspot', 'google maps'];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls() {

        // ===== CONTENT SECTIONS =====

        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'interactive-map-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'section_title',
            [
                'label' => esc_html__('Section Title', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Your guide to Paradise', 'interactive-map-widget'),
                'placeholder' => esc_html__('Type your title here', 'interactive-map-widget'),
            ]
        );

        $this->add_control(
            'google_maps_api_key',
            [
                'label' => esc_html__('Google Maps API Key', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Enter your Google Maps API key', 'interactive-map-widget'),
                'description' => esc_html__('Get your API key from Google Cloud Console. Required for the map to work.', 'interactive-map-widget'),
            ]
        );

        $this->end_controls_section();

        // Map Settings Section
        $this->start_controls_section(
            'map_settings_section',
            [
                'label' => esc_html__('Map Settings', 'interactive-map-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'map_center_lat',
            [
                'label' => esc_html__('Map Center Latitude', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 37.4419,
                'step' => 0.000001,
                'description' => esc_html__('Default center position of the map', 'interactive-map-widget'),
            ]
        );

        $this->add_control(
            'map_center_lng',
            [
                'label' => esc_html__('Map Center Longitude', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 25.3656,
                'step' => 0.000001,
                'description' => esc_html__('Default center position of the map', 'interactive-map-widget'),
            ]
        );

        $this->add_control(
            'map_zoom',
            [
                'label' => esc_html__('Default Zoom Level', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
            ]
        );

        $this->add_control(
            'location_zoom',
            [
                'label' => esc_html__('Location Focus Zoom', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 8,
                        'max' => 20,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'description' => esc_html__('Zoom level when clicking on a location', 'interactive-map-widget'),
            ]
        );

        $this->end_controls_section();

        // Locations Section
        $this->start_controls_section(
            'locations_section',
            [
                'label' => esc_html__('Locations', 'interactive-map-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'location_name',
            [
                'label' => esc_html__('Location Name', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Location Name', 'interactive-map-widget'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'location_lat',
            [
                'label' => esc_html__('Latitude', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'step' => 0.000001,
                'description' => esc_html__('Use Google Maps to find coordinates', 'interactive-map-widget'),
            ]
        );

        $repeater->add_control(
            'location_lng',
            [
                'label' => esc_html__('Longitude', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'step' => 0.000001,
                'description' => esc_html__('Use Google Maps to find coordinates', 'interactive-map-widget'),
            ]
        );

        $repeater->add_control(
            'location_description',
            [
                'label' => esc_html__('Description', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 3,
                'placeholder' => esc_html__('Location description...', 'interactive-map-widget'),
            ]
        );

        $repeater->add_control(
            'marker_color',
            [
                'label' => esc_html__('Marker Color', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFD700',
                'description' => esc_html__('Custom color for this location marker', 'interactive-map-widget'),
            ]
        );

        $this->add_control(
            'locations_list',
            [
                'label' => esc_html__('Locations', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'location_name' => esc_html__('Villa Lemoni', 'interactive-map-widget'),
                        'location_lat' => 37.4419,
                        'location_lng' => 25.3656,
                        'location_description' => esc_html__('Beautiful villa with stunning sea views', 'interactive-map-widget'),
                        'marker_color' => '#FFD700',
                    ],
                    [
                        'location_name' => esc_html__('Egremni Beach', 'interactive-map-widget'),
                        'location_lat' => 37.4219,
                        'location_lng' => 25.3456,
                        'location_description' => esc_html__('One of the most beautiful beaches in the Mediterranean', 'interactive-map-widget'),
                        'marker_color' => '#FF6B35',
                    ],
                    [
                        'location_name' => esc_html__('Nydri Beach', 'interactive-map-widget'),
                        'location_lat' => 37.4319,
                        'location_lng' => 25.3556,
                        'location_description' => esc_html__('Popular beach destination with crystal clear waters', 'interactive-map-widget'),
                        'marker_color' => '#4ECDC4',
                    ],
                ],
                'title_field' => '{{{ location_name }}}',
            ]
        );

        $this->end_controls_section();

        // ===== STYLE SECTIONS =====

        // Title Style Section
        $this->start_controls_section(
            'title_style_section',
            [
                'label' => esc_html__('Title Style', 'interactive-map-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .interactive-map-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .interactive-map-title',
            ]
        );

        $this->add_responsive_control(
            'title_alignment',
            [
                'label' => esc_html__('Alignment', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'interactive-map-widget'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'interactive-map-widget'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'interactive-map-widget'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .interactive-map-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .interactive-map-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Map Colors Section
        $this->start_controls_section(
            'map_colors_section',
            [
                'label' => esc_html__('Map Colors', 'interactive-map-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'water_color',
            [
                'label' => esc_html__('Water Color', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#87CEEB',
                'description' => esc_html__('Color of water areas on the map', 'interactive-map-widget'),
            ]
        );

        $this->add_control(
            'land_color',
            [
                'label' => esc_html__('Land Color', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FAFAFA',
                'description' => esc_html__('Color of land areas on the map', 'interactive-map-widget'),
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label' => esc_html__('Border Color', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#D3D3D3',
                'description' => esc_html__('Color of country and region borders', 'interactive-map-widget'),
            ]
        );

        $this->end_controls_section();

        // Map Style Section
        $this->start_controls_section(
            'map_style_section',
            [
                'label' => esc_html__('Map Layout', 'interactive-map-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'map_height',
            [
                'label' => esc_html__('Map Height', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 800,
                        'step' => 10,
                    ],
                    'vh' => [
                        'min' => 20,
                        'max' => 100,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 500,
                ],
                'selectors' => [
                    '{{WRAPPER}} .map-container' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'map_width_ratio',
            [
                'label' => esc_html__('Map Width (%)', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 50,
                        'max' => 80,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 70,
                ],
                'description' => esc_html__('How much space the map takes up vs. the location list', 'interactive-map-widget'),
                'selectors' => [
                    '{{WRAPPER}} .map-section' => 'flex: 0 0 {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .locations-section' => 'flex: 1;',
                ],
            ]
        );

        $this->add_control(
            'container_gap',
            [
                'label' => esc_html__('Gap Between Map and List', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .interactive-map-container' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'map_border_radius',
            [
                'label' => esc_html__('Border Radius', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .map-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'map_box_shadow',
                'selector' => '{{WRAPPER}} .map-container',
            ]
        );

        $this->end_controls_section();

        // Locations Panel Style Section
        $this->start_controls_section(
            'locations_panel_style_section',
            [
                'label' => esc_html__('Locations Panel', 'interactive-map-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'panel_background',
            [
                'label' => esc_html__('Panel Background Color', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#F8F9FA',
                'description' => esc_html__('Background color for the entire locations panel', 'interactive-map-widget'),
                'selectors' => [
                    '{{WRAPPER}} .locations-section' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'panel_padding',
            [
                'label' => esc_html__('Panel Padding', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .locations-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'panel_border_radius',
            [
                'label' => esc_html__('Panel Border Radius', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .locations-section' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'panel_box_shadow',
                'selector' => '{{WRAPPER}} .locations-section',
            ]
        );

        $this->end_controls_section();

        // Location Items Style Section
        $this->start_controls_section(
            'location_items_style_section',
            [
                'label' => esc_html__('Location Items', 'interactive-map-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Normal State
        $this->add_control(
            'normal_state_heading',
            [
                'label' => esc_html__('Normal State', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'location_item_background',
            [
                'label' => esc_html__('Background Color', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .location-item' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'location_item_text_color',
            [
                'label' => esc_html__('Text Color', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .location-item' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .location-item h3' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .location-item p' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'location_item_border',
                'selector' => '{{WRAPPER}} .location-item',
            ]
        );

        // Hover State
        $this->add_control(
            'hover_state_heading',
            [
                'label' => esc_html__('Hover State', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'location_item_hover_bg',
            [
                'label' => esc_html__('Hover Background', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#F0F8FF',
                'selectors' => [
                    '{{WRAPPER}} .location-item:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'location_item_hover_text_color',
            [
                'label' => esc_html__('Hover Text Color', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#2C5AA0',
                'selectors' => [
                    '{{WRAPPER}} .location-item:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .location-item:hover h3' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .location-item:hover p' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'location_item_hover_border_color',
            [
                'label' => esc_html__('Hover Border Color', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .location-item:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        // Active State
        $this->add_control(
            'active_state_heading',
            [
                'label' => esc_html__('Active State', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'location_item_active_bg',
            [
                'label' => esc_html__('Active Background', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .location-item.active' => 'background-color: {{VALUE}} !important',
                ],
            ]
        );

        $this->add_control(
            'location_item_active_text_color',
            [
                'label' => esc_html__('Active Text Color', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .location-item.active' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} .location-item.active h3' => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} .location-item.active p' => 'color: {{VALUE}} !important',
                ],
            ]
        );

        $this->add_control(
            'location_item_active_border_color',
            [
                'label' => esc_html__('Active Border Color', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#005a87',
                'selectors' => [
                    '{{WRAPPER}} .location-item.active' => 'border-color: {{VALUE}} !important',
                ],
            ]
        );

        // Typography & Spacing
        $this->add_control(
            'typography_spacing_heading',
            [
                'label' => esc_html__('Typography & Spacing', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'location_title_typography',
                'label' => esc_html__('Title Typography', 'interactive-map-widget'),
                'selector' => '{{WRAPPER}} .location-item h3',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'location_description_typography',
                'label' => esc_html__('Description Typography', 'interactive-map-widget'),
                'selector' => '{{WRAPPER}} .location-item p',
            ]
        );

        $this->add_responsive_control(
            'location_item_padding',
            [
                'label' => esc_html__('Item Padding', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .location-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'location_item_border_radius',
            [
                'label' => esc_html__('Item Border Radius', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 8,
                    'right' => 8,
                    'bottom' => 8,
                    'left' => 8,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .location-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'locations_gap',
            [
                'label' => esc_html__('Gap Between Items', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .locations-list' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'location_item_box_shadow',
                'selector' => '{{WRAPPER}} .location-item',
            ]
        );

        $this->end_controls_section();

        // Mobile Layout Section
        $this->start_controls_section(
            'mobile_layout_section',
            [
                'label' => esc_html__('Mobile Layout', 'interactive-map-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mobile_layout',
            [
                'label' => esc_html__('Mobile Layout', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'column',
                'options' => [
                    'column' => esc_html__('Stack Vertically', 'interactive-map-widget'),
                    'row' => esc_html__('Side by Side', 'interactive-map-widget'),
                ],
                'selectors_dictionary' => [
                    'column' => 'flex-direction: column;',
                    'row' => 'flex-direction: row;',
                ],
                'selectors' => [
                    '(mobile){{WRAPPER}} .interactive-map-container' => '{{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'mobile_map_height',
            [
                'label' => esc_html__('Mobile Map Height', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 500,
                        'step' => 10,
                    ],
                    'vh' => [
                        'min' => 20,
                        'max' => 60,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 300,
                ],
                'selectors' => [
                    '(mobile){{WRAPPER}} .map-container' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_id = $this->get_id();
        
        // Safely get settings with fallbacks
        $locations_list = isset($settings['locations_list']) ? $settings['locations_list'] : [];
        $map_center_lat = isset($settings['map_center_lat']) ? floatval($settings['map_center_lat']) : 37.4419;
        $map_center_lng = isset($settings['map_center_lng']) ? floatval($settings['map_center_lng']) : 25.3656;
        $map_zoom = isset($settings['map_zoom']['size']) ? intval($settings['map_zoom']['size']) : 10;
        $location_zoom = isset($settings['location_zoom']['size']) ? intval($settings['location_zoom']['size']) : 14;
        $api_key = isset($settings['google_maps_api_key']) ? sanitize_text_field($settings['google_maps_api_key']) : '';
        
        // Map colors
        $water_color = isset($settings['water_color']) ? $settings['water_color'] : '#87CEEB';
        $land_color = isset($settings['land_color']) ? $settings['land_color'] : '#FAFAFA';
        $border_color = isset($settings['border_color']) ? $settings['border_color'] : '#D3D3D3';
        
        ?>
        <div class="interactive-map-widget">
            <?php if (!empty($settings['section_title'])) : ?>
                <h2 class="interactive-map-title"><?php echo esc_html($settings['section_title']); ?></h2>
            <?php endif; ?>
            
            <div class="interactive-map-container">
                <div class="map-section">
                    <div id="map-<?php echo esc_attr($widget_id); ?>" class="map-container"></div>
                </div>
                
                <div class="locations-section">
                    <div class="locations-list">
                        <?php if (!empty($locations_list)) : ?>
                            <?php foreach ($locations_list as $index => $location) : ?>
                                <div class="location-item" 
                                     data-lat="<?php echo esc_attr(isset($location['location_lat']) ? $location['location_lat'] : ''); ?>"
                                     data-lng="<?php echo esc_attr(isset($location['location_lng']) ? $location['location_lng'] : ''); ?>"
                                     data-index="<?php echo esc_attr($index); ?>"
                                     data-marker-color="<?php echo esc_attr(isset($location['marker_color']) ? $location['marker_color'] : '#FFD700'); ?>">
                                    <h3><?php echo esc_html(isset($location['location_name']) ? $location['location_name'] : ''); ?></h3>
                                    <?php if (!empty($location['location_description'])) : ?>
                                        <p><?php echo esc_html($location['location_description']); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <style>
        .interactive-map-container {
            display: flex;
            gap: 30px;
            margin-top: 30px;
        }
        
        .map-section {
            flex: 0 0 70%;
        }
        
        .map-container {
            width: 100%;
            height: 500px;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .locations-section {
            flex: 1;
            background-color: #F8F9FA;
            padding: 20px;
            border-radius: 8px;
        }
        
        .locations-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .location-item {
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #ffffff;
        }
        
        .location-item:hover {
            background: #F0F8FF;
            border-color: #007cba;
            color: #2C5AA0;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .location-item.active {
            background: #007cba !important;
            color: white !important;
            border-color: #005a87 !important;
        }
        
        .location-item h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .location-item p {
            margin: 0;
            font-size: 14px;
            opacity: 0.8;
            line-height: 1.5;
            transition: color 0.3s ease;
        }
        
        .location-item.active h3,
        .location-item.active p {
            color: white !important;
        }
        
        .location-item:hover h3,
        .location-item:hover p {
            color: #2C5AA0;
        }
        
        .interactive-map-title {
            text-align: center;
            margin-bottom: 0;
            font-size: 32px;
            font-weight: 300;
            color: #333;
        }
        
        @media (max-width: 1024px) {
            .map-section {
                flex: 0 0 60%;
            }
        }
        
        @media (max-width: 768px) {
            .interactive-map-container {
                flex-direction: column;
            }
            
            .map-section,
            .locations-section {
                flex: none;
            }
            
            .map-container {
                height: 300px;
            }
            
            .locations-section {
                margin-top: 0;
            }
        }
        
        @media (max-width: 480px) {
            .interactive-map-container {
                gap: 20px;
            }
            
            .locations-section {
                padding: 15px;
            }
            
            .location-item {
                padding: 15px;
            }
            
            .location-item h3 {
                font-size: 15px;
            }
            
            .location-item p {
                font-size: 13px;
            }
        }
        </style>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const widgetId = '<?php echo esc_js($widget_id); ?>';
            const apiKey = '<?php echo esc_js($api_key); ?>';
            
            if (!apiKey) {
                console.warn('Google Maps API key is required for the Interactive Map widget');
                const mapElement = document.getElementById('map-' + widgetId);
                if (mapElement) {
                    mapElement.innerHTML = '<div style="padding: 40px 20px; text-align: center; background: #f0f0f0; border-radius: 8px; color: #666; height: 100%; display: flex; flex-direction: column; justify-content: center;"><h4 style="margin: 0 0 10px 0; font-size: 18px;">Google Maps API Key Required</h4><p style="margin: 0; font-size: 14px; opacity: 0.8;">Please add your Google Maps API key in the widget settings to display the interactive map.</p></div>';
                }
                return;
            }
            
            const locations = <?php echo wp_json_encode($locations_list); ?>;
            const mapCenter = {
                lat: <?php echo $map_center_lat; ?>,
                lng: <?php echo $map_center_lng; ?>
            };
            const mapZoom = <?php echo $map_zoom; ?>;
            const locationZoom = <?php echo $location_zoom; ?>;
            
            // Map colors from Elementor settings
            const waterColor = '<?php echo esc_js($water_color); ?>';
            const landColor = '<?php echo esc_js($land_color); ?>';
            const borderColor = '<?php echo esc_js($border_color); ?>';
            
            let map;
            let markers = [];
            
            function initMap() {
                const mapElement = document.getElementById('map-' + widgetId);
                if (!mapElement) return;
                
                map = new google.maps.Map(mapElement, {
                    zoom: mapZoom,
                    center: mapCenter,
                    styles: [
                        {
                            "featureType": "water",
                            "elementType": "geometry",
                            "stylers": [{"color": waterColor}]
                        },
                        {
                            "featureType": "landscape",
                            "elementType": "geometry",
                            "stylers": [{"color": landColor}]
                        },
                        {
                            "featureType": "administrative.country",
                            "elementType": "geometry.stroke",
                            "stylers": [{"color": borderColor}]
                        },
                        {
                            "featureType": "administrative.province",
                            "elementType": "geometry.stroke", 
                            "stylers": [{"color": borderColor}]
                        },
                        {
                            "featureType": "administrative.locality",
                            "elementType": "geometry.stroke",
                            "stylers": [{"color": borderColor}]
                        },
                        {
                            "featureType": "road",
                            "elementType": "geometry",
                            "stylers": [{"color": "#ffffff"}, {"weight": 0.8}]
                        },
                        {
                            "featureType": "road",
                            "elementType": "labels",
                            "stylers": [{"visibility": "off"}]
                        },
                        {
                            "featureType": "poi",
                            "elementType": "labels",
                            "stylers": [{"visibility": "off"}]
                        }
                    ],
                    mapTypeControl: false,
                    streetViewControl: false,
                    fullscreenControl: true,
                    zoomControl: true
                });
                
                // Create markers for each location
                if (locations && locations.length > 0) {
                    locations.forEach((location, index) => {
                        if (location.location_lat && location.location_lng) {
                            const markerColor = location.marker_color || '#FFD700';
                            
                            const marker = new google.maps.Marker({
                                position: {
                                    lat: parseFloat(location.location_lat),
                                    lng: parseFloat(location.location_lng)
                                },
                                map: map,
                                title: location.location_name || '',
                                icon: {
                                    url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                                        <svg width="36" height="36" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="18" cy="18" r="14" fill="${markerColor}" stroke="#ffffff" stroke-width="3"/>
                                            <circle cx="18" cy="18" r="6" fill="#ffffff"/>
                                        </svg>
                                    `),
                                    scaledSize: new google.maps.Size(36, 36),
                                    anchor: new google.maps.Point(18, 18)
                                },
                                animation: google.maps.Animation.DROP
                            });
                            
                            const infoWindow = new google.maps.InfoWindow({
                                content: `
                                    <div style="padding: 15px; max-width: 280px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                                        <h4 style="margin: 0 0 10px 0; color: #333; font-size: 18px; font-weight: 600;">${location.location_name || ''}</h4>
                                        ${location.location_description ? `<p style="margin: 0; font-size: 14px; color: #666; line-height: 1.5;">${location.location_description}</p>` : ''}
                                    </div>
                                `,
                                pixelOffset: new google.maps.Size(0, -10)
                            });
                            
                            marker.addListener('click', () => {
                                markers.forEach(m => m.infoWindow && m.infoWindow.close());
                                infoWindow.open(map, marker);
                                
                                // Update active location item
                                document.querySelectorAll('.location-item').forEach(item => {
                                    item.classList.remove('active');
                                });
                                const activeItem = document.querySelector(`[data-index="${index}"]`);
                                if (activeItem) {
                                    activeItem.classList.add('active');
                                    // Smooth scroll to active item
                                    activeItem.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                                }
                            });
                            
                            markers.push({marker, infoWindow, index});
                        }
                    });
                }
                
                // Add click handlers to location items
                document.querySelectorAll('.location-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const lat = parseFloat(this.dataset.lat);
                        const lng = parseFloat(this.dataset.lng);
                        const index = parseInt(this.dataset.index);
                        
                        if (lat && lng && !isNaN(lat) && !isNaN(lng)) {
                            // Smooth pan to location
                            map.panTo({lat, lng});
                            
                            // Smooth zoom transition
                            const currentZoom = map.getZoom();
                            if (currentZoom !== locationZoom) {
                                const zoomDiff = locationZoom - currentZoom;
                                const steps = Math.abs(zoomDiff);
                                const stepSize = zoomDiff / steps;
                                
                                let currentStep = 0;
                                const zoomInterval = setInterval(() => {
                                    currentStep++;
                                    map.setZoom(currentZoom + (stepSize * currentStep));
                                    
                                    if (currentStep >= steps) {
                                        clearInterval(zoomInterval);
                                    }
                                }, 50);
                            }
                            
                            // Close all info windows
                            markers.forEach(m => m.infoWindow && m.infoWindow.close());
                            
                            // Open the corresponding marker's info window with delay
                            const markerData = markers.find(m => m.index === index);
                            if (markerData) {
                                setTimeout(() => {
                                    markerData.infoWindow.open(map, markerData.marker);
                                }, 400);
                            }
                            
                            // Update active state
                            document.querySelectorAll('.location-item').forEach(locationItem => {
                                locationItem.classList.remove('active');
                            });
                            this.classList.add('active');
                        }
                    });
                    
                    // Add keyboard accessibility
                    item.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            this.click();
                        }
                    });
                    
                    // Make focusable for accessibility
                    item.setAttribute('tabindex', '0');
                    item.setAttribute('role', 'button');
                    item.setAttribute('aria-label', 'View ' + (item.querySelector('h3')?.textContent || 'location') + ' on map');
                });
            }
            
            // Load Google Maps API
            if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
                const script = document.createElement('script');
                script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&callback=initMap${widgetId}&loading=async`;
                script.async = true;
                script.defer = true;
                window['initMap' + widgetId] = initMap;
                document.head.appendChild(script);
                
                // Handle script loading errors
                script.onerror = function() {
                    console.error('Failed to load Google Maps API');
                    const mapElement = document.getElementById('map-' + widgetId);
                    if (mapElement) {
                        mapElement.innerHTML = '<div style="padding: 40px 20px; text-align: center; background: #f0f0f0; border-radius: 8px; color: #666; height: 100%; display: flex; flex-direction: column; justify-content: center;"><h4 style="margin: 0 0 10px 0; color: #d63638;">Map Loading Error</h4><p style="margin: 0; font-size: 14px;">Unable to load Google Maps. Please check your API key and internet connection.</p></div>';
                    }
                };
            } else {
                initMap();
            }
        });
        </script>
        <?php
    }

    /**
     * Render widget output in the editor.
     */
    protected function content_template() {
        ?>
        <#
        const widgetId = 'preview-' + Math.random().toString(36).substr(2, 9);
        #>
        <div class="interactive-map-widget">
            <# if (settings.section_title) { #>
                <h2 class="interactive-map-title">{{{ settings.section_title }}}</h2>
            <# } #>
            
            <div class="interactive-map-container">
                <div class="map-section">
                    <div id="map-{{{ widgetId }}}" class="map-container" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; position: relative;">
                        <div style="text-align: center; z-index: 2;">
                            <h4 style="margin: 0 0 10px 0; font-size: 20px;">Interactive Map Preview</h4>
                            <p style="margin: 0; font-size: 14px; opacity: 0.9;">Add your Google Maps API key to see the live map</p>
                        </div>
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><circle cx=\"20\" cy=\"30\" r=\"3\" fill=\"%23FFD700\" opacity=\"0.8\"/><circle cx=\"70\" cy=\"20\" r=\"3\" fill=\"%23FF6B35\" opacity=\"0.8\"/><circle cx=\"50\" cy=\"60\" r=\"3\" fill=\"%234ECDC4\" opacity=\"0.8\"/><circle cx=\"30\" cy=\"70\" r=\"3\" fill=\"%23FFD700\" opacity=\"0.8\"/></svg>') center/cover; opacity: 0.3;"></div>
                    </div>
                </div>
                
                <div class="locations-section">
                    <div class="locations-list">
                        <# _.each(settings.locations_list, function(location, index) { #>
                            <div class="location-item" data-index="{{{ index }}}">
                                <h3>{{{ location.location_name }}}</h3>
                                <# if (location.location_description) { #>
                                    <p>{{{ location.location_description }}}</p>
                                <# } #>
                            </div>
                        <# }); #>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}