<?php
/**
 * Job Meta Boxes
 * 
 * Handles the creation and management of meta boxes for the Job post type
 */

if (!defined('ABSPATH')) {
    exit;
}

class SXS_Job_Metaboxes {
    
    public function init() {
        // Only initialize if jobs are enabled
        if (class_exists('SXS_Settings') && SXS_Settings::is_jobs_enabled()) {
            add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
            add_action('save_post_sxs_job', array($this, 'save_meta_boxes'));
        }
    }
    
    /**
     * Add meta boxes to the job edit screen
     */
    public function add_meta_boxes() {
        add_meta_box(
            'sxs_job_details',
            __('Job Details', 'sxs-candidate-comparison'),
            array($this, 'render_details_meta_box'),
            'sxs_job',
            'normal',
            'high'
        );
        
        add_meta_box(
            'sxs_job_company',
            __('Company Information', 'sxs-candidate-comparison'),
            array($this, 'render_company_meta_box'),
            'sxs_job',
            'normal',
            'high'
        );
        
        add_meta_box(
            'sxs_job_requirements',
            __('Job Requirements', 'sxs-candidate-comparison'),
            array($this, 'render_requirements_meta_box'),
            'sxs_job',
            'normal',
            'default'
        );
        
        add_meta_box(
            'sxs_job_application',
            __('Application Details', 'sxs-candidate-comparison'),
            array($this, 'render_application_meta_box'),
            'sxs_job',
            'side',
            'default'
        );
    }
    
    /**
     * Render the job details meta box
     */
    public function render_details_meta_box($post) {
        wp_nonce_field('sxs_job_details_nonce', 'sxs_job_details_nonce');
        
        $location = get_post_meta($post->ID, '_sxs_job_location', true);
        $type = get_post_meta($post->ID, '_sxs_job_type', true);
        $salary_min = get_post_meta($post->ID, '_sxs_job_salary_min', true);
        $salary_max = get_post_meta($post->ID, '_sxs_job_salary_max', true);
        $description = get_post_meta($post->ID, '_sxs_job_description', true);
        
        ?>
        <div class="sxs-meta-row">
            <p>
                <label for="sxs_job_location"><strong><?php _e('Location', 'sxs-candidate-comparison'); ?></strong></label>
                <input type="text" id="sxs_job_location" name="sxs_job_location" value="<?php echo esc_attr($location); ?>" class="widefat">
                <span class="description"><?php _e('Enter the job location (city, state, country or "Remote")', 'sxs-candidate-comparison'); ?></span>
            </p>
            
            <p>
                <label for="sxs_job_type"><strong><?php _e('Job Type', 'sxs-candidate-comparison'); ?></strong></label>
                <select id="sxs_job_type" name="sxs_job_type" class="widefat">
                    <option value=""><?php _e('-- Select Job Type --', 'sxs-candidate-comparison'); ?></option>
                    <option value="full-time" <?php selected($type, 'full-time'); ?>><?php _e('Full Time', 'sxs-candidate-comparison'); ?></option>
                    <option value="part-time" <?php selected($type, 'part-time'); ?>><?php _e('Part Time', 'sxs-candidate-comparison'); ?></option>
                    <option value="contract" <?php selected($type, 'contract'); ?>><?php _e('Contract', 'sxs-candidate-comparison'); ?></option>
                    <option value="freelance" <?php selected($type, 'freelance'); ?>><?php _e('Freelance', 'sxs-candidate-comparison'); ?></option>
                    <option value="temporary" <?php selected($type, 'temporary'); ?>><?php _e('Temporary', 'sxs-candidate-comparison'); ?></option>
                    <option value="internship" <?php selected($type, 'internship'); ?>><?php _e('Internship', 'sxs-candidate-comparison'); ?></option>
                </select>
            </p>
            
            <div class="sxs-salary-range">
                <p><strong><?php _e('Salary Range (Optional)', 'sxs-candidate-comparison'); ?></strong></p>
                <div class="sxs-salary-inputs">
                    <p>
                        <label for="sxs_job_salary_min"><?php _e('Minimum', 'sxs-candidate-comparison'); ?></label>
                        <input type="text" id="sxs_job_salary_min" name="sxs_job_salary_min" value="<?php echo esc_attr($salary_min); ?>" class="widefat">
                    </p>
                    <p>
                        <label for="sxs_job_salary_max"><?php _e('Maximum', 'sxs-candidate-comparison'); ?></label>
                        <input type="text" id="sxs_job_salary_max" name="sxs_job_salary_max" value="<?php echo esc_attr($salary_max); ?>" class="widefat">
                    </p>
                </div>
                <span class="description"><?php _e('Enter the salary range (without currency symbols). Leave empty if you prefer not to disclose.', 'sxs-candidate-comparison'); ?></span>
            </div>
            
            <p>
                <label for="sxs_job_description"><strong><?php _e('Job Description', 'sxs-candidate-comparison'); ?></strong></label>
                <?php
                wp_editor(
                    $description,
                    'sxs_job_description',
                    array(
                        'media_buttons' => false,
                        'textarea_name' => 'sxs_job_description',
                        'textarea_rows' => 10,
                        'tinymce'       => true,
                        'quicktags'     => true,
                    )
                );
                ?>
                <span class="description"><?php _e('Enter the detailed job description.', 'sxs-candidate-comparison'); ?></span>
            </p>
        </div>
        
        <style>
            .sxs-salary-inputs {
                display: flex;
                gap: 20px;
            }
            
            .sxs-salary-inputs p {
                flex: 1;
            }
            
            .sxs-meta-row p {
                margin-bottom: 20px;
            }
        </style>
        <?php
    }
    
