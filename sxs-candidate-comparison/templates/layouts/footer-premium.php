<?php
/**
 * Template for premium footer layout
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

<div class="sxs-layout-footer sxs-layout-premium-footer">
    <div class="sxs-premium-footer-container">
        <div class="sxs-premium-cta-section" style="background-color: <?php echo esc_attr($company_header_color); ?>;">
            <div class="sxs-premium-cta-overlay"></div>
            
            <div class="sxs-premium-cta-content" style="color: <?php echo esc_attr($company_text_color); ?>;">
                <div class="sxs-premium-cta-left">
                    <?php if (!empty($company_logo_url)) : ?>
                    <div class="sxs-premium-footer-logo">
                        <img src="<?php echo esc_url($company_logo_url); ?>" alt="<?php echo $company ? esc_attr($company->post_title) : ''; ?> Logo">
                    </div>
                    <?php endif; ?>
                    
                    <div class="sxs-premium-footer-text">
                        <?php if (!empty($company)) : ?>
                        <h3 class="sxs-premium-footer-company"><?php echo esc_html($company->post_title); ?></h3>
                        <?php endif; ?>
                        
                        <?php if (!empty($job_title)) : ?>
                        <h4 class="sxs-premium-footer-job"><?php echo esc_html($job_title); ?></h4>
                        <?php endif; ?>
                        
                        <?php if (!empty($job_location)) : ?>
                        <div class="sxs-premium-footer-location">
                            <span class="sxs-icon">📍</span> <?php echo esc_html($job_location); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="sxs-premium-cta-right">
                    <div class="sxs-premium-cta-message">
                        <h3 class="sxs-premium-cta-heading">
                            <?php _e('Ready to take the next step?', 'sxs-candidate-comparison'); ?>
                        </h3>
                        <p class="sxs-premium-cta-subheading">
                            <?php _e('Apply now to join our team and make an impact.', 'sxs-candidate-comparison'); ?>
                        </p>
                    </div>
                    
                    <?php if (!empty($job_link)) : ?>
                    <div class="sxs-premium-button-wrapper">
                        <a href="<?php echo esc_url($job_link); ?>" class="sxs-premium-apply-button" target="_blank">
                            <?php _e('Apply for this Position', 'sxs-candidate-comparison'); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="sxs-premium-footer-info">
            <p class="sxs-premium-footer-disclaimer">
                <?php _e('This candidate comparison is a confidential document prepared by a professional recruiter. All information presented is based on candidate-provided details and recruiter assessment.', 'sxs-candidate-comparison'); ?>
            </p>
            
            <div class="sxs-premium-footer-meta">
                <div class="sxs-premium-prepared-for">
                    <?php if (!empty($company)) : ?>
                    <?php printf(
                        __('Prepared exclusively for %s', 'sxs-candidate-comparison'),
                        '<strong>' . esc_html($company->post_title) . '</strong>'
                    ); ?>
                    <?php endif; ?>
                </div>
                
                <div class="sxs-premium-date">
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
    .sxs-layout-premium-footer {
        margin-top: 80px;
        margin-bottom: 60px;
    }
    
    .sxs-premium-footer-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .sxs-premium-cta-section {
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        padding: 40px;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .sxs-premium-cta-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('<?php echo plugins_url("assets/images/pattern.png", dirname(dirname(__FILE__))); ?>');
        opacity: 0.1;
        z-index: 1;
    }
    
    .sxs-premium-cta-content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .sxs-premium-cta-left {
        display: flex;
        align-items: center;
        flex: 1;
    }
    
    .sxs-premium-footer-logo {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        background: white;
        border-radius: 8px;
        padding: 10px;
    }
    
    .sxs-premium-footer-logo img {
        max-width: 100%;
        max-height: 100%;
        display: block;
    }
    
    .sxs-premium-footer-text {
        padding-right: 20px;
    }
    
    .sxs-premium-footer-company {
        font-size: 18px;
        margin: 0 0 5px;
        font-weight: 600;
    }
    
    .sxs-premium-footer-job {
        font-size: 24px;
        margin: 0 0 10px;
        font-weight: 700;
    }
    
    .sxs-premium-footer-location {
        font-size: 16px;
        display: flex;
        align-items: center;
    }
    
    .sxs-premium-cta-right {
        text-align: center;
        padding-left: 40px;
        border-left: 1px solid rgba(255,255,255,0.2);
        flex: 0 0 40%;
    }
    
    .sxs-premium-cta-heading {
        font-size: 28px;
        margin: 0 0 10px;
        font-weight: 700;
    }
    
    .sxs-premium-cta-subheading {
        font-size: 16px;
        margin: 0 0 20px;
        opacity: 0.9;
    }
    
    .sxs-premium-apply-button {
        display: inline-block;
        padding: 14px 30px;
        background: #fff;
        color: <?php echo esc_attr($company_header_color); ?>;
        border-radius: 6px;
        font-weight: 700;
        font-size: 16px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .sxs-premium-apply-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        color: <?php echo esc_attr($company_header_color); ?>;
        text-decoration: none;
    }
    
    .sxs-premium-footer-info {
        border-top: 1px solid #eee;
        padding-top: 30px;
        font-size: 14px;
    }
    
    .sxs-premium-footer-disclaimer {
        color: #777;
        margin-bottom: 20px;
        text-align: center;
        font-style: italic;
    }
    
    .sxs-premium-footer-meta {
        display: flex;
        justify-content: space-between;
        color: #555;
    }
    
    .sxs-premium-prepared-for,
    .sxs-premium-date {
        font-size: 13px;
    }
    
    @media (max-width: 768px) {
        .sxs-premium-cta-content {
            flex-direction: column;
        }
        
        .sxs-premium-cta-left {
            margin-bottom: 30px;
            flex-direction: column;
            text-align: center;
        }
        
        .sxs-premium-footer-logo {
            margin: 0 auto 20px;
        }
        
        .sxs-premium-cta-right {
            padding-left: 0;
            border-left: none;
            border-top: 1px solid rgba(255,255,255,0.2);
            padding-top: 30px;
        }
        
        .sxs-premium-footer-text {
            padding-right: 0;
        }
        
        .sxs-premium-footer-meta {
            flex-direction: column;
            text-align: center;
        }
        
        .sxs-premium-prepared-for {
            margin-bottom: 10px;
        }
    }
</style> 