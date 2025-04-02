<?php
/**
 * Template for branded header layout
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

<div class="sxs-layout-header sxs-layout-branded-header">
    <div class="sxs-branded-hero" style="background-color: <?php echo esc_attr($company_header_color); ?>; color: <?php echo esc_attr($company_text_color); ?>;">
        <?php if (!empty($company_cover_url)) : ?>
        <div class="sxs-branded-cover" style="background-image: url('<?php echo esc_url($company_cover_url); ?>');">
            <div class="sxs-branded-overlay" style="background-color: <?php echo esc_attr($company_header_color); ?>; opacity: 0.8;"></div>
        </div>
        <?php endif; ?>
        
        <div class="sxs-branded-content">
            <div class="sxs-branded-top">
                <?php if (!empty($company_logo_url)) : ?>
                <div class="sxs-branded-logo">
                    <img src="<?php echo esc_url($company_logo_url); ?>" alt="<?php echo $company ? esc_attr($company->post_title) : ''; ?> Logo">
                </div>
                <?php endif; ?>
                
                <?php if (!empty($company)) : ?>
                <div class="sxs-branded-company-name">
                    <h2><?php echo esc_html($company->post_title); ?></h2>
                    
                    <?php if (!empty($company_website)) : ?>
                    <div class="sxs-branded-website">
                        <a href="<?php echo esc_url($company_website); ?>" target="_blank" rel="noopener noreferrer" style="color: <?php echo esc_attr($company_text_color); ?>;">
                            <?php echo esc_html(preg_replace('#^https?://#', '', $company_website)); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="sxs-branded-job-info">
                <?php if (!empty($job_title)) : ?>
                <h1 class="sxs-branded-job-title"><?php echo esc_html($job_title); ?></h1>
                <?php endif; ?>
                
                <?php if (!empty($job_location)) : ?>
                <div class="sxs-branded-job-location">
                    <span class="sxs-branded-icon">📍</span> <?php echo esc_html($job_location); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php if (!empty($job_description)) : ?>
    <div class="sxs-branded-description">
        <div class="sxs-branded-description-container">
            <div class="sxs-branded-description-header">
                <div class="sxs-branded-description-icon" style="background-color: <?php echo esc_attr($company_header_color); ?>; color: <?php echo esc_attr($company_text_color); ?>;">
                    💼
                </div>
                <h3 class="sxs-branded-description-title"><?php _e('Position Overview', 'sxs-candidate-comparison'); ?></h3>
            </div>
            
            <div class="sxs-branded-description-content">
                <?php echo wpautop($job_description); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="sxs-branded-candidates-intro">
        <div class="sxs-branded-intro-container">
            <div class="sxs-branded-intro-header">
                <div class="sxs-branded-intro-icon" style="background-color: <?php echo esc_attr($company_header_color); ?>; color: <?php echo esc_attr($company_text_color); ?>;">
                    👥
                </div>
                <h3 class="sxs-branded-intro-title"><?php _e('Top Candidates', 'sxs-candidate-comparison'); ?></h3>
            </div>
            
            <div class="sxs-branded-intro-content">
                <p>
                    <?php _e('Below are highly qualified candidates for your consideration. Each has been thoroughly evaluated based on experience, skills, and cultural fit.', 'sxs-candidate-comparison'); ?>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    .sxs-layout-branded-header {
        margin-bottom: 50px;
    }
    
    .sxs-branded-hero {
        position: relative;
        min-height: 260px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    
    .sxs-branded-cover {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-size: cover;
        background-position: center;
        z-index: 1;
    }
    
    .sxs-branded-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 2;
    }
    
    .sxs-branded-content {
        position: relative;
        z-index: 3;
        max-width: 1200px;
        width: 100%;
        padding: 40px 20px;
        text-align: center;
    }
    
    .sxs-branded-top {
        margin-bottom: 30px;
    }
    
    .sxs-branded-logo {
        max-width: 180px;
        margin: 0 auto 20px;
        background: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 6px 12px rgba(0,0,0,0.1);
    }
    
    .sxs-branded-logo img {
        width: 100%;
        height: auto;
        display: block;
    }
    
    .sxs-branded-company-name h2 {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 10px;
    }
    
    .sxs-branded-website a {
        text-decoration: underline;
        font-size: 16px;
    }
    
    .sxs-branded-job-title {
        font-size: 36px;
        font-weight: 800;
        margin: 0 0 15px;
        letter-spacing: -0.5px;
    }
    
    .sxs-branded-job-location {
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .sxs-branded-icon {
        margin-right: 8px;
    }
    
    .sxs-branded-description,
    .sxs-branded-candidates-intro {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    .sxs-branded-description {
        margin-top: -30px;
        margin-bottom: 40px;
    }
    
    .sxs-branded-description-container,
    .sxs-branded-intro-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        padding: 25px 30px;
    }
    
    .sxs-branded-description-header,
    .sxs-branded-intro-header {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .sxs-branded-description-icon,
    .sxs-branded-intro-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 20px;
    }
    
    .sxs-branded-description-title,
    .sxs-branded-intro-title {
        font-size: 22px;
        font-weight: 700;
        margin: 0;
        color: #333;
    }
    
    .sxs-branded-description-content,
    .sxs-branded-intro-content {
        color: #555;
        line-height: 1.7;
    }
    
    @media (max-width: 768px) {
        .sxs-branded-logo {
            max-width: 120px;
        }
        
        .sxs-branded-job-title {
            font-size: 28px;
        }
    }
</style> 