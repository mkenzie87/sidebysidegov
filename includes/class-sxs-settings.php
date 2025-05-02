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
            // 'jobs_enabled' => false
            'default_header_background' => ''
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
            'default_header_background',
            __('Default Header Background', 'sxs-candidate-comparison'),
            array($this, 'render_default_header_background_field'),
            'sxs-settings',
            'sxs_general_section'
        );

        // add_settings_field(
        //     'jobs_enabled',
        //     __('Enable Job Post Type', 'sxs-candidate-comparison'),
        //     array($this, 'render_jobs_enabled_field'),
        //     'sxs-settings',
        //     'sxs_general_section'
        // );
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

    public function render_default_header_background_field() {
        // Ensure media scripts are loaded
        wp_enqueue_media();
        
        $image_url = isset($this->options['default_header_background']) ? $this->options['default_header_background'] : '';
        ?>
        <div class="sxs-image-upload-field">
            <input type="text" id="sxs_default_header_background" name="sxs_settings[default_header_background]" 
                value="<?php echo esc_attr($image_url); ?>" class="regular-text">
            <button type="button" class="button sxs-upload-image" id="sxs_upload_image_button">
                <?php _e('Upload Image', 'sxs-candidate-comparison'); ?>
            </button>
            <p class="description">
                <?php _e('Upload or select an image to use as the default background for all comparison headers.', 'sxs-candidate-comparison'); ?>
            </p>
            
            <?php if (!empty($image_url)) : ?>
                <div class="sxs-image-preview" style="margin-top: 10px;">
                    <img src="<?php echo esc_url($image_url); ?>" style="max-width: 300px; height: auto;" />
                </div>
            <?php endif; ?>
        </div>
        
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#sxs_upload_image_button').on('click', function(e) {
                e.preventDefault();
                
                var image = wp.media({
                    title: '<?php _e('Upload or Select Image', 'sxs-candidate-comparison'); ?>',
                    multiple: false
                }).open().on('select', function() {
                    var uploaded_image = image.state().get('selection').first();
                    var image_url = uploaded_image.toJSON().url;
                    
                    $('#sxs_default_header_background').val(image_url);
                    
                    // Update or add preview
                    if ($('.sxs-image-preview').length) {
                        $('.sxs-image-preview img').attr('src', image_url);
                    } else {
                        $('.sxs-image-upload-field').append('<div class="sxs-image-preview" style="margin-top: 10px;"><img src="' + image_url + '" style="max-width: 300px; height: auto;" /></div>');
                    }
                });
            });
        });
        </script>
        <?php
    }

    public function render_jobs_enabled_field() {
        // Removed jobs_enabled field
    }

    public function sanitize_settings($input) {
        $sanitized = array();
        
        // Sanitize default_header_background
        $sanitized['default_header_background'] = isset($input['default_header_background']) ? esc_url_raw($input['default_header_background']) : '';

        // Sanitize jobs_enabled
        // $sanitized['jobs_enabled'] = isset($input['jobs_enabled']) ? (bool) $input['jobs_enabled'] : false;

        return $sanitized;
    }

    public static function is_jobs_enabled() {
        // Always return false now that jobs are disabled
        return false;
    }

    public static function get_default_header_background() {
        $options = get_option('sxs_settings', array());
        return isset($options['default_header_background']) ? $options['default_header_background'] : '';
    }
} 