<?php
/**
 * Recruiter Section Template
 * Displays recruiter information in a horizontal card layout
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get recruiter data from comparison set
$set_id = isset($atts['set']) ? $atts['set'] : get_the_ID();
$recruiters = get_post_meta($set_id, '_sxs_selected_recruiters', true);

// Position info
$job_title = get_post_meta($set_id, '_sxs_job_title', true);
$enable_position_brief = get_post_meta($set_id, '_sxs_position_brief_enabled', true);
$position_brief_url = get_post_meta($set_id, '_sxs_position_brief_url', true);
$enable_scorecard = get_post_meta($set_id, '_sxs_scorecard_enabled', true);
$scorecard_url = get_post_meta($set_id, '_sxs_scorecard_url', true);

// Custom field settings
$title_data = get_field('team_title_data_field', 'option');
$phone_data = get_field('team_phone_data_field', 'option');
$linkedin_data = get_field('linkedin_data_filed', 'option');
$email_data = get_field('team_email_data_field', 'option');

// Get placeholder image
$placeholder = get_field('team_image_fallback', 'option');
if (empty($placeholder)) {
    $placeholder = SXS_CC_PLUGIN_URL . 'assets/images/placeholder-profile.png';
}
?>

<div class="sxs-recruiter-section">
    <?php if (!empty($recruiters) && is_array($recruiters)) : ?>
        <div class="sxs-position-info">
            <?php if (!empty($job_title)) : ?>
                <h2 class="sxs-position-title"><?php echo esc_html($job_title); ?></h2>
            <?php endif; ?>
            
            <div class="sxs-action-buttons">
                <?php if ($enable_position_brief && !empty($position_brief_url)) : ?>
                    <a href="<?php echo esc_url($position_brief_url); ?>" class="sxs-button position-brief" target="_blank">Position Brief</a>
                <?php endif; ?>
                
                <?php if ($enable_scorecard && !empty($scorecard_url)) : ?>
                    <a href="<?php echo esc_url($scorecard_url); ?>" class="sxs-button scorecard" target="_blank">Scorecard</a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="sxs-recruiters-container">
            <?php foreach ($recruiters as $recruiter_id) : 
                // Get recruiter data
                $thumbnail = get_the_post_thumbnail_url($recruiter_id, 'thumbnail');
                $title = get_field($title_data, $recruiter_id);
                $phone = get_field($phone_data, $recruiter_id);
                $linkedin = get_field($linkedin_data, $recruiter_id);
                $email = get_field($email_data, $recruiter_id);
                $recruiter_name = get_the_title($recruiter_id);
                
                // Use placeholder if no image
                if (empty($thumbnail)) {
                    $thumbnail = $placeholder;
                }
            ?>
                <div class="sxs-recruiter-card">
                    <div class="sxs-recruiter-photo">
                        <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($recruiter_name); ?>">
                    </div>
                    <div class="sxs-recruiter-info">
                        <h3 class="sxs-recruiter-name"><?php echo esc_html($recruiter_name); ?></h3>
                        
                        <?php if (!empty($title)) : ?>
                            <p class="sxs-recruiter-title"><?php echo esc_html($title); ?></p>
                        <?php endif; ?>
                        
                        <?php if (!empty($phone)) : ?>
                            <p class="sxs-recruiter-contact"><?php echo esc_html($phone); ?></p>
                        <?php endif; ?>
                        
                        <div class="sxs-recruiter-social">
                            <?php if (!empty($linkedin)) : ?>
                                <a href="<?php echo esc_url($linkedin); ?>" target="_blank" aria-label="LinkedIn Profile"><i class="fab fa-linkedin-in"></i></a>
                            <?php endif; ?>
                            
                            <?php if (!empty($email)) : ?>
                                <a href="mailto:<?php echo esc_attr($email); ?>" target="_blank" aria-label="Email Contact"><i class="fas fa-envelope"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div> 