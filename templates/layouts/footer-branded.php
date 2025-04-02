<?php
/**
 * Template for branded footer layout
 */

if (!defined('ABSPATH')) {
    exit;
}

// Extract variables
$company = $args['company'] ?? null;
$company_logo_url = $args['company_logo_url'] ?? '';
$company_header_color = $args['company_header_color'] ?? '#1C2856';
$company_text_color = $args['company_text_color'] ?? '#FFFFFF';
$company_location = $args['company_location'] ?? '';
$company_website = $args['company_website'] ?? '';
$job_title = $args['job_title'] ?? '';
$job_location = $args['job_location'] ?? '';
$job_link = $args['job_link'] ?? '';
?>

<div class="sxs-layout-footer sxs-layout-branded-footer">
    <div class="sxs-branded-footer-container">
        <div class="sxs-branded-prefooter">
            <div class="sxs-branded-prefooter-content">
                <div class="sxs-branded-next-steps">
                    <div class="sxs-branded-steps-header">
                        <div class="sxs-branded-steps-icon" style="background-color: <?php echo esc_attr($company_header_color); ?>; color: <?php echo esc_attr($company_text_color); ?>;">
                            🔍
                        </div>
                        <h3 class="sxs-branded-steps-title"><?php _e('Your Next Steps', 'sxs-candidate-comparison'); ?></h3>
                    </div>
                    
                    <div class="sxs-branded-steps-content">
                        <ol class="sxs-branded-steps-list">
                            <li><?php _e('Review the candidate profiles above', 'sxs-candidate-comparison'); ?></li>
                            <li><?php _e('Consider which candidates best match your requirements', 'sxs-candidate-comparison'); ?></li>
                            <li><?php _e('Apply for the position to request candidate interviews', 'sxs-candidate-comparison'); ?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="sxs-branded-cta-band" style="background-color: <?php echo esc_attr($company_header_color); ?>; color: <?php echo esc_attr($company_text_color); ?>;">
            <div class="sxs-branded-cta-content">
                <div class="sxs-branded-cta-text">
                    <h3><?php _e('Ready to meet these candidates?', 'sxs-candidate-comparison'); ?></h3>
                    <p><?php _e('Apply now to schedule interviews with your preferred candidates.', 'sxs-candidate-comparison'); ?></p>
                </div>
                
                <?php if (!empty($job_link)) : ?>
                <div class="sxs-branded-cta-action">
                    <a href="<?php echo esc_url($job_link); ?>" class="sxs-branded-apply-now" target="_blank">
                        <?php _e('Apply Now', 'sxs-candidate-comparison'); ?> <span class="sxs-branded-arrow">→</span>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="sxs-branded-footer-credits">
            <div class="sxs-branded-credits-content">
                <?php if (!empty($company)) : ?>
                <div class="sxs-branded-prepared-for">
                    <?php printf(
                        __('Prepared exclusively for %s', 'sxs-candidate-comparison'),
                        '<strong>' . esc_html($company->post_title) . '</strong>'
                    ); ?>
                    <?php if (!empty($job_title)) : ?>
                        <?php printf(
                            __(' - %s position', 'sxs-candidate-comparison'),
                            esc_html($job_title)
                        ); ?>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <div class="sxs-branded-footer-disclaimer">
                    <?php _e('This is a confidential candidate comparison document provided by a professional recruiter.', 'sxs-candidate-comparison'); ?>
                </div>
                
                <div class="sxs-branded-date">
                    <?php printf(
                        __('Generated on %s', 'sxs-candidate-comparison'),
                        date_i18n(get_option('date_format'))
                    ); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .sxs-layout-branded-footer {
        margin-top: 70px;
    }
    
    .sxs-branded-prefooter {
        max-width: 1200px;
        margin: 0 auto 50px;
        padding: 0 20px;
    }
    
    .sxs-branded-prefooter-content {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        padding: 30px;
    }
    
    .sxs-branded-steps-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .sxs-branded-steps-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 20px;
    }
    
    .sxs-branded-steps-title {
        font-size: 22px;
        font-weight: 700;
        margin: 0;
        color: #333;
    }
    
    .sxs-branded-steps-content {
        padding-left: 55px;
    }
    
    .sxs-branded-steps-list {
        margin: 0;
        padding-left: 20px;
        color: #555;
    }
    
    .sxs-branded-steps-list li {
        margin-bottom: 10px;
        padding-left: 5px;
    }
    
    .sxs-branded-cta-band {
        padding: 40px 20px;
    }
    
    .sxs-branded-cta-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .sxs-branded-cta-text {
        flex: 1;
    }
    
    .sxs-branded-cta-text h3 {
        font-size: 26px;
        font-weight: 700;
        margin: 0 0 10px;
    }
    
    .sxs-branded-cta-text p {
        margin: 0;
        opacity: 0.9;
        font-size: 16px;
    }
    
    .sxs-branded-cta-action {
        margin-left: 30px;
    }
    
    .sxs-branded-apply-now {
        display: inline-block;
        background: white;
        color: <?php echo esc_attr($company_header_color); ?>;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 16px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    
    .sxs-branded-apply-now:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        color: <?php echo esc_attr($company_header_color); ?>;
        text-decoration: none;
    }
    
    .sxs-branded-arrow {
        display: inline-block;
        transition: transform 0.3s ease;
        margin-left: 5px;
    }
    
    .sxs-branded-apply-now:hover .sxs-branded-arrow {
        transform: translateX(5px);
    }
    
    .sxs-branded-footer-credits {
        background: #f8f9fa;
        padding: 25px 20px;
        text-align: center;
    }
    
    .sxs-branded-credits-content {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .sxs-branded-prepared-for {
        font-size: 15px;
        color: #555;
        margin-bottom: 10px;
    }
    
    .sxs-branded-footer-disclaimer {
        font-size: 13px;
        color: #777;
        margin-bottom: 10px;
        font-style: italic;
    }
    
    .sxs-branded-date {
        font-size: 12px;
        color: #999;
    }
    
    @media (max-width: 768px) {
        .sxs-branded-cta-content {
            flex-direction: column;
            text-align: center;
        }
        
        .sxs-branded-cta-text {
            margin-bottom: 20px;
        }
        
        .sxs-branded-cta-action {
            margin-left: 0;
        }
        
        .sxs-branded-steps-content {
            padding-left: 0;
        }
        
        .sxs-branded-steps-header {
            flex-direction: column;
            text-align: center;
        }
        
        .sxs-branded-steps-icon {
            margin: 0 auto 15px;
        }
    }
</style>