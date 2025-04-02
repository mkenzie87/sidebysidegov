<?php
/**
 * Template for displaying a single comparison set
 */

if (!defined('ABSPATH')) {
    exit;
}

// Add inline styles to ensure correct styling
?>
<style>
/* Ensure this CSS takes precedence */
.sxs-col-header {
    width: 200px !important;
    flex: 0 0 200px !important;
    padding: 20px !important;
    background: #FFFFFF !important;
    color: #F26724 !important;
    font-weight: bold !important;
    text-transform: uppercase !important;
    text-align: center !important;
    border: 1px solid #d0d0d0 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    word-wrap: break-word !important;
    overflow-wrap: break-word !important;
    hyphens: auto !important;
    line-height: 1.3 !important;
    position: sticky !important;
    left: 0 !important;
    z-index: 10 !important;
    box-shadow: 5px 0 5px -2px rgba(0,0,0,0.1) !important;
}

.sxs-comparison-header {
    background: #1C2856 !important;
}

.sxs-comparison-header .sxs-col-header {
    background: #F26724 !important;
    color: white !important;
    font-size: 18px !important;
    position: sticky !important;
    left: 0 !important;
    z-index: 20 !important;
    box-shadow: 5px 0 5px -2px rgba(0,0,0,0.1) !important;
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
    position: relative !important;
}

/* Row settings for horizontal scroll */
.sxs-row {
    display: flex !important;
    width: max-content !important;
    min-width: 100% !important;
    flex-wrap: nowrap !important;
    position: relative !important;
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

.sxs-comparison-wrapper {
    position: relative !important;
}
</style>
<?php

get_header();

while (have_posts()) :
    the_post();
    
    // Get the selected candidates
    $selected_candidates = get_post_meta(get_the_ID(), '_sxs_selected_candidates', true);
    
    if (!is_array($selected_candidates) || empty($selected_candidates)) {
        ?>
        <div class="sxs-comparison-wrapper">
            <div class="sxs-comparison-container">
                <div class="sxs-comparison-message">
                    <?php _e('No candidates selected for this comparison.', 'sxs-candidate-comparison'); ?>
                </div>
            </div>
        </div>
        <?php
    } else {
        // Get the candidates
        $candidates = get_posts(array(
            'post_type' => 'sxs_candidate',
            'posts_per_page' => -1,
            'post__in' => $selected_candidates,
            'orderby' => 'post__in',
        ));

        if (!empty($candidates)) :
            ?>
            <div class="sxs-comparison-wrapper">
                <div class="sxs-comparison-container">
                    <!-- Header Row -->
                    <div class="sxs-row sxs-comparison-header">
                        <div class="sxs-col-header">SIDE BY SIDE</div>
                        <?php foreach ($candidates as $candidate) : ?>
                            <div class="sxs-col">
                                <?php echo esc_html($candidate->post_title); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Body Section -->
                    <div class="sxs-comparison-body">
                        <!-- Current Company/Title -->
                        <div class="sxs-row">
                            <div class="sxs-col-header">CURRENT COMPANY/<br>TITLE</div>
                            <?php foreach ($candidates as $candidate) : ?>
                                <div class="sxs-col">
                                    <div class="company"><?php echo esc_html(get_post_meta($candidate->ID, '_sxs_current_company', true)); ?></div>
                                    <div class="title"><?php echo esc_html(get_post_meta($candidate->ID, '_sxs_current_title', true)); ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Degrees/Certifications -->
                        <div class="sxs-row">
                            <div class="sxs-col-header">DEGREES/<br>CERTIFICATIONS</div>
                            <?php foreach ($candidates as $candidate) : ?>
                                <div class="sxs-col">
                                    <ul class="sxs-degrees">
                                        <?php 
                                        $education = get_post_meta($candidate->ID, '_sxs_education', true);
                                        if (is_array($education) && !empty($education)) :
                                            foreach ($education as $edu) : ?>
                                                <li><?php echo esc_html($edu); ?></li>
                                            <?php endforeach;
                                        endif; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Years of Experience -->
                        <div class="sxs-row">
                            <div class="sxs-col-header">YEARS OF INDUSTRY<br>EXPERIENCE/ROLE<br>EXPERIENCE</div>
                            <?php foreach ($candidates as $candidate) : ?>
                                <div class="sxs-col">
                                    <div class="sxs-experience">
                                        <?php 
                                        $industry_exp = get_post_meta($candidate->ID, '_sxs_industry_experience', true);
                                        $role_exp = get_post_meta($candidate->ID, '_sxs_role_experience', true);
                                        echo esc_html($industry_exp) . ' years\' industry experience; ' . esc_html($role_exp) . ' years\' specific role experience';
                                        ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Relevant Experience -->
                        <div class="sxs-row">
                            <div class="sxs-col-header">RELEVANT EXPERIENCE<br>SUMMARY</div>
                            <?php foreach ($candidates as $candidate) : ?>
                                <div class="sxs-col">
                                    <ul class="sxs-list">
                                        <?php 
                                        $experience = get_post_meta($candidate->ID, '_sxs_relevant_experience', true);
                                        if (is_array($experience) && !empty($experience)) :
                                            foreach ($experience as $item) : ?>
                                                <li><?php echo esc_html($item); ?></li>
                                            <?php endforeach;
                                        endif; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Compensation -->
                        <div class="sxs-row">
                            <div class="sxs-col-header">COMPENSATION</div>
                            <?php foreach ($candidates as $candidate) : ?>
                                <div class="sxs-col">
                                    <div class="sxs-compensation">
                                        <?php 
                                        $current_base = get_post_meta($candidate->ID, '_sxs_current_base', true);
                                        $current_bonus = get_post_meta($candidate->ID, '_sxs_current_bonus', true);
                                        $application = get_post_meta($candidate->ID, '_sxs_application_compensation', true);
                                        
                                        if (!empty($current_base)) {
                                            echo 'Current: ' . esc_html($current_base);
                                            if (!empty($current_bonus)) {
                                                echo ' + ' . esc_html($current_bonus);
                                            }
                                            echo '<br>';
                                        }
                                        
                                        if (!empty($application)) {
                                            echo 'Application: ' . esc_html($application);
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        endif;
    }
endwhile;

get_footer(); 