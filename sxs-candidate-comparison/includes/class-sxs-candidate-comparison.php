<?php

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

        // Main plugin CSS
        wp_enqueue_style(
            'sxs-candidate-comparison',
            plugins_url('assets/css/sxs-candidate-comparison.css', dirname(__FILE__)),
            array(),
            SXS_CC_VERSION
        );
        
        // Add comparison view CSS - this ensures it's loaded for shortcodes too
        wp_enqueue_style(
            'sxs-comparison-view',
            plugins_url('assets/css/sxs-comparison-view.css', dirname(__FILE__)),
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
            plugins_url('assets/css/sxs-candidate-admin.css', dirname(__FILE__)),
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

        // If a comparison set ID is provided, use its candidates
        if (!empty($atts['set'])) {
            $selected_candidates = get_post_meta($atts['set'], '_sxs_selected_candidates', true);
            if (is_array($selected_candidates) && !empty($selected_candidates)) {
                $args = array(
                    'post_type' => 'sxs_candidate',
                    'posts_per_page' => -1,
                    'post__in' => $selected_candidates,
                    'orderby' => 'post__in',
                );
            } else {
                return '<p>' . __('No candidates selected for this comparison.', 'sxs-candidate-comparison') . '</p>';
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
        }

        $candidates = get_posts($args);

        if (empty($candidates)) {
            return '<p>' . __('No candidates found.', 'sxs-candidate-comparison') . '</p>';
        }

        ob_start();
        
        // Add inline styles
        ?>
        <style>
        /* Ensure correct styling for left column */
        .sxs-col-header {
            width: 200px !important;
            flex: 0 0 200px !important;
            padding: 20px !important;
            background: #FFFFFF !important;
            color: #F26724 !important;
            font-weight: bold !important;
            text-transform: uppercase !important;
            text-align: center !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            hyphens: auto !important;
            line-height: 1.3 !important;
        }
        
        .sxs-comparison-header {
            background: #1C2856 !important;
        }
        
        .sxs-comparison-header .sxs-col-header {
            background: #F26724 !important;
            color: white !important;
            font-size: 18px !important;
        }
        
        .sxs-comparison-header .sxs-col {
            background: #1C2856 !important;
            color: white !important;
            border-color: rgba(255,255,255,0.2) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            min-height: 60px !important;
        }
        
        /* Alternating column colors */
        .sxs-comparison-body .sxs-row .sxs-col:nth-child(even) {
            background-color: #F5F5F5 !important;
        }
        
        .sxs-comparison-body .sxs-row .sxs-col:nth-child(odd) {
            background-color: #FFFFFF !important;
        }
        
        /* Make container full width */
        .sxs-comparison-container {
            width: auto !important;
            min-width: 100% !important;
        }
        
        /* Row settings for horizontal scroll */
        .sxs-row {
            display: flex !important;
            width: max-content !important;
            min-width: 100% !important;
            flex-wrap: nowrap !important;
        }
        
        /* Fixed column widths */
        .sxs-col {
            flex: 0 0 350px !important;
            width: 350px !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: normal !important;
            hyphens: auto !important;
            text-align: center !important;
        }
        
        /* Cell text wrapping */
        .sxs-col {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: normal !important;
            hyphens: auto !important;
            text-align: center !important;
        }
        
        /* Add text centering for all content */
        .company, .title, .sxs-experience, .sxs-compensation, 
        .sxs-list, .sxs-degrees, .sxs-list li, .sxs-degrees li {
            text-align: center !important;
        }
        
        .sxs-list, .sxs-degrees {
            padding-left: 0 !important;
            list-style-position: inside !important;
        }
        
        /* Sticky positioning for left column */
        .sxs-comparison-wrapper {
            position: relative !important;
        }
        
        .sxs-comparison-container {
            position: relative !important;
        }
        
        .sxs-row {
            position: relative !important;
        }
        
        .sxs-col-header {
            position: sticky !important;
            left: 0 !important;
            z-index: 10 !important;
            box-shadow: 5px 0 5px -2px rgba(0,0,0,0.1) !important;
        }
        
        .sxs-comparison-header .sxs-col-header {
            position: sticky !important;
            left: 0 !important;
            z-index: 20 !important;
            box-shadow: 5px 0 5px -2px rgba(0,0,0,0.1) !important;
        }
        </style>
        <?php
        
        ?>
        <div class="sxs-comparison-wrapper">
            <div class="sxs-comparison-header">
                <div class="sxs-row">
                    <div class="sxs-col sxs-col-header">
                        <?php _e('SIDE BY SIDE', 'sxs-candidate-comparison'); ?>
                        <button class="sxs-print-button button">
                            <?php _e('Print Comparison', 'sxs-candidate-comparison'); ?>
                        </button>
                    </div>
                    <?php foreach ($candidates as $candidate) : ?>
                        <div class="sxs-col">
                            <h3><?php echo esc_html($candidate->post_title); ?></h3>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="sxs-comparison-body">
                <!-- Current Company/Title -->
                <div class="sxs-row">
                    <div class="sxs-col sxs-col-header"><?php _e('CURRENT COMPANY/<br>TITLE', 'sxs-candidate-comparison'); ?></div>
                    <?php foreach ($candidates as $candidate) : ?>
                        <div class="sxs-col">
                            <p class="company"><?php echo esc_html(get_post_meta($candidate->ID, '_sxs_current_company', true)); ?></p>
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