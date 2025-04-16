<?php

if (!defined('ABSPATH')) {
    exit;
}

class SXS_Comparison_Set {
    
    public function init() {
        add_action('init', array($this, 'register_post_type'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'), 10, 2);
        add_filter('post_row_actions', array($this, 'modify_row_actions'), 10, 2);
        add_filter('template_include', array($this, 'load_comparison_template'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
        add_filter('manage_sxs_comparison_posts_columns', array($this, 'set_custom_columns'));
        add_action('manage_sxs_comparison_posts_custom_column', array($this, 'custom_column'), 10, 2);
    }

    public function register_post_type() {
        $labels = array(
            'name'               => _x('Side by Side Comparisons', 'post type general name', 'sxs-candidate-comparison'),
            'singular_name'      => _x('Side by Side Comparison', 'post type singular name', 'sxs-candidate-comparison'),
            'menu_name'          => _x('Comparisons', 'admin menu', 'sxs-candidate-comparison'),
            'name_admin_bar'     => _x('Side by Side', 'add new on admin bar', 'sxs-candidate-comparison'),
            'add_new'            => _x('Add New', 'comparison', 'sxs-candidate-comparison'),
            'add_new_item'       => __('Add New Comparison', 'sxs-candidate-comparison'),
            'new_item'           => __('New Comparison', 'sxs-candidate-comparison'),
            'edit_item'          => __('Edit Comparison', 'sxs-candidate-comparison'),
            'view_item'          => __('View Comparison', 'sxs-candidate-comparison'),
            'all_items'          => __('All Comparisons', 'sxs-candidate-comparison'),
            'search_items'       => __('Search Comparisons', 'sxs-candidate-comparison'),
            'parent_item_colon'  => __('Parent Comparisons:', 'sxs-candidate-comparison'),
            'not_found'          => __('No comparisons found.', 'sxs-candidate-comparison'),
            'not_found_in_trash' => __('No comparisons found in Trash.', 'sxs-candidate-comparison')
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __('Side by Side Candidate Comparisons', 'sxs-candidate-comparison'),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'edit.php?post_type=sxs_candidate',
            'query_var'          => true,
            'rewrite'            => array('slug' => 'side-by-side'),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 5,
            'menu_icon'          => 'dashicons-columns',
            'supports'           => array(
                'title',
                'author',
            ),
            'show_in_rest'       => true,
        );

        register_post_type('sxs_comparison', $args);
    }

    public function add_meta_boxes() {
        // Only add job selection if jobs are enabled
        if (class_exists('SXS_Settings') && SXS_Settings::is_jobs_enabled()) {
            add_meta_box(
                'sxs_job_selection',
                __('Job Selection', 'sxs-candidate-comparison'),
                array($this, 'render_job_selection_meta_box'),
                'sxs_comparison',
                'normal',
                'high'
            );
        }
        
        // Position Brief and Scorecard metabox
        add_meta_box(
            'sxs_position_brief_scorecard',
            __('Position Brief & Scorecard', 'sxs-candidate-comparison'),
            array($this, 'render_position_brief_scorecard_meta_box'),
            'sxs_comparison',
            'normal',
            'high'
        );
        
        // Recruiters metabox
        add_meta_box(
            'sxs_recruiters_selection',
            __('Recruiters', 'sxs-candidate-comparison'),
            array($this, 'render_recruiters_meta_box'),
            'sxs_comparison',
            'normal',
            'high'
        );
        
        // Candidates metabox
        add_meta_box(
            'sxs_comparison_candidates',
            __('Select Candidates', 'sxs-candidate-comparison'),
            array($this, 'render_candidates_meta_box'),
            'sxs_comparison',
            'normal',
            'high'
        );

        // Companies metabox
        add_meta_box(
            'sxs_comparison_companies',
            __('Select Companies', 'sxs-candidate-comparison'),
            array($this, 'render_companies_meta_box'),
            'sxs_comparison',
            'normal',
            'high'
        );

        // Shortcode metabox
        add_meta_box(
            'sxs_comparison_shortcode',
            __('Shortcode', 'sxs-candidate-comparison'),
            array($this, 'render_shortcode_meta_box'),
            'sxs_comparison',
            'side',
            'default'
        );
    }

    public function render_job_selection_meta_box($post) {
        wp_nonce_field('sxs_job_selection_nonce', 'sxs_job_selection_nonce');
        
        $selected_job = get_post_meta($post->ID, '_sxs_selected_job', true);
        
        // Get all jobs
        $jobs = get_posts(array(
            'post_type' => 'sxs_job',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ));

        ?>
        <div class="sxs-meta-row">
            <h3 class="sxs-section-title"><?php _e('Job Selection', 'sxs-candidate-comparison'); ?></h3>
            
            <p class="description">
                <?php _e('Choose a job to include in this comparison. The job information will be displayed along with the candidates.', 'sxs-candidate-comparison'); ?>
                <span class="sxs-tooltip">
                    <span class="sxs-tooltip-icon">?</span>
                    <span class="sxs-tooltip-text"><?php _e('Job information such as title, location, description and application link will be shown in the comparison.', 'sxs-candidate-comparison'); ?></span>
                </span>
            </p>
            
            <div class="sxs-job-selector">
                <label for="sxs_selected_job">
                    <?php _e('Select Job', 'sxs-candidate-comparison'); ?>
                </label>
                
                <select id="sxs_selected_job" name="sxs_selected_job" class="widefat">
                    <option value=""><?php _e('-- None --', 'sxs-candidate-comparison'); ?></option>
                    <?php foreach ($jobs as $job) : ?>
                        <?php 
                        $company_id = get_post_meta($job->ID, '_sxs_job_company_id', true);
                        $company_name = '';
                        if (!empty($company_id)) {
                            $company = get_post($company_id);
                            if ($company) {
                                $company_name = ' (' . $company->post_title . ')';
                            }
                        }
                        $job_location = get_post_meta($job->ID, '_sxs_job_location', true);
                        $location_text = !empty($job_location) ? ' - ' . $job_location : '';
                        ?>
                        <option value="<?php echo esc_attr($job->ID); ?>" <?php selected($selected_job, $job->ID); ?>>
                            <?php echo esc_html($job->post_title) . esc_html($location_text) . esc_html($company_name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <?php if (empty($jobs)) : ?>
                    <div class="sxs-error-message">
                        <?php 
                        printf(
                            __('No jobs available. <a href="%s">Add a job</a> first.', 'sxs-candidate-comparison'),
                            admin_url('post-new.php?post_type=sxs_job')
                        ); 
                        ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($selected_job)) : 
                    $job = get_post($selected_job);
                    if ($job) : 
                        // Get job details
                        $job_location = get_post_meta($job->ID, '_sxs_job_location', true);
                        $job_type = get_post_meta($job->ID, '_sxs_job_type', true);
                        $company_id = get_post_meta($job->ID, '_sxs_job_company_id', true);
                        $company_name = '';
                        $company_logo_url = '';
                        
                        if (!empty($company_id)) {
                            $company = get_post($company_id);
                            if ($company) {
                                $company_name = $company->post_title;
                                $logo_id = get_post_meta($company_id, '_sxs_company_logo_id', true);
                                if (!empty($logo_id)) {
                                    $logo_img = wp_get_attachment_image_src($logo_id, array(40, 40));
                                    if ($logo_img) {
                                        $company_logo_url = $logo_img[0];
                                    }
                                }
                            }
                        }
                        ?>
                        <div class="sxs-job-preview">
                            <div class="sxs-job-preview-header">
                                <?php if (!empty($company_logo_url)) : ?>
                                <div class="sxs-job-company-logo">
                                    <img src="<?php echo esc_url($company_logo_url); ?>" alt="<?php echo esc_attr($company_name); ?> Logo">
                                </div>
                                <?php endif; ?>
                                <div class="sxs-job-preview-title">
                                    <h3><?php echo esc_html($job->post_title); ?></h3>
                                    <?php if (!empty($company_name)) : ?>
                                    <div class="sxs-job-company"><?php echo esc_html($company_name); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="sxs-job-preview-details">
                                <?php if (!empty($job_location)) : ?>
                                <div class="sxs-job-location">
                                    <strong><?php _e('Location:', 'sxs-candidate-comparison'); ?></strong> <?php echo esc_html($job_location); ?>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($job_type)) : ?>
                                <div class="sxs-job-type">
                                    <strong><?php _e('Type:', 'sxs-candidate-comparison'); ?></strong> <?php echo esc_html(ucfirst($job_type)); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="sxs-job-actions">
                            <a href="<?php echo get_edit_post_link($job->ID); ?>" class="button" target="_blank">
                                <?php _e('Edit Job', 'sxs-candidate-comparison'); ?>
                            </a>
                            <a href="<?php echo get_permalink($job->ID); ?>" class="button" target="_blank">
                                <?php _e('View Job', 'sxs-candidate-comparison'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <style>
            .sxs-job-selector {
                margin-top: 20px;
            }
            
            #sxs_selected_job {
                margin-top: 10px;
                margin-bottom: 15px;
            }
            
            .sxs-job-preview {
                margin-top: 15px;
                border: 1px solid #ddd;
                border-radius: 4px;
                overflow: hidden;
                background: #f9f9f9;
            }
            
            .sxs-job-preview-header {
                padding: 15px;
                display: flex;
                align-items: center;
                border-bottom: 1px solid #eee;
                background: #fff;
            }
            
            .sxs-job-company-logo {
                margin-right: 15px;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .sxs-job-company-logo img {
                max-width: 100%;
                max-height: 100%;
                border-radius: 4px;
            }
            
            .sxs-job-preview-title h3 {
                margin: 0 0 5px;
                font-size: 16px;
            }
            
            .sxs-job-company {
                font-size: 13px;
                color: #666;
            }
            
            .sxs-job-preview-details {
                padding: 15px;
                display: flex;
                gap: 20px;
                font-size: 13px;
            }
            
            .sxs-job-actions {
                margin-top: 10px;
                display: flex;
                gap: 10px;
            }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            // Update preview when job selection changes
            $('#sxs_selected_job').on('change', function() {
                // Submit the form when job changes to update the preview
                var $form = $(this).closest('form');
                var action = $form.attr('action');
                
                // Store the current scroll position
                var scrollPosition = $(window).scrollTop();
                
                // Add a hidden input to indicate this is just a preview update
                if (!$('#sxs_preview_update').length) {
                    $form.append('<input type="hidden" id="sxs_preview_update" name="sxs_preview_update" value="1">');
                }
                
                // Save form data to localStorage
                var formData = $form.serialize();
                localStorage.setItem('sxs_form_data', formData);
                localStorage.setItem('sxs_scroll_position', scrollPosition);
                
                // Submit the form
                $form.submit();
            });
            
            // Restore scroll position after page reload
            var savedScrollPosition = localStorage.getItem('sxs_scroll_position');
            if (savedScrollPosition) {
                $(window).scrollTop(savedScrollPosition);
                localStorage.removeItem('sxs_scroll_position');
            }
        });
        </script>
        <?php
    }

    public function render_position_brief_scorecard_meta_box($post) {
        wp_nonce_field('sxs_position_brief_scorecard_nonce', 'sxs_position_brief_scorecard_nonce');
        
        // Get current values
        $position_brief_enabled = get_post_meta($post->ID, '_sxs_position_brief_enabled', true) === '1';
        $position_brief_url = get_post_meta($post->ID, '_sxs_position_brief_url', true);
        $scorecard_enabled = get_post_meta($post->ID, '_sxs_scorecard_enabled', true) === '1';
        $scorecard_url = get_post_meta($post->ID, '_sxs_scorecard_url', true);
        $buttons_message = get_post_meta($post->ID, '_sxs_buttons_message', true);
        
        ?>
        <div class="sxs-meta-row">
            <h3 class="sxs-section-title"><?php _e('Position Brief & Scorecard Buttons', 'sxs-candidate-comparison'); ?></h3>
            
            <p class="description">
                <?php _e('Enable buttons to link to Position Brief and Scorecard documents.', 'sxs-candidate-comparison'); ?>
            </p>
            
            <div class="sxs-field-row">
                <div>
                    <label for="sxs_position_brief_enabled">
                        <input type="checkbox" name="sxs_position_brief_enabled" id="sxs_position_brief_enabled" value="1" <?php checked($position_brief_enabled, true); ?>>
                        <?php _e('Enable Position Brief Button', 'sxs-candidate-comparison'); ?>
                    </label>
                </div>
                
                <div class="sxs-position-brief-url" style="margin: 10px 0 20px 24px; <?php echo $position_brief_enabled ? '' : 'display: none;'; ?>">
                    <label for="sxs_position_brief_url">
                        <?php _e('Position Brief URL', 'sxs-candidate-comparison'); ?>
                    </label>
                    <input type="url" name="sxs_position_brief_url" id="sxs_position_brief_url" 
                           value="<?php echo esc_attr($position_brief_url); ?>" class="widefat"
                           placeholder="https://example.com/position-brief.pdf">
                    <p class="description"><?php _e('URL to the Position Brief document (PDF or webpage).', 'sxs-candidate-comparison'); ?></p>
                </div>
            </div>
            
            <div class="sxs-field-row">
                <div>
                    <label for="sxs_scorecard_enabled">
                        <input type="checkbox" name="sxs_scorecard_enabled" id="sxs_scorecard_enabled" value="1" <?php checked($scorecard_enabled, true); ?>>
                        <?php _e('Enable Scorecard Button', 'sxs-candidate-comparison'); ?>
                    </label>
                </div>
                
                <div class="sxs-scorecard-url" style="margin: 10px 0 20px 24px; <?php echo $scorecard_enabled ? '' : 'display: none;'; ?>">
                    <label for="sxs_scorecard_url">
                        <?php _e('Scorecard URL', 'sxs-candidate-comparison'); ?>
                    </label>
                    <input type="url" name="sxs_scorecard_url" id="sxs_scorecard_url" 
                           value="<?php echo esc_attr($scorecard_url); ?>" class="widefat"
                           placeholder="https://example.com/scorecard.pdf">
                    <p class="description"><?php _e('URL to the Scorecard document (PDF or webpage).', 'sxs-candidate-comparison'); ?></p>
                </div>
            </div>
        </div>
        
        <div class="sxs-meta-row sxs-buttons-message" style="margin-top: 20px; <?php echo ($position_brief_enabled || $scorecard_enabled) ? '' : 'display: none;'; ?>">
            <h3 class="sxs-section-title"><?php _e('Message Before Buttons', 'sxs-candidate-comparison'); ?></h3>
            <p>
                <label for="sxs_buttons_message">
                    <?php _e('Enter a message to display above the buttons', 'sxs-candidate-comparison'); ?>
                </label>
                <input type="text" id="sxs_buttons_message" name="sxs_buttons_message" 
                       value="<?php echo esc_attr($buttons_message); ?>" class="widefat"
                       placeholder="<?php _e('e.g., Learn more about this position:', 'sxs-candidate-comparison'); ?>">
                <p class="description"><?php _e('This message will appear as a heading above the Position Brief and Scorecard buttons.', 'sxs-candidate-comparison'); ?></p>
            </p>
        </div>
        <?php
    }

    public function render_recruiters_meta_box($post) {
        wp_nonce_field('sxs_recruiters_selection_nonce', 'sxs_recruiters_selection_nonce');
        
        // Get selected recruiters
        $selected_recruiters = get_post_meta($post->ID, '_sxs_selected_recruiters', true);
        if (!is_array($selected_recruiters)) {
            $selected_recruiters = array();
        }
        
        // Query for team members (recruiters)
        $args = array(
            'posts_per_page' => -1,
            'post_type' => 'team', // Using the existing 'team' post type
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC',
        );
        
        // Query posts
        $recruiters = get_posts($args);
        ?>
        <div class="sxs-meta-row">
            <h3 class="sxs-section-title">
                <?php _e('Select Recruiters for This Comparison', 'sxs-candidate-comparison'); ?>
                <span class="sxs-tooltip">
                    <span class="sxs-tooltip-icon">?</span>
                    <span class="sxs-tooltip-text"><?php _e('Choose team members to display as recruiter contacts in the comparison header.', 'sxs-candidate-comparison'); ?></span>
                </span>
            </h3>
            
            <p class="description">
                <?php _e('The selected recruiters will be displayed in the comparison header as contact persons.', 'sxs-candidate-comparison'); ?>
            </p>
            
            <?php if (!empty($recruiters)) : ?>
                <div class="sxs-recruiter-selection" style="display: flex; gap: 20px; margin-top: 15px;">
                    <div style="flex: 1;">
                        <h4><?php _e('Available Team Members', 'sxs-candidate-comparison'); ?></h4>
                        <select id="sxs-available-recruiters" multiple="multiple" class="widefat" style="height: 200px; width: 100%;">
                            <?php 
                            foreach ($recruiters as $recruiter) : 
                                if (!in_array($recruiter->ID, $selected_recruiters)) :
                            ?>
                                <option value="<?php echo esc_attr($recruiter->ID); ?>">
                                    <?php echo esc_html($recruiter->post_title); ?>
                                </option>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </select>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; justify-content: center;">
                        <button type="button" id="sxs-add-recruiter" class="button" style="margin-bottom: 10px;">
                            <?php _e('Add →', 'sxs-candidate-comparison'); ?>
                        </button>
                        <button type="button" id="sxs-remove-recruiter" class="button">
                            <?php _e('← Remove', 'sxs-candidate-comparison'); ?>
                        </button>
                    </div>
                    
                    <div style="flex: 1;">
                        <h4><?php _e('Selected Team Members', 'sxs-candidate-comparison'); ?></h4>
                        <select id="sxs-selected-recruiters" name="sxs_selected_recruiters[]" multiple="multiple" class="widefat" style="height: 200px; width: 100%;">
                            <?php 
                            foreach ($selected_recruiters as $recruiter_id) : 
                                $recruiter = get_post($recruiter_id);
                                if ($recruiter && $recruiter->post_status == 'publish') :
                            ?>
                                <option value="<?php echo esc_attr($recruiter->ID); ?>">
                                    <?php echo esc_html($recruiter->post_title); ?>
                                </option>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </select>
                    </div>
                </div>
                
                <p class="description" style="margin-top: 10px;">
                    <?php _e('You can select multiple team members by holding down the Ctrl (Windows) or Command (Mac) key while clicking.', 'sxs-candidate-comparison'); ?>
                </p>
                
                <script type="text/javascript">
                jQuery(document).ready(function($) {
                    // Add recruiters
                    $('#sxs-add-recruiter').on('click', function() {
                        $('#sxs-available-recruiters option:selected').each(function() {
                            var option = $(this);
                            var value = option.val();
                            var text = option.text();
                            
                            // Add to selected list
                            $('#sxs-selected-recruiters').append(
                                $('<option></option>').val(value).text(text)
                            );
                            
                            // Remove from available list
                            option.remove();
                        });
                    });
                    
                    // Remove recruiters
                    $('#sxs-remove-recruiter').on('click', function() {
                        $('#sxs-selected-recruiters option:selected').each(function() {
                            var option = $(this);
                            var value = option.val();
                            var text = option.text();
                            
                            // Add to available list
                            $('#sxs-available-recruiters').append(
                                $('<option></option>').val(value).text(text)
                            );
                            
                            // Remove from selected list
                            option.remove();
                        });
                    });
                    
                    // Select all options before form submission
                    $('form#post').on('submit', function() {
                        $('#sxs-selected-recruiters option').prop('selected', true);
                    });
                });
                </script>
            <?php else : ?>
                <div class="sxs-notice" style="background: #f8f8f8; padding: 15px; border-left: 4px solid #ddd; margin-top: 10px;">
                    <?php _e('No team members found. Please make sure the "team" post type exists and has published entries.', 'sxs-candidate-comparison'); ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    public function render_candidates_meta_box($post) {
        wp_nonce_field('sxs_comparison_candidates_nonce', 'sxs_comparison_candidates_nonce');
        
        $selected_candidates = get_post_meta($post->ID, '_sxs_selected_candidates', true);
        if (!is_array($selected_candidates)) {
            $selected_candidates = array();
        }

        // Get all candidates
        $candidates = get_posts(array(
            'post_type' => 'sxs_candidate',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ));

        ?>
        <div class="sxs-meta-row">
            <h3 class="sxs-section-title"><?php _e('Candidate Selection', 'sxs-candidate-comparison'); ?></h3>
            
            <p class="description">
                <?php _e('Choose candidates to include in this comparison. For best results, select 2-6 candidates.', 'sxs-candidate-comparison'); ?>
                <span class="sxs-tooltip">
                    <span class="sxs-tooltip-icon">?</span>
                    <span class="sxs-tooltip-text"><?php _e('Double-click to add/remove candidates or use the arrow buttons. Drag to reorder the selected candidates.', 'sxs-candidate-comparison'); ?></span>
                </span>
            </p>
            
            <div class="sxs-candidate-selector">
                <div class="sxs-available-candidates">
                    <h4><?php _e('Available Candidates', 'sxs-candidate-comparison'); ?></h4>
                    <input type="text" id="sxs-candidate-search" class="sxs-candidate-search" placeholder="<?php _e('Search candidates...', 'sxs-candidate-comparison'); ?>">
                    <select multiple="multiple" size="10" class="sxs-candidates-list" id="sxs-available-candidates">
                        <?php foreach ($candidates as $candidate) : ?>
                            <?php if (!in_array($candidate->ID, $selected_candidates)) : ?>
                                <option value="<?php echo esc_attr($candidate->ID); ?>">
                                    <?php echo esc_html($candidate->post_title); ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <p class="description">
                        <?php _e('Double-click to add | Hold Ctrl/Cmd to select multiple', 'sxs-candidate-comparison'); ?>
                    </p>
                </div>

                <div class="sxs-candidate-controls">
                    <button type="button" class="button" id="sxs-add-candidate" title="<?php _e('Add Selected Candidates', 'sxs-candidate-comparison'); ?>">&rarr;</button>
                    <button type="button" class="button" id="sxs-remove-candidate" title="<?php _e('Remove Selected Candidates', 'sxs-candidate-comparison'); ?>">&larr;</button>
                </div>

                <div class="sxs-selected-candidates">
                    <h4><?php _e('Selected Candidates', 'sxs-candidate-comparison'); ?> <span class="sxs-required">*</span></h4>
                    <select multiple="multiple" size="10" class="sxs-candidates-list" id="sxs-selected-candidates" name="sxs_selected_candidates[]">
                        <?php foreach ($selected_candidates as $candidate_id) : ?>
                            <?php $candidate = get_post($candidate_id); ?>
                            <?php if ($candidate) : ?>
                                <option value="<?php echo esc_attr($candidate->ID); ?>" selected>
                                    <?php echo esc_html($candidate->post_title); ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <p class="description">
                        <?php _e('Double-click to remove | Drag to reorder', 'sxs-candidate-comparison'); ?>
                    </p>
                </div>
            </div>

            <?php if (empty($candidates)) : ?>
                <div class="sxs-error-message">
                    <?php 
                    printf(
                        __('No candidates available. <a href="%s">Add some candidates</a> first.', 'sxs-candidate-comparison'),
                        admin_url('post-new.php?post_type=sxs_candidate')
                    ); 
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($selected_candidates) && count($selected_candidates) < 2) : ?>
                <div class="sxs-error-message">
                    <?php _e('Please select at least 2 candidates for comparison.', 'sxs-candidate-comparison'); ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    public function render_companies_meta_box($post) {
        wp_nonce_field('sxs_comparison_companies_nonce', 'sxs_comparison_companies_nonce');
        
        $selected_company = get_post_meta($post->ID, '_sxs_selected_company', true);
        
        // Get all companies
        $companies = get_posts(array(
            'post_type' => 'sxs_company',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ));

        ?>
        <div class="sxs-meta-row">
            <h3 class="sxs-section-title"><?php _e('Company Selection', 'sxs-candidate-comparison'); ?></h3>
            
            <p class="description">
                <?php _e('Choose a company to include in this comparison. The company information will be displayed along with the candidates selected below.', 'sxs-candidate-comparison'); ?>
                <span class="sxs-tooltip">
                    <span class="sxs-tooltip-icon">?</span>
                    <span class="sxs-tooltip-text"><?php _e('Company information such as logo, header colors, and contact details will be shown in the comparison.', 'sxs-candidate-comparison'); ?></span>
                </span>
            </p>
            
            <div class="sxs-company-selector">
                <label for="sxs_selected_company">
                    <?php _e('Select Company', 'sxs-candidate-comparison'); ?>
                </label>
                
                <select id="sxs_selected_company" name="sxs_selected_company" class="widefat">
                    <option value=""><?php _e('-- None --', 'sxs-candidate-comparison'); ?></option>
                    <?php foreach ($companies as $company) : ?>
                        <option value="<?php echo esc_attr($company->ID); ?>" <?php selected($selected_company, $company->ID); ?>>
                            <?php echo esc_html($company->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <?php if (empty($companies)) : ?>
                    <div class="sxs-error-message">
                        <?php 
                        printf(
                            __('No companies available. <a href="%s">Add a company</a> first.', 'sxs-candidate-comparison'),
                            admin_url('post-new.php?post_type=sxs_company')
                        ); 
                        ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($selected_company)) : 
                    $company = get_post($selected_company);
                    if ($company) : 
                        // Get company details for preview
                        $header_color = get_post_meta($selected_company, '_sxs_company_header_color', true) ?: '#1C2856';
                        $text_color = get_post_meta($selected_company, '_sxs_company_text_color', true) ?: '#FFFFFF';
                        $logo_id = get_post_meta($selected_company, '_sxs_company_logo_id', true);
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
        </div>
        <?php
    }

    public function render_shortcode_meta_box($post) {
        // Get selected company and candidates
        $selected_company = get_post_meta($post->ID, '_sxs_selected_company', true);
        $selected_candidates = get_post_meta($post->ID, '_sxs_selected_candidates', true);
        
        $has_company = !empty($selected_company);
        $has_candidates = is_array($selected_candidates) && !empty($selected_candidates);
        
        ?>
        <div class="sxs-meta-row">
            <h3 class="sxs-section-title"><?php _e('Shortcode', 'sxs-candidate-comparison'); ?></h3>
            <p><?php _e('Use this shortcode to display the comparison:', 'sxs-candidate-comparison'); ?></p>
            <div class="sxs-shortcode-container">
                <code class="sxs-shortcode">[sxs_candidate_comparison set="<?php echo $post->ID; ?>"]</code>
                <button type="button" class="button sxs-copy-shortcode" data-clipboard-text="[sxs_candidate_comparison set=&quot;<?php echo $post->ID; ?>&quot;]">
                    <?php _e('Copy', 'sxs-candidate-comparison'); ?>
                </button>
            </div>
            <p class="description">
                <?php _e('Copy and paste this shortcode into any post or page where you want to display this comparison.', 'sxs-candidate-comparison'); ?>
            </p>
            
            <?php if ($has_company && $has_candidates): 
                $company = get_post($selected_company);
                $company_name = $company ? $company->post_title : __('Selected company', 'sxs-candidate-comparison');
                ?>
                <div class="sxs-notice sxs-notice-info">
                    <p>
                        <strong><?php _e('Summary:', 'sxs-candidate-comparison'); ?></strong> 
                        <?php 
                        printf(
                            __('This comparison includes %s and %d selected candidates.', 'sxs-candidate-comparison'),
                            '<strong>' . esc_html($company_name) . '</strong>',
                            count($selected_candidates)
                        ); 
                        ?>
                    </p>
                </div>
            <?php elseif ($has_company): ?>
                <div class="sxs-notice sxs-notice-warning">
                    <p>
                        <strong><?php _e('Warning:', 'sxs-candidate-comparison'); ?></strong> 
                        <?php _e('You have selected a company but no candidates. Please select candidates to compare.', 'sxs-candidate-comparison'); ?>
                    </p>
                </div>
            <?php elseif ($has_candidates): ?>
                <div class="sxs-notice sxs-notice-info">
                    <p>
                        <strong><?php _e('Candidate Only Mode:', 'sxs-candidate-comparison'); ?></strong> 
                        <?php printf(
                            _n('This comparison includes %d candidate without company branding.', 
                               'This comparison includes %d candidates without company branding.', 
                               count($selected_candidates), 
                               'sxs-candidate-comparison'),
                            count($selected_candidates)
                        ); ?>
                    </p>
                </div>
            <?php else: ?>
                <div class="sxs-notice sxs-notice-warning">
                    <p>
                        <strong><?php _e('Warning:', 'sxs-candidate-comparison'); ?></strong> 
                        <?php _e('No candidates selected. Please select candidates to compare.', 'sxs-candidate-comparison'); ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    public function save_meta_boxes($post_id, $post = null) {
        // Check if this is the correct post type
        if (!isset($post) || $post->post_type !== 'sxs_comparison') {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Verify candidate nonce
        if (isset($_POST['sxs_comparison_candidates_nonce']) &&
            wp_verify_nonce($_POST['sxs_comparison_candidates_nonce'], 'sxs_comparison_candidates_nonce')) {
            
            // Save selected candidates
            if (isset($_POST['sxs_selected_candidates'])) {
                $selected_candidates = array_map('intval', $_POST['sxs_selected_candidates']);
                update_post_meta($post_id, '_sxs_selected_candidates', $selected_candidates);
            } else {
                delete_post_meta($post_id, '_sxs_selected_candidates');
            }
        }
        
        // Verify company nonce
        if (isset($_POST['sxs_comparison_companies_nonce']) &&
            wp_verify_nonce($_POST['sxs_comparison_companies_nonce'], 'sxs_comparison_companies_nonce')) {
            
            // Save selected companies
            if (isset($_POST['sxs_selected_company'])) {
                $selected_company = intval($_POST['sxs_selected_company']);
                update_post_meta($post_id, '_sxs_selected_company', $selected_company);
            } else {
                delete_post_meta($post_id, '_sxs_selected_company');
            }
        }
        
        // Only save job-related data if jobs are enabled
        if (class_exists('SXS_Settings') && SXS_Settings::is_jobs_enabled()) {
            // Verify job selection nonce
            if (isset($_POST['sxs_job_selection_nonce']) &&
                wp_verify_nonce($_POST['sxs_job_selection_nonce'], 'sxs_job_selection_nonce')) {
                
                // Save selected job
                if (isset($_POST['sxs_selected_job'])) {
                    $selected_job = intval($_POST['sxs_selected_job']);
                    update_post_meta($post_id, '_sxs_selected_job', $selected_job);
                    
                    // If this is just a preview update, redirect back to the edit page
                    if (isset($_POST['sxs_preview_update'])) {
                        wp_redirect(admin_url('post.php?post=' . $post_id . '&action=edit'));
                        exit;
                    }
                } else {
                    delete_post_meta($post_id, '_sxs_selected_job');
                }
            }
        }
        
        // Verify layout settings nonce
        if (isset($_POST['sxs_layout_settings_nonce']) &&
            wp_verify_nonce($_POST['sxs_layout_settings_nonce'], 'sxs_layout_settings_nonce')) {
            
            // Save layout option - will only be used for footer
            if (isset($_POST['sxs_layout_option'])) {
                $layout_option = sanitize_text_field($_POST['sxs_layout_option']);
                update_post_meta($post_id, '_sxs_layout_option', $layout_option);
            }
        }
        
        // Verify job details nonce
        if (isset($_POST['sxs_comparison_job_nonce']) &&
            wp_verify_nonce($_POST['sxs_comparison_job_nonce'], 'sxs_comparison_job_nonce')) {
            
            // Save job details
            if (isset($_POST['sxs_job_title'])) {
                update_post_meta($post_id, '_sxs_job_title', sanitize_text_field($_POST['sxs_job_title']));
            }
            
            if (isset($_POST['sxs_job_location'])) {
                update_post_meta($post_id, '_sxs_job_location', sanitize_text_field($_POST['sxs_job_location']));
            }
            
            if (isset($_POST['sxs_job_description'])) {
                update_post_meta($post_id, '_sxs_job_description', wp_kses_post($_POST['sxs_job_description']));
            }
            
            if (isset($_POST['sxs_job_link'])) {
                update_post_meta($post_id, '_sxs_job_link', esc_url_raw($_POST['sxs_job_link']));
            }
        }

        // Save recruiters selection
        if (isset($_POST['sxs_recruiters_selection_nonce']) && 
            wp_verify_nonce($_POST['sxs_recruiters_selection_nonce'], 'sxs_recruiters_selection_nonce')) {
            
            if (isset($_POST['sxs_selected_recruiters']) && is_array($_POST['sxs_selected_recruiters'])) {
                $recruiters = array_map('intval', $_POST['sxs_selected_recruiters']);
                update_post_meta($post_id, '_sxs_selected_recruiters', $recruiters);
            } else {
                // If no recruiters selected, save empty array
                update_post_meta($post_id, '_sxs_selected_recruiters', array());
            }
        }

        // Save position brief and scorecard settings
        if (isset($_POST['sxs_position_brief_scorecard_nonce']) && 
            wp_verify_nonce($_POST['sxs_position_brief_scorecard_nonce'], 'sxs_position_brief_scorecard_nonce')) {
            
            // Save position brief settings
            $position_brief_enabled = isset($_POST['sxs_position_brief_enabled']) ? '1' : '0';
            update_post_meta($post_id, '_sxs_position_brief_enabled', $position_brief_enabled);
            
            if (isset($_POST['sxs_position_brief_url'])) {
                update_post_meta($post_id, '_sxs_position_brief_url', esc_url_raw($_POST['sxs_position_brief_url']));
            }
            
            // Save scorecard settings
            $scorecard_enabled = isset($_POST['sxs_scorecard_enabled']) ? '1' : '0';
            update_post_meta($post_id, '_sxs_scorecard_enabled', $scorecard_enabled);
            
            if (isset($_POST['sxs_scorecard_url'])) {
                update_post_meta($post_id, '_sxs_scorecard_url', esc_url_raw($_POST['sxs_scorecard_url']));
            }

            // Save buttons message
            if (isset($_POST['sxs_buttons_message'])) {
                update_post_meta($post_id, '_sxs_buttons_message', sanitize_text_field($_POST['sxs_buttons_message']));
            }
        }
    }

    public function modify_row_actions($actions, $post) {
        if ($post->post_type === 'sxs_comparison') {
            // Add view link that opens in new tab
            $actions['view'] = sprintf(
                '<a href="%s" target="_blank">%s</a>',
                esc_url(get_permalink($post->ID)),
                esc_html__('View Comparison', 'sxs-candidate-comparison')
            );
        }
        return $actions;
    }

    public function load_comparison_template($template) {
        if (is_singular('sxs_comparison')) {
            $custom_template = SXS_CC_PLUGIN_DIR . 'templates/single-sxs-comparison.php';
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }
        return $template;
    }

    public function enqueue_frontend_scripts() {
        if (is_singular('sxs_comparison')) {
            // Define components in order of loading
            $components = array('layout', 'header', 'comparison', 'cells', 'recruiter', 'buttons');
            
            // Enqueue each component's CSS
            foreach ($components as $component) {
                wp_enqueue_style(
                    'sxs-' . $component,
                    plugins_url('assets/css/frontend/components/_' . $component . '.css', dirname(__FILE__)),
                    array(),
                    filemtime(plugin_dir_path(dirname(__FILE__)) . 'assets/css/frontend/components/_' . $component . '.css')
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
            
            // Main JavaScript
            wp_enqueue_script(
                'sxs-candidate-comparison',
                plugins_url('assets/js/sxs-candidate-comparison.js', dirname(__FILE__)),
                array('jquery'),
                filemtime(plugin_dir_path(dirname(__FILE__)) . 'assets/js/sxs-candidate-comparison.js'),
                true
            );
        }
    }

    public function set_custom_columns($columns) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = $columns['title'];
        $new_columns['company'] = __('Company', 'sxs-candidate-comparison');
        $new_columns['candidates'] = __('Candidates', 'sxs-candidate-comparison');
        $new_columns['shortcode'] = __('Shortcode', 'sxs-candidate-comparison');
        $new_columns['view'] = __('View', 'sxs-candidate-comparison');
        $new_columns['date'] = $columns['date'];
        return $new_columns;
    }

    public function custom_column($column, $post_id) {
        switch ($column) {
            case 'company':
                $selected_company = get_post_meta($post_id, '_sxs_selected_company', true);
                
                if (!empty($selected_company)) {
                    $company = get_post($selected_company);
                    if ($company) {
                        $logo_id = get_post_meta($selected_company, '_sxs_company_logo_id', true);
                        $logo_html = '';
                        
                        if (!empty($logo_id)) {
                            $image = wp_get_attachment_image_src($logo_id, array(30, 30));
                            if ($image) {
                                $logo_html = '<img src="' . esc_url($image[0]) . '" alt="' . esc_attr__('Company Logo', 'sxs-candidate-comparison') . '" style="max-width: 30px; max-height: 30px; vertical-align: middle; margin-right: 5px; border-radius: 3px;">';
                            }
                        }
                        
                        echo $logo_html . '<strong>' . esc_html($company->post_title) . '</strong>';
                    } else {
                        echo '<em>' . __('Invalid company', 'sxs-candidate-comparison') . '</em>';
                    }
                } else {
                    echo '<em>' . __('None', 'sxs-candidate-comparison') . '</em>';
                }
                break;
                
            case 'candidates':
                $selected_candidates = get_post_meta($post_id, '_sxs_selected_candidates', true);
                
                if (is_array($selected_candidates) && !empty($selected_candidates)) {
                    $count = count($selected_candidates);
                    echo '<span class="sxs-badge">' . sprintf(_n('%d candidate', '%d candidates', $count, 'sxs-candidate-comparison'), $count) . '</span>';
                    
                    $candidate_names = array();
                    foreach ($selected_candidates as $candidate_id) {
                        $candidate = get_post($candidate_id);
                        if ($candidate) {
                            $candidate_names[] = $candidate->post_title;
                        }
                    }
                    
                    // Show first few candidates
                    $max_display = 3;
                    $display_names = array_slice($candidate_names, 0, $max_display);
                    $remaining = count($candidate_names) - $max_display;
                    
                    echo '<p>' . esc_html(implode(', ', $display_names));
                    if ($remaining > 0) {
                        echo ' <em>' . sprintf(_n('and %d more', 'and %d more', $remaining, 'sxs-candidate-comparison'), $remaining) . '</em>';
                    }
                    echo '</p>';
                } else {
                    echo '<em>' . __('None selected', 'sxs-candidate-comparison') . '</em>';
                }
                break;

            case 'shortcode':
                echo '<code>[sxs_candidate_comparison set="' . $post_id . '"]</code>';
                break;

            case 'view':
                printf(
                    '<a href="%s" target="_blank" class="button button-small">%s</a>',
                    esc_url(get_permalink($post_id)),
                    esc_html__('View', 'sxs-candidate-comparison')
                );
                break;
        }
    }
} 