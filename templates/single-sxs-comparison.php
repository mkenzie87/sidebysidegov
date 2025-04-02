<?php
/**
 * Template for displaying a single comparison set
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom function to load template parts from plugin directory
 */
function sxs_get_template_part($slug, $name = null, $args = array()) {
    $template = '';
    $name = (string) $name;
    
    // Look in plugin/templates/
    if ($name) {
        $template = SXS_CC_PLUGIN_DIR . "{$slug}-{$name}.php";
    } else {
        $template = SXS_CC_PLUGIN_DIR . "{$slug}.php";
    }
    
    if (file_exists($template)) {
        // Extract arguments if any
        if (!empty($args) && is_array($args)) {
            extract($args);
        }
        
        include $template;
    } else {
        echo "<!-- Template {$template} not found -->";
    }
}

// Get layout option
$layout_option = get_post_meta(get_the_ID(), '_sxs_layout_option', true);
if (empty($layout_option)) {
    $layout_option = 'standard'; // Default layout
}

// Always use standard layout for header
$header_layout = 'standard';

// Keep the saved layout for footer
$footer_layout = $layout_option;

// Get company information
$selected_company_id = get_post_meta(get_the_ID(), '_sxs_selected_company', true);
$company = !empty($selected_company_id) ? get_post($selected_company_id) : null;
$company_logo_id = !empty($company) ? get_post_meta($company->ID, '_sxs_company_logo_id', true) : '';
$company_logo_url = !empty($company_logo_id) ? wp_get_attachment_image_url($company_logo_id, 'medium') : '';
$company_header_color = !empty($company) ? get_post_meta($company->ID, '_sxs_company_header_color', true) : '#1C2856';
$company_text_color = !empty($company) ? get_post_meta($company->ID, '_sxs_company_text_color', true) : '#FFFFFF';
$company_location = !empty($company) ? get_post_meta($company->ID, '_sxs_company_location', true) : '';
$company_website = !empty($company) ? get_post_meta($company->ID, '_sxs_company_website', true) : '';
$company_cover_id = !empty($company) ? get_post_meta($company->ID, '_sxs_company_cover_id', true) : '';
$company_cover_url = !empty($company_cover_id) ? wp_get_attachment_image_url($company_cover_id, 'large') : '';

// Get job information only if jobs are enabled
$job = null;
$job_title = '';
$job_location = '';
$job_description = '';
$job_type = '';
$job_experience = '';
$job_education = '';
$job_application_url = '';
$job_link = '';

if (class_exists('SXS_Settings') && SXS_Settings::is_jobs_enabled()) {
    $selected_job_id = get_post_meta(get_the_ID(), '_sxs_selected_job', true);
    $job = !empty($selected_job_id) ? get_post($selected_job_id) : null;

    if (!empty($job)) {
        // Job exists, get its details
        $job_title = $job->post_title;
        $job_location = get_post_meta($job->ID, '_sxs_job_location', true);
        $job_description = get_post_meta($job->ID, '_sxs_job_description', true);
        $job_type = get_post_meta($job->ID, '_sxs_job_type', true);
        $job_experience = get_post_meta($job->ID, '_sxs_job_experience', true);
        $job_education = get_post_meta($job->ID, '_sxs_job_education', true);
        $job_application_url = get_post_meta($job->ID, '_sxs_job_application_url', true);
        
        // If job has a company, use that company's details
        $job_company_id = get_post_meta($job->ID, '_sxs_job_company_id', true);
        if (!empty($job_company_id)) {
            $company = get_post($job_company_id);
            $selected_company_id = $job_company_id;
            
            // Update company details
            if ($company) {
                $company_logo_id = get_post_meta($company->ID, '_sxs_company_logo_id', true);
                $company_logo_url = !empty($company_logo_id) ? wp_get_attachment_image_url($company_logo_id, 'medium') : '';
                $company_header_color = get_post_meta($company->ID, '_sxs_company_header_color', true) ?: '#1C2856';
                $company_text_color = get_post_meta($company->ID, '_sxs_company_text_color', true) ?: '#FFFFFF';
                $company_location = get_post_meta($company->ID, '_sxs_company_location', true);
                $company_website = get_post_meta($company->ID, '_sxs_company_website', true);
                $company_cover_id = get_post_meta($company->ID, '_sxs_company_cover_id', true);
                $company_cover_url = !empty($company_cover_id) ? wp_get_attachment_image_url($company_cover_id, 'large') : '';
            }
        }
        
        // Use job details for application link
        $job_link = !empty($job_application_url) ? $job_application_url : '';
    }
} else {
    // Jobs module is disabled, use the values from the comparison meta (for backward compatibility)
    $job_title = get_post_meta(get_the_ID(), '_sxs_job_title', true);
    $job_location = get_post_meta(get_the_ID(), '_sxs_job_location', true);
    $job_description = get_post_meta(get_the_ID(), '_sxs_job_description', true);
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
            // Display the selected layout header
            sxs_get_template_part('templates/layouts/header', $header_layout, array(
                'post_id' => get_the_ID(),
                'company' => $company,
                'company_logo_url' => $company_logo_url,
                'company_header_color' => $company_header_color,
                'company_text_color' => $company_text_color,
                'company_location' => $company_location,
                'company_website' => $company_website,
                'company_cover_url' => $company_cover_url,
                'job_title' => $job_title,
                'job_location' => $job_location,
                'job_description' => $job_description,
                'job_link' => $job_link,
                'job_type' => $job_type,
                'job_experience' => $job_experience,
                'job_education' => $job_education,
                'job' => $job
            ));
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
            // Display the selected layout footer
            sxs_get_template_part('templates/layouts/footer', $footer_layout, array(
                'company' => $company,
                'company_logo_url' => $company_logo_url,
                'company_header_color' => $company_header_color,
                'company_text_color' => $company_text_color,
                'company_location' => $company_location,
                'company_website' => $company_website,
                'job_title' => $job_title,
                'job_location' => $job_location,
                'job_description' => $job_description,
                'job_link' => $job_link,
                'job_type' => $job_type,
                'job_experience' => $job_experience,
                'job_education' => $job_education,
                'job' => $job
            ));
        endif;
    }
endwhile;

get_footer(); 