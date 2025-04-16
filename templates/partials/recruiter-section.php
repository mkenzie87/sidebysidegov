<?php
/**
 * Recruiter Section Template
 * Displays recruiter information in a banner style layout
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get recruiter data from comparison set
$set_id = isset($atts['set']) ? $atts['set'] : get_the_ID();
$recruiters = get_post_meta($set_id, '_sxs_selected_recruiters', true);

// Get placeholder image
$placeholder = get_field('team_image_fallback', 'option');
if (empty($placeholder)) {
    $placeholder = SXS_CC_PLUGIN_URL . 'assets/images/placeholder-profile.png';
}

// Custom field settings
$title_data = get_field('team_title_data_field', 'option');
$phone_data = get_field('team_phone_data_field', 'option');
$linkedin_data = get_field('linkedin_data_filed', 'option');
$email_data = get_field('team_email_data_field', 'option');

// Position buttons info
$enable_position_brief = get_post_meta($set_id, '_sxs_position_brief_enabled', true);
$position_brief_url = get_post_meta($set_id, '_sxs_position_brief_url', true);
$enable_scorecard = get_post_meta($set_id, '_sxs_scorecard_enabled', true);
$scorecard_url = get_post_meta($set_id, '_sxs_scorecard_url', true);
?>

<?php if (!empty($recruiters) && is_array($recruiters)) : ?>
<div class="sxs-recruiter-banner">
    <?php 
    // Add slider if multiple recruiters
    $slider_class = count($recruiters) > 1 ? 'sxs-recruiter-slider' : '';
    ?>
    <div class="<?php echo $slider_class; ?>">
        <?php foreach ($recruiters as $recruiter_id) : 
            // Get recruiter data
            $thumbnail = get_the_post_thumbnail_url($recruiter_id, 'medium');
            $recruiter_name = get_the_title($recruiter_id);
            
            // Use placeholder if no image
            if (empty($thumbnail)) {
                $thumbnail = $placeholder;
            }
            
            // Get additional info for hover/details
            $title = get_field($title_data, $recruiter_id);
            $phone = get_field($phone_data, $recruiter_id);
            $linkedin = get_field($linkedin_data, $recruiter_id);
            $email = get_field($email_data, $recruiter_id);
        ?>
        <div class="sxs-recruiter-slide">
            <div class="sxs-recruiter-photo">
                <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($recruiter_name); ?>">
            </div>
            <h2 class="sxs-recruiter-name"><?php echo esc_html($recruiter_name); ?></h2>
            
            <?php if (count($recruiters) > 1) : ?>
            <div class="sxs-slide-controls">
                <button class="sxs-prev-slide" aria-label="Previous recruiter"><i class="fas fa-chevron-left"></i></button>
                <button class="sxs-next-slide" aria-label="Next recruiter"><i class="fas fa-chevron-right"></i></button>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    
    <?php if ($enable_position_brief && !empty($position_brief_url) || $enable_scorecard && !empty($scorecard_url)) : ?>
    <div class="sxs-action-buttons-container">
        <?php if ($enable_position_brief && !empty($position_brief_url)) : ?>
            <a href="<?php echo esc_url($position_brief_url); ?>" class="sxs-button position-brief" target="_blank">Position Brief</a>
        <?php endif; ?>
        
        <?php if ($enable_scorecard && !empty($scorecard_url)) : ?>
            <a href="<?php echo esc_url($scorecard_url); ?>" class="sxs-button scorecard" target="_blank">Scorecard</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?> 