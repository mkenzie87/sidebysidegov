<?php
/**
 * Template for standard footer layout
 */

if (!defined('ABSPATH')) {
    exit;
}

// Extract variables
$company = $args['company'] ?? null;
$company_header_color = $args['company_header_color'] ?? '#1C2856';
$company_text_color = $args['company_text_color'] ?? '#FFFFFF';
$job_title = $args['job_title'] ?? '';
$job_link = $args['job_link'] ?? '';
?>

<div class="sxs-layout-footer sxs-layout-standard-footer">
    <div class="sxs-footer-container">
        <div class="sxs-footer-content">
            <div class="sxs-cta-section">
                <h3 class="sxs-cta-heading">
                    <?php echo !empty($job_title) 
                        ? sprintf(__('Interested in the %s position?', 'sxs-candidate-comparison'), esc_html($job_title)) 
                        : __('Interested in this position?', 'sxs-candidate-comparison'); 
                    ?>
                </h3>
                
                <?php if (!empty($job_link)) : ?>
                <div class="sxs-cta-button-wrapper">
                    <a href="<?php echo esc_url($job_link); ?>" class="sxs-cta-button" style="background-color: <?php echo esc_attr($company_header_color); ?>; color: <?php echo esc_attr($company_text_color); ?>;">
                        <?php _e('Apply Now', 'sxs-candidate-comparison'); ?>
                    </a>
                </div>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($company)) : ?>
            <div class="sxs-company-footer">
                <p class="sxs-company-footer-text">
                    <?php printf(
                        __('This candidate comparison was prepared for %s by a recruitment professional.', 'sxs-candidate-comparison'),
                        '<strong>' . esc_html($company->post_title) . '</strong>'
                    ); ?>
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .sxs-layout-standard-footer {
        margin-top: 60px;
        margin-bottom: 40px;
    }
    
    .sxs-footer-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .sxs-footer-content {
        border-top: 1px solid #eee;
        padding-top: 30px;
    }
    
    .sxs-cta-section {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .sxs-cta-heading {
        font-size: 24px;
        margin-bottom: 20px;
        color: #333;
    }
    
    .sxs-cta-button-wrapper {
        margin-top: 15px;
    }
    
    .sxs-cta-button {
        display: inline-block;
        padding: 12px 30px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
    }
    
    .sxs-cta-button:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }
    
    .sxs-company-footer {
        text-align: center;
        padding: 20px;
        background: #f9f9f9;
        border-radius: 6px;
    }
    
    .sxs-company-footer-text {
        color: #666;
        margin: 0;
        font-size: 14px;
    }
</style> 