    /**
     * Render the company meta box
     */
    public function render_company_meta_box($post) {
        wp_nonce_field('sxs_job_company_nonce', 'sxs_job_company_nonce');
        
        $selected_company_id = get_post_meta($post->ID, '_sxs_job_company_id', true);
        
        // Get all companies
        $companies = get_posts(array(
            'post_type' => 'sxs_company',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ));
        
        ?>
        <div class="sxs-meta-row">
            <p>
                <label for="sxs_job_company_id"><strong><?php _e('Select Company', 'sxs-candidate-comparison'); ?></strong></label>
                <select id="sxs_job_company_id" name="sxs_job_company_id" class="widefat">
                    <option value=""><?php _e('-- Select a Company --', 'sxs-candidate-comparison'); ?></option>
                    <?php foreach ($companies as $company) : ?>
                    <option value="<?php echo esc_attr($company->ID); ?>" <?php selected($selected_company_id, $company->ID); ?>>
                        <?php echo esc_html($company->post_title); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                
                <?php if (empty($companies)) : ?>
                <p class="sxs-notice sxs-notice-warning">
                    <?php 
                    printf(
                        __('No companies available. <a href="%s">Add a company</a> first.', 'sxs-candidate-comparison'),
                        admin_url('post-new.php?post_type=sxs_company')
                    ); 
                    ?>
                </p>
                <?php endif; ?>
                
                <span class="description"><?php _e('Select the company associated with this job.', 'sxs-candidate-comparison'); ?></span>
            </p>
            
            <?php if (!empty($selected_company_id)) : 
                $company = get_post($selected_company_id);
                if ($company) : 
                    // Get company details for preview
                    $header_color = get_post_meta($selected_company_id, '_sxs_company_header_color', true) ?: '#1C2856';
                    $text_color = get_post_meta($selected_company_id, '_sxs_company_text_color', true) ?: '#FFFFFF';
                    $logo_id = get_post_meta($selected_company_id, '_sxs_company_logo_id', true);
                    $logo_url = '';
                    if (!empty($logo_id)) {
                        $logo_img = wp_get_attachment_image_src($logo_id, array(40, 40));
                        if ($logo_img) {
                            $logo_url = $logo_img[0];
                        }
                    }
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
                <?php endif; ?>
            <?php endif; ?>
        </div>
        
        <style>
            .sxs-company-preview {
                margin-top: 15px;
                border: 1px solid #ddd;
                border-radius: 4px;
                overflow: hidden;
            }
            
            .sxs-company-header {
                padding: 15px;
                display: flex;
                align-items: center;
                font-weight: bold;
                font-size: 16px;
            }
            
            .sxs-company-logo {
                margin-right: 10px;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .sxs-company-logo img {
                max-width: 100%;
                max-height: 100%;
                border-radius: 4px;
                background: white;
            }
            
            .sxs-notice {
                margin: 10px 0;
                padding: 10px;
                border-radius: 4px;
            }
            
            .sxs-notice-warning {
                border-left: 4px solid #f0ad4e;
                background-color: #fcf8e3;
            }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            // Update preview when company selection changes
            $('#sxs_job_company_id').on('change', function() {
                var companyId = $(this).val();
                
                if (companyId) {
                    // Show loading message
                    if (!$('.sxs-company-loading').length) {
                        $('<p class="sxs-company-loading"><?php _e('Loading company preview...', 'sxs-candidate-comparison'); ?></p>').insertAfter($(this));
                    }
                    
                    // Make AJAX call to get company preview
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'sxs_get_company_preview',
                            company_id: companyId,
                            nonce: '<?php echo wp_create_nonce('sxs_get_company_preview'); ?>'
                        },
                        success: function(response) {
                            $('.sxs-company-loading').remove();
                            $('.sxs-company-preview').remove();
                            
                            if (response.success) {
                                $(response.data.html).insertAfter('#sxs_job_company_id');
                            }
                        }
                    });
                } else {
                    // Remove preview if no company is selected
                    $('.sxs-company-loading, .sxs-company-preview').remove();
                }
            });
        });
        </script>
        <?php
    }
    
    /**
     * Render the job requirements meta box
     */
    public function render_requirements_meta_box($post) {
        wp_nonce_field('sxs_job_requirements_nonce', 'sxs_job_requirements_nonce');
        
        $requirements = get_post_meta($post->ID, '_sxs_job_requirements', true);
        if (!is_array($requirements)) {
            $requirements = array('');
        }
        
        $experience = get_post_meta($post->ID, '_sxs_job_experience', true);
        $education = get_post_meta($post->ID, '_sxs_job_education', true);
        
        ?>
        <div class="sxs-meta-row">
            <p>
                <label for="sxs_job_experience"><strong><?php _e('Years of Experience Required', 'sxs-candidate-comparison'); ?></strong></label>
                <input type="text" id="sxs_job_experience" name="sxs_job_experience" value="<?php echo esc_attr($experience); ?>" class="widefat">
                <span class="description"><?php _e('Enter the minimum years of experience required (e.g., 3-5 years).', 'sxs-candidate-comparison'); ?></span>
            </p>
            
            <p>
                <label for="sxs_job_education"><strong><?php _e('Education Requirements', 'sxs-candidate-comparison'); ?></strong></label>
                <input type="text" id="sxs_job_education" name="sxs_job_education" value="<?php echo esc_attr($education); ?>" class="widefat">
                <span class="description"><?php _e('Enter the minimum education required (e.g., Bachelor\'s Degree in Computer Science).', 'sxs-candidate-comparison'); ?></span>
            </p>
            
            <div class="sxs-repeater">
                <h4><?php _e('Skills & Requirements', 'sxs-candidate-comparison'); ?></h4>
                <p class="description"><?php _e('List the specific skills and requirements for this job.', 'sxs-candidate-comparison'); ?></p>
                
                <div class="sxs-repeater-items" id="sxs-job-requirements">
                    <?php foreach ($requirements as $index => $requirement) : ?>
                    <div class="sxs-repeater-item">
                        <input type="text" name="sxs_job_requirements[]" value="<?php echo esc_attr($requirement); ?>" class="widefat">
                        <button type="button" class="button sxs-remove-item" <?php echo (count($requirements) <= 1) ? 'style="display:none;"' : ''; ?>>
                            <?php _e('Remove', 'sxs-candidate-comparison'); ?>
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <button type="button" class="button button-secondary sxs-add-item" data-target="sxs-job-requirements">
                    <?php _e('Add Requirement', 'sxs-candidate-comparison'); ?>
                </button>
            </div>
        </div>
        
        <style>
            .sxs-repeater-item {
                display: flex;
                align-items: center;
                margin-bottom: 10px;
            }
            
            .sxs-repeater-item input {
                flex: 1;
                margin-right: 10px;
            }
            
            .sxs-repeater-items {
                margin-bottom: 15px;
            }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            // Add item
            $('.sxs-add-item').on('click', function() {
                var target = $(this).data('target');
                var $items = $('#' + target);
                
                var $newItem = $items.find('.sxs-repeater-item:first').clone();
                $newItem.find('input').val('');
                $newItem.find('.sxs-remove-item').show();
                
                $items.append($newItem);
            });
            
            // Remove item
            $(document).on('click', '.sxs-remove-item', function() {
                var $items = $(this).closest('.sxs-repeater-items');
                
                if ($items.find('.sxs-repeater-item').length > 1) {
                    $(this).closest('.sxs-repeater-item').remove();
                }
            });
        });
        </script>
        <?php
    }
    
    /**
     * Render the application details meta box
     */
    public function render_application_meta_box($post) {
        wp_nonce_field('sxs_job_application_nonce', 'sxs_job_application_nonce');
        
        $application_url = get_post_meta($post->ID, '_sxs_job_application_url', true);
        $application_email = get_post_meta($post->ID, '_sxs_job_application_email', true);
        $closing_date = get_post_meta($post->ID, '_sxs_job_closing_date', true);
        
        ?>
        <div class="sxs-meta-row">
            <p>
                <label for="sxs_job_application_url"><strong><?php _e('Application URL', 'sxs-candidate-comparison'); ?></strong></label>
                <input type="url" id="sxs_job_application_url" name="sxs_job_application_url" value="<?php echo esc_url($application_url); ?>" class="widefat">
                <span class="description"><?php _e('Enter the URL where candidates can apply.', 'sxs-candidate-comparison'); ?></span>
            </p>
            
            <p>
                <label for="sxs_job_application_email"><strong><?php _e('Application Email', 'sxs-candidate-comparison'); ?></strong></label>
                <input type="email" id="sxs_job_application_email" name="sxs_job_application_email" value="<?php echo esc_attr($application_email); ?>" class="widefat">
                <span class="description"><?php _e('Enter the email where applications should be sent.', 'sxs-candidate-comparison'); ?></span>
            </p>
            
            <p>
                <label for="sxs_job_closing_date"><strong><?php _e('Closing Date', 'sxs-candidate-comparison'); ?></strong></label>
                <input type="date" id="sxs_job_closing_date" name="sxs_job_closing_date" value="<?php echo esc_attr($closing_date); ?>" class="widefat">
                <span class="description"><?php _e('Enter the closing date for applications.', 'sxs-candidate-comparison'); ?></span>
            </p>
        </div>
        <?php
    }
    
    /**
     * Save meta box data
     */
    public function save_meta_boxes($post_id) {
        // Check if autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Job Details
        if (isset($_POST['sxs_job_details_nonce']) && wp_verify_nonce($_POST['sxs_job_details_nonce'], 'sxs_job_details_nonce')) {
            if (isset($_POST['sxs_job_location'])) {
                update_post_meta($post_id, '_sxs_job_location', sanitize_text_field($_POST['sxs_job_location']));
            }
            
            if (isset($_POST['sxs_job_type'])) {
                update_post_meta($post_id, '_sxs_job_type', sanitize_text_field($_POST['sxs_job_type']));
            }
            
            if (isset($_POST['sxs_job_salary_min'])) {
                update_post_meta($post_id, '_sxs_job_salary_min', sanitize_text_field($_POST['sxs_job_salary_min']));
            }
            
            if (isset($_POST['sxs_job_salary_max'])) {
                update_post_meta($post_id, '_sxs_job_salary_max', sanitize_text_field($_POST['sxs_job_salary_max']));
            }
            
            if (isset($_POST['sxs_job_description'])) {
                update_post_meta($post_id, '_sxs_job_description', wp_kses_post($_POST['sxs_job_description']));
            }
        }
        
        // Company Information
        if (isset($_POST['sxs_job_company_nonce']) && wp_verify_nonce($_POST['sxs_job_company_nonce'], 'sxs_job_company_nonce')) {
            if (isset($_POST['sxs_job_company_id'])) {
                update_post_meta($post_id, '_sxs_job_company_id', intval($_POST['sxs_job_company_id']));
            }
        }
        
        // Job Requirements
        if (isset($_POST['sxs_job_requirements_nonce']) && wp_verify_nonce($_POST['sxs_job_requirements_nonce'], 'sxs_job_requirements_nonce')) {
            if (isset($_POST['sxs_job_experience'])) {
                update_post_meta($post_id, '_sxs_job_experience', sanitize_text_field($_POST['sxs_job_experience']));
            }
            
            if (isset($_POST['sxs_job_education'])) {
                update_post_meta($post_id, '_sxs_job_education', sanitize_text_field($_POST['sxs_job_education']));
            }
            
            if (isset($_POST['sxs_job_requirements'])) {
                $requirements = array_map('sanitize_text_field', $_POST['sxs_job_requirements']);
                $requirements = array_filter($requirements); // Remove empty items
                update_post_meta($post_id, '_sxs_job_requirements', $requirements);
            }
        }
        
        // Application Details
        if (isset($_POST['sxs_job_application_nonce']) && wp_verify_nonce($_POST['sxs_job_application_nonce'], 'sxs_job_application_nonce')) {
            if (isset($_POST['sxs_job_application_url'])) {
                update_post_meta($post_id, '_sxs_job_application_url', esc_url_raw($_POST['sxs_job_application_url']));
            }
            
            if (isset($_POST['sxs_job_application_email'])) {
                update_post_meta($post_id, '_sxs_job_application_email', sanitize_email($_POST['sxs_job_application_email']));
            }
            
            if (isset($_POST['sxs_job_closing_date'])) {
                update_post_meta($post_id, '_sxs_job_closing_date', sanitize_text_field($_POST['sxs_job_closing_date']));
            }
        }
    }
} 