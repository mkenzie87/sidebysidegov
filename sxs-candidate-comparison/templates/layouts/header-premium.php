<?php
/**
 * Template for premium header layout
 */

if (!defined('ABSPATH')) {
    exit;
}

// Extract variables
$company = $args['company'] ?? null;
$company_logo_url = $args['company_logo_url'] ?? '';
$company_header_color = $args['company_header_color'] ?? '#1C2856';
$company_text_color = $args['company_text_color'] ?? '#FFFFFF';
$company_cover_url = $args['company_cover_url'] ?? '';
$company_location = $args['company_location'] ?? '';
$company_website = $args['company_website'] ?? '';
$job_title = $args['job_title'] ?? '';
$job_location = $args['job_location'] ?? '';
$job_description = $args['job_description'] ?? '';
?>

<div class="sxs-layout-header sxs-layout-premium-header">
    <div class="sxs-premium-hero" style="background-color: <?php echo esc_attr($company_header_color); ?>;">
        <?php if (!empty($company_cover_url)) : ?>
        <div class="sxs-cover-image" style="background-image: url('<?php echo esc_url($company_cover_url); ?>');">
            <div class="sxs-overlay" style="background-color: <?php echo esc_attr($company_header_color); ?>; opacity: 0.7;"></div>
        </div>
        <?php endif; ?>
        
        <div class="sxs-premium-header-content" style="color: <?php echo esc_attr($company_text_color); ?>;">
            <?php if (!empty($company_logo_url)) : ?>
            <div class="sxs-premium-company-logo">
                <img src="<?php echo esc_url($company_logo_url); ?>" alt="<?php echo $company ? esc_attr($company->post_title) : ''; ?> Logo">
            </div>
            <?php endif; ?>
            
            <div class="sxs-premium-details">
                <?php if (!empty($company)) : ?>
                <div class="sxs-premium-company">
                    <h2 class="sxs-premium-company-name"><?php echo esc_html($company->post_title); ?></h2>
                    
                    <?php if (!empty($company_location) || !empty($company_website)) : ?>
                    <div class="sxs-premium-company-meta">
                        <?php if (!empty($company_location)) : ?>
                        <div class="sxs-premium-company-location">
                            <span class="sxs-icon">📍</span> <?php echo esc_html($company_location); ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($company_website)) : ?>
                        <div class="sxs-premium-company-website">
                            <span class="sxs-icon">🌐</span> 
                            <a href="<?php echo esc_url($company_website); ?>" target="_blank" rel="noopener noreferrer" style="color: <?php echo esc_attr($company_text_color); ?>;">
                                <?php echo esc_html(preg_replace('#^https?://#', '', $company_website)); ?>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($job_title)) : ?>
                <div class="sxs-premium-job">
                    <h1 class="sxs-premium-job-title"><?php echo esc_html($job_title); ?></h1>
                    
                    <?php if (!empty($job_location)) : ?>
                    <div class="sxs-premium-job-location">
                        <span class="sxs-icon">📍</span> <?php echo esc_html($job_location); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php if (!empty($job_description)) : ?>
    <div class="sxs-premium-job-description">
        <div class="sxs-premium-section-title"><?php _e('About This Position', 'sxs-candidate-comparison'); ?></div>
        <div class="sxs-premium-description-content">
            <?php echo wpautop($job_description); ?>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="sxs-premium-intro">
        <div class="sxs-premium-intro-content">
            <h3 class="sxs-premium-intro-title"><?php _e('Candidate Comparison', 'sxs-candidate-comparison'); ?></h3>
            <p class="sxs-premium-intro-text">
                <?php _e('Below is a side-by-side comparison of qualified candidates for your position. Each candidate has been carefully screened and evaluated based on their qualifications, experience, and fit for the role.', 'sxs-candidate-comparison'); ?>
            </p>
        </div>
    </div>
</div>

<style>
    .sxs-layout-premium-header {
        margin-bottom: 50px;
    }
    
    .sxs-premium-hero {
        position: relative;
        overflow: hidden;
        border-radius: 8px 8px 0 0;
        padding: 60px 40px;
        min-height: 250px;
    }
    
    .sxs-cover-image {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-size: cover;
        background-position: center;
        z-index: 1;
    }
    
    .sxs-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 2;
    }
    
    .sxs-premium-header-content {
        position: relative;
        z-index: 3;
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: flex-start;
    }
    
    .sxs-premium-company-logo {
        flex: 0 0 150px;
        margin-right: 40px;
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .sxs-premium-company-logo img {
        width: 100%;
        height: auto;
        display: block;
    }
    
    .sxs-premium-details {
        flex: 1;
    }
    
    .sxs-premium-company-name {
        font-size: 24px;
        margin: 0 0 5px;
        font-weight: 700;
    }
    
    .sxs-premium-company-meta {
        margin-bottom: 30px;
        display: flex;
        flex-wrap: wrap;
    }
    
    .sxs-premium-company-location,
    .sxs-premium-company-website {
        margin-right: 20px;
        margin-top: 10px;
        display: flex;
        align-items: center;
    }
    
    .sxs-icon {
        margin-right: 5px;
    }
    
    .sxs-premium-job-title {
        font-size: 36px;
        margin: 0 0 10px;
        font-weight: 700;
    }
    
    .sxs-premium-job-location {
        font-size: 18px;
        display: flex;
        align-items: center;
    }
    
    .sxs-premium-job-description {
        background: #f8f9fa;
        padding: 30px 40px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .sxs-premium-section-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 15px;
        color: #333;
    }
    
    .sxs-premium-description-content {
        color: #555;
        line-height: 1.7;
    }
    
    .sxs-premium-intro {
        max-width: 1200px;
        margin: 40px auto 0;
        border-top: 1px solid #eee;
        padding-top: 30px;
    }
    
    .sxs-premium-intro-title {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
    }
    
    .sxs-premium-intro-text {
        color: #666;
        line-height: 1.6;
        font-size: 16px;
    }
    
    @media (max-width: 768px) {
        .sxs-premium-header-content {
            flex-direction: column;
            text-align: center;
        }
        
        .sxs-premium-company-logo {
            margin: 0 auto 30px;
        }
        
        .sxs-premium-company-meta {
            justify-content: center;
        }
    }
</style> 