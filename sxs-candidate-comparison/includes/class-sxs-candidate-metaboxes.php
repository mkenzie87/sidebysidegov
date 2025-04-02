<?php

if (!defined('ABSPATH')) {
    exit;
}

class SXS_Candidate_Metaboxes {
    
    public function init() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post_sxs_candidate', array($this, 'save_meta_boxes'), 10, 2);
    }

    public function add_meta_boxes() {
        add_meta_box(
            'sxs_candidate_details',
            __('Candidate Details', 'sxs-candidate-comparison'),
            array($this, 'render_details_meta_box'),
            'sxs_candidate',
            'normal',
            'high'
        );

        add_meta_box(
            'sxs_candidate_experience',
            __('Experience & Education', 'sxs-candidate-comparison'),
            array($this, 'render_experience_meta_box'),
            'sxs_candidate',
            'normal',
            'high'
        );

        add_meta_box(
            'sxs_candidate_compensation',
            __('Compensation', 'sxs-candidate-comparison'),
            array($this, 'render_compensation_meta_box'),
            'sxs_candidate',
            'normal',
            'high'
        );
    }

    public function render_details_meta_box($post) {
        wp_nonce_field('sxs_candidate_details_nonce', 'sxs_candidate_details_nonce');
        
        $current_company = get_post_meta($post->ID, '_sxs_current_company', true);
        $current_title = get_post_meta($post->ID, '_sxs_current_title', true);
        
        ?>
        <div class="sxs-meta-row">
            <p>
                <label for="sxs_current_company"><?php _e('Current Company', 'sxs-candidate-comparison'); ?></label>
                <input type="text" id="sxs_current_company" name="sxs_current_company" value="<?php echo esc_attr($current_company); ?>" class="widefat">
            </p>
            <p>
                <label for="sxs_current_title"><?php _e('Current Title', 'sxs-candidate-comparison'); ?></label>
                <input type="text" id="sxs_current_title" name="sxs_current_title" value="<?php echo esc_attr($current_title); ?>" class="widefat">
            </p>
        </div>
        <?php
    }

    public function render_experience_meta_box($post) {
        wp_nonce_field('sxs_candidate_experience_nonce', 'sxs_candidate_experience_nonce');
        
        $industry_experience = get_post_meta($post->ID, '_sxs_industry_experience', true);
        $role_experience = get_post_meta($post->ID, '_sxs_role_experience', true);
        $education = get_post_meta($post->ID, '_sxs_education', true);
        $relevant_experience = get_post_meta($post->ID, '_sxs_relevant_experience', true);
        
        if (!is_array($education)) {
            $education = array('');
        }
        if (!is_array($relevant_experience)) {
            $relevant_experience = array('');
        }
        
        ?>
        <div class="sxs-meta-row">
            <p>
                <label for="sxs_industry_experience"><?php _e('Years of Industry Experience', 'sxs-candidate-comparison'); ?></label>
                <input type="number" id="sxs_industry_experience" name="sxs_industry_experience" value="<?php echo esc_attr($industry_experience); ?>" class="small-text">
            </p>
            <p>
                <label for="sxs_role_experience"><?php _e('Years of Role-Specific Experience', 'sxs-candidate-comparison'); ?></label>
                <input type="number" id="sxs_role_experience" name="sxs_role_experience" value="<?php echo esc_attr($role_experience); ?>" class="small-text">
            </p>
        </div>

        <div class="sxs-meta-row">
            <p>
                <label><?php _e('Education/Certifications', 'sxs-candidate-comparison'); ?></label>
                <div id="sxs_education_fields">
                    <?php foreach ($education as $index => $edu) : ?>
                        <div class="education-field">
                            <input type="text" name="sxs_education[]" value="<?php echo esc_attr($edu); ?>" class="widefat">
                            <button type="button" class="button remove-field"><?php _e('Remove', 'sxs-candidate-comparison'); ?></button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-education-field"><?php _e('Add Education', 'sxs-candidate-comparison'); ?></button>
            </p>
        </div>

        <div class="sxs-meta-row">
            <p>
                <label><?php _e('Relevant Experience', 'sxs-candidate-comparison'); ?></label>
                <div id="sxs_relevant_experience_fields">
                    <?php foreach ($relevant_experience as $index => $exp) : ?>
                        <div class="experience-field">
                            <textarea name="sxs_relevant_experience[]" class="widefat" rows="2"><?php echo esc_textarea($exp); ?></textarea>
                            <button type="button" class="button remove-field"><?php _e('Remove', 'sxs-candidate-comparison'); ?></button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-experience-field"><?php _e('Add Experience', 'sxs-candidate-comparison'); ?></button>
            </p>
        </div>

        <script>
        jQuery(document).ready(function($) {
            $('.add-education-field').on('click', function() {
                var field = '<div class="education-field">' +
                           '<input type="text" name="sxs_education[]" value="" class="widefat">' +
                           '<button type="button" class="button remove-field">Remove</button>' +
                           '</div>';
                $('#sxs_education_fields').append(field);
            });

            $('.add-experience-field').on('click', function() {
                var field = '<div class="experience-field">' +
                           '<textarea name="sxs_relevant_experience[]" class="widefat" rows="2"></textarea>' +
                           '<button type="button" class="button remove-field">Remove</button>' +
                           '</div>';
                $('#sxs_relevant_experience_fields').append(field);
            });

            $(document).on('click', '.remove-field', function() {
                $(this).parent().remove();
            });
        });
        </script>
        <?php
    }

    public function render_compensation_meta_box($post) {
        wp_nonce_field('sxs_candidate_compensation_nonce', 'sxs_candidate_compensation_nonce');
        
        $current_base = get_post_meta($post->ID, '_sxs_current_base', true);
        $current_bonus = get_post_meta($post->ID, '_sxs_current_bonus', true);
        $application_compensation = get_post_meta($post->ID, '_sxs_application_compensation', true);
        
        ?>
        <div class="sxs-meta-row">
            <p>
                <label for="sxs_current_base"><?php _e('Current Base Salary', 'sxs-candidate-comparison'); ?></label>
                <input type="text" id="sxs_current_base" name="sxs_current_base" value="<?php echo esc_attr($current_base); ?>" class="widefat">
            </p>
            <p>
                <label for="sxs_current_bonus"><?php _e('Current Bonus/Additional Compensation', 'sxs-candidate-comparison'); ?></label>
                <input type="text" id="sxs_current_bonus" name="sxs_current_bonus" value="<?php echo esc_attr($current_bonus); ?>" class="widefat">
            </p>
            <p>
                <label for="sxs_application_compensation"><?php _e('Application Compensation Range', 'sxs-candidate-comparison'); ?></label>
                <input type="text" id="sxs_application_compensation" name="sxs_application_compensation" value="<?php echo esc_attr($application_compensation); ?>" class="widefat">
            </p>
        </div>
        <?php
    }

    public function save_meta_boxes($post_id, $post) {
        // Verify nonces
        if (!isset($_POST['sxs_candidate_details_nonce']) ||
            !isset($_POST['sxs_candidate_experience_nonce']) ||
            !isset($_POST['sxs_candidate_compensation_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['sxs_candidate_details_nonce'], 'sxs_candidate_details_nonce') ||
            !wp_verify_nonce($_POST['sxs_candidate_experience_nonce'], 'sxs_candidate_experience_nonce') ||
            !wp_verify_nonce($_POST['sxs_candidate_compensation_nonce'], 'sxs_candidate_compensation_nonce')) {
            return;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save details
        if (isset($_POST['sxs_current_company'])) {
            update_post_meta($post_id, '_sxs_current_company', sanitize_text_field($_POST['sxs_current_company']));
        }
        if (isset($_POST['sxs_current_title'])) {
            update_post_meta($post_id, '_sxs_current_title', sanitize_text_field($_POST['sxs_current_title']));
        }

        // Save experience
        if (isset($_POST['sxs_industry_experience'])) {
            update_post_meta($post_id, '_sxs_industry_experience', absint($_POST['sxs_industry_experience']));
        }
        if (isset($_POST['sxs_role_experience'])) {
            update_post_meta($post_id, '_sxs_role_experience', absint($_POST['sxs_role_experience']));
        }

        // Save education
        if (isset($_POST['sxs_education'])) {
            $education = array_map('sanitize_text_field', $_POST['sxs_education']);
            $education = array_filter($education); // Remove empty values
            update_post_meta($post_id, '_sxs_education', $education);
        }

        // Save relevant experience
        if (isset($_POST['sxs_relevant_experience'])) {
            $experience = array_map('sanitize_textarea_field', $_POST['sxs_relevant_experience']);
            $experience = array_filter($experience); // Remove empty values
            update_post_meta($post_id, '_sxs_relevant_experience', $experience);
        }

        // Save compensation
        if (isset($_POST['sxs_current_base'])) {
            update_post_meta($post_id, '_sxs_current_base', sanitize_text_field($_POST['sxs_current_base']));
        }
        if (isset($_POST['sxs_current_bonus'])) {
            update_post_meta($post_id, '_sxs_current_bonus', sanitize_text_field($_POST['sxs_current_bonus']));
        }
        if (isset($_POST['sxs_application_compensation'])) {
            update_post_meta($post_id, '_sxs_application_compensation', sanitize_text_field($_POST['sxs_application_compensation']));
        }
    }
} 