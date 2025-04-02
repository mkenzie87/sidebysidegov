<?php
/**
 * Template for minimal header layout
 */

if (!defined('ABSPATH')) {
    exit;
}

// Extract variables
$company = $args['company'] ?? null;
$company_logo_url = $args['company_logo_url'] ?? '';
$company_header_color = $args['company_header_color'] ?? '#1C2856';
$company_text_color = $args['company_text_color'] ?? '#FFFFFF';
$job_title = $args['job_title'] ?? '';
$job_location = $args['job_location'] ?? '';
?>

<div class="sxs-layout-header sxs-layout-minimal-header">
    <div class="sxs-minimal-header-container" style="border-top-color: <?php echo esc_attr($company_header_color); ?>;">
        <div class="sxs-minimal-header-content">
            <?php if (!empty($company_logo_url)) : ?>
            <div class="sxs-minimal-logo">
                <img src="<?php echo esc_url($company_logo_url); ?>" alt="<?php echo $company ? esc_attr($company->post_title) : ''; ?> Logo">
            </div>
            <?php endif; ?>
            
            <div class="sxs-minimal-title-area">
                <?php if (!empty($job_title)) : ?>
                <h1 class="sxs-minimal-job-title"><?php echo esc_html($job_title); ?></h1>
                <?php endif; ?>
                
                <div class="sxs-minimal-meta">
                    <?php if (!empty($company)) : ?>
                    <div class="sxs-minimal-company">
                        <?php echo esc_html($company->post_title); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($job_location)) : ?>
                    <div class="sxs-minimal-location">
                        <span class="sxs-minimal-icon">📍</span> <?php echo esc_html($job_location); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .sxs-layout-minimal-header {
        margin-bottom: 30px;
    }
    
    .sxs-minimal-header-container {
        border-top: 4px solid;
        padding: 20px 0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .sxs-minimal-header-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        padding: 0 15px;
    }
    
    .sxs-minimal-logo {
        margin-right: 20px;
        max-width: 80px;
    }
    
    .sxs-minimal-logo img {
        max-width: 100%;
        height: auto;
    }
    
    .sxs-minimal-title-area {
        flex: 1;
    }
    
    .sxs-minimal-job-title {
        font-size: 24px;
        margin: 0 0 10px;
        font-weight: 600;
        color: #333;
    }
    
    .sxs-minimal-meta {
        display: flex;
        align-items: center;
        color: #666;
        font-size: 14px;
    }
    
    .sxs-minimal-company {
        font-weight: 500;
        margin-right: 20px;
    }
    
    .sxs-minimal-location {
        display: flex;
        align-items: center;
    }
    
    .sxs-minimal-icon {
        margin-right: 5px;
    }
    
    @media (max-width: 768px) {
        .sxs-minimal-header-content {
            flex-direction: column;
            text-align: center;
        }
        
        .sxs-minimal-logo {
            margin: 0 auto 15px;
        }
        
        .sxs-minimal-meta {
            flex-direction: column;
            gap: 5px;
        }
        
        .sxs-minimal-company {
            margin-right: 0;
            margin-bottom: 5px;
        }
    }
</style> 