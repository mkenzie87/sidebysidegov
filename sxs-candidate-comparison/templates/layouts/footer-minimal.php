<?php
/**
 * Template for minimal footer layout
 */

if (!defined('ABSPATH')) {
    exit;
}

// Extract variables
$company = $args['company'] ?? null;
$company_header_color = $args['company_header_color'] ?? '#1C2856';
$company_text_color = $args['company_text_color'] ?? '#FFFFFF';
$job_link = $args['job_link'] ?? '';
?>

<div class="sxs-layout-footer sxs-layout-minimal-footer">
    <div class="sxs-minimal-footer-container">
        <?php if (!empty($job_link)) : ?>
        <div class="sxs-minimal-cta">
            <a href="<?php echo esc_url($job_link); ?>" class="sxs-minimal-button" style="background-color: <?php echo esc_attr($company_header_color); ?>; color: <?php echo esc_attr($company_text_color); ?>;" target="_blank">
                <?php _e('Apply Now', 'sxs-candidate-comparison'); ?>
            </a>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($company)) : ?>
        <div class="sxs-minimal-footer-note">
            <?php printf(
                __('Prepared for %s', 'sxs-candidate-comparison'),
                '<strong>' . esc_html($company->post_title) . '</strong>'
            ); ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .sxs-layout-minimal-footer {
        margin-top: 40px;
        margin-bottom: 40px;
    }
    
    .sxs-minimal-footer-container {
        max-width: 1200px;
        margin: 0 auto;
        text-align: center;
        padding: 0 15px;
    }
    
    .sxs-minimal-cta {
        margin-bottom: 20px;
    }
    
    .sxs-minimal-button {
        display: inline-block;
        padding: 10px 25px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.2s ease;
    }
    
    .sxs-minimal-button:hover {
        opacity: 0.9;
        text-decoration: none;
    }
    
    .sxs-minimal-footer-note {
        font-size: 13px;
        color: #777;
    }
</style> 