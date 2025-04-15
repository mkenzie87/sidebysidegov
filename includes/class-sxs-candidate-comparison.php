<?php
//Comment here
if (!defined('ABSPATH')) {
    exit;
}

class SXS_Candidate_Comparison {
    
    public function init() {
        add_shortcode('sxs_candidate_comparison', array($this, 'render_comparison'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function enqueue_scripts() {
        // Enqueue jQuery UI
        wp_enqueue_script('jquery-ui-tooltip');
        wp_enqueue_style(
            'jquery-ui',
            '//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css'
        );

        // Enqueue Font Awesome
        wp_enqueue_style(
            'font-awesome',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
            array(),
            '6.5.1'
        );

        // Main plugin CSS
        wp_enqueue_style(
            'sxs-candidate-comparison',
            plugins_url('assets/css/frontend/comparison-view.css', dirname(__FILE__)),
            array(),
            SXS_CC_VERSION
        );

        wp_enqueue_script(
            'sxs-candidate-comparison',
            plugins_url('assets/js/sxs-candidate-comparison.js', dirname(__FILE__)),
            array('jquery', 'jquery-ui-tooltip'),
            SXS_CC_VERSION,
            true
        );

        wp_localize_script('sxs-candidate-comparison', 'sxsCC', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('sxs_cc_nonce')
        ));
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

    public function render_comparison($atts) {
        $atts = shortcode_atts(array(
            'ids' => '',
            'category' => '',
            'limit' => 6,
            'set' => 0,
        ), $atts);

        // If a comparison set ID is provided, use its candidates and company
        if (!empty($atts['set'])) {
            $set_id = intval($atts['set']);
            $selected_candidates = get_post_meta($set_id, '_sxs_selected_candidates', true);
            $selected_company = get_post_meta($set_id, '_sxs_selected_company', true);
            
            // Always use selected candidates
            if (is_array($selected_candidates) && !empty($selected_candidates)) {
                $args = array(
                    'post_type' => 'sxs_candidate',
                    'posts_per_page' => -1,
                    'post__in' => $selected_candidates,
                    'orderby' => 'post__in',
                );
                
                $candidates = get_posts($args);
                
                if (empty($candidates)) {
                    return '<p>' . __('No candidates found for this comparison.', 'sxs-candidate-comparison') . '</p>';
                }
            } else {
                return '<p>' . __('No candidates selected for this comparison.', 'sxs-candidate-comparison') . '</p>';
            }
            
            // Get company data if selected
            $company_data = false;
            if (!empty($selected_company)) {
                $company = get_post($selected_company);
                if ($company && $company->post_type === 'sxs_company') {
                    $company_data = array(
                        'id' => $company->ID,
                        'name' => $company->post_title,
                        'logo' => '',
                        'logo_id' => get_post_meta($company->ID, '_sxs_company_logo_id', true),
                        'cover_id' => get_post_meta($company->ID, '_sxs_company_cover_id', true),
                        'location' => get_post_meta($company->ID, '_sxs_company_location', true),
                        'website' => get_post_meta($company->ID, '_sxs_company_website', true),
                        'industry' => get_post_meta($company->ID, '_sxs_company_industry', true),
                        'description' => get_post_meta($company->ID, '_sxs_company_description', true),
                        'header_color' => get_post_meta($company->ID, '_sxs_company_header_color', true) ?: '#1C2856',
                        'text_color' => get_post_meta($company->ID, '_sxs_company_text_color', true) ?: '#FFFFFF'
                    );
                    
                    // Get company logo
                    if (!empty($company_data['logo_id'])) {
                        $logo_img = wp_get_attachment_image_src($company_data['logo_id'], array(50, 50));
                        if ($logo_img) {
                            $company_data['logo'] = $logo_img[0];
                        }
                    }
                    
                    // Get company cover
                    if (!empty($company_data['cover_id'])) {
                        $cover_img = wp_get_attachment_image_src($company_data['cover_id'], 'medium');
                        if ($cover_img) {
                            $company_data['cover'] = $cover_img[0];
                        }
                    }
                }
            }
        } else {
            // Use the traditional shortcode parameters
            $args = array(
                'post_type' => 'sxs_candidate',
                'posts_per_page' => intval($atts['limit']),
                'orderby' => 'title',
                'order' => 'ASC',
            );

            if (!empty($atts['ids'])) {
                $ids = array_map('trim', explode(',', $atts['ids']));
                $args['post__in'] = $ids;
                $args['orderby'] = 'post__in';
            } elseif (!empty($atts['category'])) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'sxs_candidate_category',
                        'field' => 'slug',
                        'terms' => $atts['category'],
                    ),
                );
            }
            
            $candidates = get_posts($args);
            
            if (empty($candidates)) {
                return '<p>' . __('No candidates found.', 'sxs-candidate-comparison') . '</p>';
            }
            
            // No company data for shortcode without set
            $company_data = false;
        }

        ob_start();
        
        // Get button settings and URLs
        $enable_position_brief = get_post_meta($atts['set'], '_sxs_enable_position_brief', true);
        $enable_scorecard = get_post_meta($atts['set'], '_sxs_enable_scorecard', true);
        $position_brief_url = get_post_meta($atts['set'], '_sxs_position_brief_url', true);
        $scorecard_url = get_post_meta($atts['set'], '_sxs_scorecard_url', true);
        
        ?>
        <div class="sxs-comparison-wrapper">
            <?php 
            // Include header section
            include(plugin_dir_path(dirname(__FILE__)) . 'templates/partials/comparison-header.php');
            
            // Include recruiter section
            $post_id = $atts['set'];
            include(plugin_dir_path(dirname(__FILE__)) . 'templates/partials/recruiter-section.php');
            ?>

            <div class="sxs-comparison-body">
                <!-- Current Company/Title -->
                <div class="sxs-row">
                    <div class="sxs-col sxs-col-header"><?php _e('CURRENT COMPANY/<br>TITLE', 'sxs-candidate-comparison'); ?></div>
                    <?php foreach ($candidates as $candidate) : ?>
                        <div class="sxs-col">
                            <?php if ($company_data && !empty($company_data['cover'])) : ?>
                                <div class="sxs-company-cover">
                                    <img src="<?php echo esc_url($company_data['cover']); ?>" alt="<?php echo esc_attr($company_data['name']); ?> Cover">
                                </div>
                            <?php endif; ?>
                            
                            <div class="sxs-company-details">
                                <?php if ($company_data && !empty($company_data['logo'])) : ?>
                                    <div class="sxs-company-logo-detail">
                                        <img src="<?php echo esc_url($company_data['logo']); ?>" alt="<?php echo esc_attr($company_data['name']); ?> Logo">
                                    </div>
                                <?php endif; ?>
                                <div class="sxs-company-info">
                                    <p class="company">
                                        <?php 
                                        // Use company name from comparison set if available, otherwise use candidate's company
                                        if ($company_data) {
                                            echo esc_html($company_data['name']);
                                        } else {
                                            echo esc_html(get_post_meta($candidate->ID, '_sxs_current_company', true));
                                        }
                                        ?>
                                    </p>
                                    <?php if ($company_data && !empty($company_data['location'])) : ?>
                                        <p class="location"><i class="dashicons dashicons-location"></i> <?php echo esc_html($company_data['location']); ?></p>
                                    <?php endif; ?>
                                    <?php if ($company_data && !empty($company_data['website'])) : ?>
                                        <p class="website">
                                            <a href="<?php echo esc_url($company_data['website']); ?>" target="_blank">
                                                <i class="dashicons dashicons-admin-links"></i> <?php _e('Website', 'sxs-candidate-comparison'); ?>
                                            </a>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <p class="title"><?php echo esc_html(get_post_meta($candidate->ID, '_sxs_current_title', true)); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Education -->
                <div class="sxs-row">
                    <div class="sxs-col sxs-col-header"><?php _e('DEGREES/<br>CERTIFICATIONS', 'sxs-candidate-comparison'); ?></div>
                    <?php foreach ($candidates as $candidate) : ?>
                        <div class="sxs-col">
                            <?php
                            $education = get_post_meta($candidate->ID, '_sxs_education', true);
                            if (is_array($education)) :
                                echo '<ul class="sxs-list">';
                                foreach ($education as $edu) :
                                    echo '<li>' . esc_html($edu) . '</li>';
                                endforeach;
                                echo '</ul>';
                            endif;
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Experience -->
                <div class="sxs-row">
                    <div class="sxs-col sxs-col-header"><?php _e('YEARS OF INDUSTRY<br>EXPERIENCE/ROLE<br>EXPERIENCE', 'sxs-candidate-comparison'); ?></div>
                    <?php foreach ($candidates as $candidate) : ?>
                        <div class="sxs-col">
                            <?php
                            $industry_exp = get_post_meta($candidate->ID, '_sxs_industry_experience', true);
                            $role_exp = get_post_meta($candidate->ID, '_sxs_role_experience', true);
                            printf(
                                __('%d years\' industry experience; %d years\' specific role experience', 'sxs-candidate-comparison'),
                                intval($industry_exp),
                                intval($role_exp)
                            );
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Relevant Experience -->
                <div class="sxs-row">
                    <div class="sxs-col sxs-col-header"><?php _e('RELEVANT EXPERIENCE<br>SUMMARY', 'sxs-candidate-comparison'); ?></div>
                    <?php foreach ($candidates as $candidate) : ?>
                        <div class="sxs-col">
                            <?php
                            $experience = get_post_meta($candidate->ID, '_sxs_relevant_experience', true);
                            if (is_array($experience)) :
                                echo '<ul class="sxs-list">';
                                foreach ($experience as $exp) :
                                    echo '<li>' . esc_html($exp) . '</li>';
                                endforeach;
                                echo '</ul>';
                            endif;
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Compensation -->
                <div class="sxs-row">
                    <div class="sxs-col sxs-col-header"><?php _e('COMPENSATION', 'sxs-candidate-comparison'); ?></div>
                    <?php foreach ($candidates as $candidate) : ?>
                        <div class="sxs-col">
                            <?php
                            $current_base = get_post_meta($candidate->ID, '_sxs_current_base', true);
                            $current_bonus = get_post_meta($candidate->ID, '_sxs_current_bonus', true);
                            $application = get_post_meta($candidate->ID, '_sxs_application_compensation', true);
                            ?>
                            <p><strong><?php _e('Current:', 'sxs-candidate-comparison'); ?></strong> 
                               <?php echo esc_html($current_base); ?>
                               <?php if (!empty($current_bonus)) : ?>
                                   <?php echo ' + ' . esc_html($current_bonus); ?>
                               <?php endif; ?>
                            </p>
                            <?php if (!empty($application)) : ?>
                                <p><strong><?php _e('Application:', 'sxs-candidate-comparison'); ?></strong> 
                                   <?php echo esc_html($application); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
} 