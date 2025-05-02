<?php
//Comment here
if (!defined('ABSPATH')) {
    exit;
}

class SXS_Candidate_Comparison {
    
    public function init() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function enqueue_scripts() {
        // Define components in order of loading
        $components = array('layout', 'header', 'comparison', 'cells', 'recruiter', 'buttons', 'scroll-controls');
        
        // Enqueue each component's CSS
        foreach ($components as $component) {
            wp_enqueue_style(
                'sxs-' . $component,
                SXS_CC_PLUGIN_URL . 'assets/css/frontend/components/_' . $component . '.css',
                array(),
                filemtime(SXS_CC_PLUGIN_DIR . 'assets/css/frontend/components/_' . $component . '.css')
            );
        }
        
        // Include Font Awesome if needed
        if (!wp_script_is('font-awesome', 'enqueued')) {
            wp_enqueue_style(
                'font-awesome',
                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css',
                array(),
                '5.15.4'
            );
        }
        
        // Enqueue jQuery UI Tooltip (needed for tooltips in admin)
        wp_enqueue_script('jquery-ui-tooltip');
        wp_enqueue_style(
            'jquery-ui-styles',
            'https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css',
            array(),
            '1.13.2'
        );
        
        // Enqueue Slick Slider for recruiter section
        wp_enqueue_style(
            'slick-slider',
            'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css',
            array(),
            '1.8.1'
        );
        
        wp_enqueue_script(
            'slick-slider',
            'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js',
            array('jquery'),
            '1.8.1',
            true
        );
        
        // Main JavaScript
        wp_enqueue_script(
            'sxs-candidate-comparison',
            SXS_CC_PLUGIN_URL . 'assets/js/sxs-candidate-comparison.js',
            array('jquery', 'slick-slider', 'jquery-ui-tooltip'),
            filemtime(SXS_CC_PLUGIN_DIR . 'assets/js/sxs-candidate-comparison.js'),
            true
        );
    }

    public function enqueue_admin_scripts($hook) {
        if ('post.php' !== $hook && 'post-new.php' !== $hook) {
            return;
        }

        wp_enqueue_style(
            'sxs-candidate-admin',
            plugins_url('assets/css/admin/admin.css', dirname(__FILE__)),
            array(),
            SXS_CC_VERSION
        );
    }
} 