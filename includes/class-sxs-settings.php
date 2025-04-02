<?php
/**
 * Plugin Settings Class
 * 
 * Handles the plugin's settings page and options
 */

if (!defined('ABSPATH')) {
    exit;
}

class SXS_Settings {
    private static $instance = null;
    private $options;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->options = get_option('sxs_settings', array(
            'jobs_enabled' => false
        ));
    }

    public function init() {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_settings_page() {
        add_submenu_page(
            'edit.php?post_type=sxs_candidate',
            __('Settings', 'sxs-candidate-comparison'),
            __('Settings', 'sxs-candidate-comparison'),
            'manage_options',
            'sxs-settings',
            array($this, 'render_settings_page')
        );
    }

    public function register_settings() {
        register_setting('sxs_settings', 'sxs_settings', array($this, 'sanitize_settings'));

        add_settings_section(
            'sxs_general_section',
            __('General Settings', 'sxs-candidate-comparison'),
            array($this, 'render_general_section'),
            'sxs-settings'
        );

        add_settings_field(
            'jobs_enabled',
            __('Enable Job Post Type', 'sxs-candidate-comparison'),
            array($this, 'render_jobs_enabled_field'),
            'sxs-settings',
            'sxs_general_section'
        );
    }

    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('sxs_settings');
                do_settings_sections('sxs-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function render_general_section() {
        echo '<p>' . esc_html__('Configure general settings for the Side by Side Candidate Comparison plugin.', 'sxs-candidate-comparison') . '</p>';
    }

    public function render_jobs_enabled_field() {
        $jobs_enabled = isset($this->options['jobs_enabled']) ? $this->options['jobs_enabled'] : false;
        ?>
        <label>
            <input type="checkbox" name="sxs_settings[jobs_enabled]" value="1" <?php checked($jobs_enabled); ?>>
            <?php esc_html_e('Enable the Job post type for managing job listings', 'sxs-candidate-comparison'); ?>
        </label>
        <p class="description">
            <?php esc_html_e('When enabled, you can create and manage job listings that can be associated with candidate comparisons.', 'sxs-candidate-comparison'); ?>
        </p>
        <?php
    }

    public function sanitize_settings($input) {
        $sanitized = array();
        
        // Sanitize jobs_enabled
        $sanitized['jobs_enabled'] = isset($input['jobs_enabled']) ? (bool) $input['jobs_enabled'] : false;

        return $sanitized;
    }

    public static function is_jobs_enabled() {
        $instance = self::get_instance();
        return isset($instance->options['jobs_enabled']) ? (bool) $instance->options['jobs_enabled'] : false;
    }
} 