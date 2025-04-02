<?php
/**
 * Plugin Name: SxS Candidate Comparison
 * Plugin URI: https://govig.com
 * Description: A powerful side-by-side candidate comparison tool for recruitment professionals
 * Version: 1.0.0
 * Author: Govig
 * Author URI: https://govig.com
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
require_once SXS_CC_PLUGIN_DIR . 'includes/class-sxs-candidate-post-type.php';
require_once SXS_CC_PLUGIN_DIR . 'includes/class-sxs-candidate-metaboxes.php';
require_once SXS_CC_PLUGIN_DIR . 'includes/class-sxs-candidate-comparison.php';
require_once SXS_CC_PLUGIN_DIR . 'includes/class-sxs-comparison-set.php';

// Initialize plugin
function sxs_cc_init() {
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

    // Load text domain
    load_plugin_textdomain('sxs-candidate-comparison', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'sxs_cc_init');

// Activation hook
register_activation_hook(__FILE__, 'sxs_cc_activate');
function sxs_cc_activate() {
    // Create required database tables
    // Set up initial options
    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'sxs_cc_deactivate');
function sxs_cc_deactivate() {
    flush_rewrite_rules();
}

// Enqueue admin scripts
function sxs_cc_admin_scripts($hook) {
    if ('post.php' === $hook || 'post-new.php' === $hook) {
        $screen = get_current_screen();
        if ('sxs_comparison' === $screen->post_type) {
            wp_enqueue_script('jquery-ui-sortable');
        }
    }
}
add_action('admin_enqueue_scripts', 'sxs_cc_admin_scripts'); 