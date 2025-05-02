<?php
/**
 * Plugin Name: SxS Candidate Comparison
 * Plugin URI: https://recruiterswebsites.com
 * Description: A powerful side-by-side candidate comparison tool for recruitment professionals
 * Version: 1.0.0
 * Author: Jeff Gipson
 * Author URI: https://recruiterswebsites.com
 * Text Domain: sxs-candidate-comparison
 * License: GPL v2 or later
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SXS_CC_VERSION', '1.0.0');
define('SXS_CC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SXS_CC_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once SXS_CC_PLUGIN_DIR . 'includes/class-sxs-settings.php';
require_once SXS_CC_PLUGIN_DIR . 'includes/class-sxs-candidate-post-type.php';
require_once SXS_CC_PLUGIN_DIR . 'includes/class-sxs-candidate-metaboxes.php';
require_once SXS_CC_PLUGIN_DIR . 'includes/class-sxs-candidate-comparison.php';
require_once SXS_CC_PLUGIN_DIR . 'includes/class-sxs-comparison-set.php';
require_once SXS_CC_PLUGIN_DIR . 'admin/class-sxs-help-docs.php';
require_once SXS_CC_PLUGIN_DIR . 'admin/class-sxs-dashboard-widget.php';

// Initialize plugin
function sxs_cc_init() {
    // Initialize settings
    $settings = SXS_Settings::get_instance();
    $settings->init();
    
    // Initialize post types
    $candidate_post_type = new SXS_Candidate_Post_Type();
    $candidate_post_type->init();

    $comparison_set = new SXS_Comparison_Set();
    $comparison_set->init();

    // Initialize metaboxes
    $metaboxes = new SXS_Candidate_Metaboxes();
    $metaboxes->init();

    // Initialize comparison functionality
    $comparison = new SXS_Candidate_Comparison();
    $comparison->init();
    
    // Initialize admin components
    if (is_admin()) {
        $help_docs = new SXS_Help_Docs();
        $help_docs->init();
        
        $dashboard_widget = new SXS_Dashboard_Widget();
        $dashboard_widget->init();
    }

    // Load text domain
    load_plugin_textdomain('sxs-candidate-comparison', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'sxs_cc_init');

// Activation hook
register_activation_hook(__FILE__, 'sxs_cc_activate');
function sxs_cc_activate() {
    // Set up initial options
    $default_settings = array(
        // 'jobs_enabled' => false
    );
    
    // Only add the option if it doesn't exist
    if (!get_option('sxs_settings')) {
        add_option('sxs_settings', $default_settings);
    }
    
    // Flush rewrite rules
    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'sxs_cc_deactivate');
function sxs_cc_deactivate() {
    // Clean up header content meta
    global $wpdb;
    $wpdb->delete($wpdb->postmeta, array('meta_key' => '_sxs_header_content'));
    
    // Flush rewrite rules
    flush_rewrite_rules();
}

// Enqueue admin scripts
function sxs_cc_admin_scripts($hook) {
    if ('post.php' === $hook || 'post-new.php' === $hook) {
        $screen = get_current_screen();
        
        // Enqueue jQuery UI Tooltip (needed for tooltips in admin)
        wp_enqueue_script('jquery-ui-tooltip');
        wp_enqueue_style(
            'jquery-ui-styles',
            'https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css',
            array(),
            '1.13.2'
        );
        
        if ('sxs_comparison' === $screen->post_type) {
            wp_enqueue_script('jquery-ui-sortable');
        }
        
        // Enqueue admin scripts and styles for candidate and comparison post types
        if ('sxs_candidate' === $screen->post_type || 'sxs_comparison' === $screen->post_type) {
            // Admin CSS with filemtime for cache busting
            wp_enqueue_style(
                'sxs-candidate-admin',
                SXS_CC_PLUGIN_URL . 'assets/css/admin/admin.css',
                array('jquery-ui-styles'),
                filemtime(SXS_CC_PLUGIN_DIR . 'assets/css/admin/admin.css')
            );
            
            // Main admin JS
            wp_enqueue_script(
                'sxs-candidate-admin',
                SXS_CC_PLUGIN_URL . 'assets/js/admin/index.js',
                array('jquery', 'jquery-ui-sortable', 'jquery-ui-tooltip'),
                filemtime(SXS_CC_PLUGIN_DIR . 'assets/js/admin/index.js'),
                true
            );
            
            // Company specific JS for preview - for comparison post type only
            if ('sxs_comparison' === $screen->post_type) {
                wp_enqueue_script(
                    'sxs-company-admin',
                    SXS_CC_PLUGIN_URL . 'assets/js/admin/company.js',
                    array('jquery', 'sxs-candidate-admin'),
                    filemtime(SXS_CC_PLUGIN_DIR . 'assets/js/admin/company.js'),
                    true
                );
            }
            
            // Localize script for translations and variables
            wp_localize_script('sxs-candidate-admin', 'sxsAdmin', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('sxs-admin-nonce'),
                'i18n' => array(
                    'addEducation' => __('Add Education', 'sxs-candidate-comparison'),
                    'addExperience' => __('Add Experience', 'sxs-candidate-comparison'),
                    'remove' => __('Remove', 'sxs-candidate-comparison'),
                    'selectCandidate' => __('Select a candidate...', 'sxs-candidate-comparison')
                )
            ));
        }
    }
    
    // Add styles for admin help page
    if ('sxs_candidate_page_sxs-help-docs' === $hook) {
        wp_enqueue_style(
            'sxs-help-docs',
            SXS_CC_PLUGIN_URL . 'assets/css/admin/help-docs.css',
            array(),
            filemtime(SXS_CC_PLUGIN_DIR . 'assets/css/admin/help-docs.css')
        );
    }
}
add_action('admin_enqueue_scripts', 'sxs_cc_admin_scripts');

// Load dashicons on frontend
function sxs_enqueue_dashicons() {
    wp_enqueue_style('dashicons');
}
add_action('wp_enqueue_scripts', 'sxs_enqueue_dashicons');

// AJAX function to get company preview
function sxs_get_company_preview() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'sxs_get_company_preview')) {
        wp_send_json_error(array('message' => __('Security check failed', 'sxs-candidate-comparison')));
    }
    
    // Check company ID
    if (!isset($_POST['company_id']) || empty($_POST['company_id'])) {
        wp_send_json_error(array('message' => __('No company selected', 'sxs-candidate-comparison')));
    }
    
    $company_id = intval($_POST['company_id']);
    $company = get_post($company_id);
    
    if (!$company || $company->post_type !== 'companies') {
        wp_send_json_error(array('message' => __('Invalid company', 'sxs-candidate-comparison')));
    }
    
    // Get company logo using ACF
    $logo_url = '';
    if (function_exists('get_field')) {
        $logo = get_field('company_logo', $company_id);
        if (!empty($logo)) {
            $logo_url = is_array($logo) ? $logo['url'] : $logo;
        }
    }
    
    // Default colors
    $header_color = '#1C2856';
    $text_color = '#FFFFFF';
    
    // Build preview HTML
    ob_start();
    ?>
    <div class="sxs-company-preview">
        <div class="sxs-company-header" style="background-color:<?php echo esc_attr($header_color); ?>;color:<?php echo esc_attr($text_color); ?>;">
            <?php if (!empty($logo_url)) : ?>
                <div class="sxs-company-logo">
                    <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($company->post_title); ?> Logo">
                </div>
            <?php endif; ?>
            <div class="sxs-company-title">
                <?php echo esc_html($company->post_title); ?>
            </div>
        </div>
    </div>
    <?php
    $html = ob_get_clean();
    
    // Send response
    wp_send_json_success(array(
        'html' => $html
    ));
}
add_action('wp_ajax_sxs_get_company_preview', 'sxs_get_company_preview'); 
