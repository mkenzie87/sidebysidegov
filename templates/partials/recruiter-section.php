<?php
/**
 * Recruiter Section
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get recruiter data
$recruiters = get_post_meta($post_id, '_sxs_selected_recruiters', true);
if (!empty($recruiters) && is_array($recruiters)) :
    $recruiter = get_post($recruiters[0]); // Get first recruiter
    if ($recruiter) :
        $photo = get_the_post_thumbnail_url($recruiter->ID, 'thumbnail');
        $title = get_post_meta($recruiter->ID, '_team_title', true);
        $phone = get_post_meta($recruiter->ID, '_team_phone', true);
        $linkedin = get_post_meta($recruiter->ID, '_team_linkedin', true);
        $email = get_post_meta($recruiter->ID, '_team_email', true);
?>
<div class="sxs-recruiter-section">
    <div class="sxs-recruiter-card">
        <?php if ($photo) : ?>
        <div class="sxs-recruiter-photo">
            <img src="<?php echo esc_url($photo); ?>" alt="<?php echo esc_attr($recruiter->post_title); ?>">
        </div>
        <?php endif; ?>
        <div class="sxs-recruiter-info">
            <div class="sxs-recruiter-name"><?php echo esc_html($recruiter->post_title); ?></div>
            <?php if ($title) : ?>
            <div class="sxs-recruiter-title"><?php echo esc_html($title); ?></div>
            <?php endif; ?>
            <?php if ($phone) : ?>
            <div class="sxs-recruiter-contact"><?php echo esc_html($phone); ?></div>
            <?php endif; ?>
            <div class="sxs-recruiter-social">
                <?php if ($linkedin) : ?>
                <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener">
                    <i class="fab fa-linkedin"></i>
                </a>
                <?php endif; ?>
                <?php if ($email) : ?>
                <a href="mailto:<?php echo esc_attr($email); ?>">
                    <i class="fas fa-envelope"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="sxs-position-info">
        <div class="sxs-position-title">
            <?php echo esc_html(get_the_title($post_id)); ?>
        </div>

        <div class="sxs-action-buttons">
            <?php if ($enable_position_brief && $position_brief_url) : ?>
            <a href="<?php echo esc_url($position_brief_url); ?>" class="sxs-button position-brief" target="_blank">
                Position Brief <i class="fa-light fa-arrow-right"></i>
            </a>
            <?php endif; ?>
            <?php if ($enable_scorecard && $scorecard_url) : ?>
            <a href="<?php echo esc_url($scorecard_url); ?>" class="sxs-button scorecard" target="_blank">
                Scorecard <i class="fa-light fa-arrow-right"></i>
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; endif; ?> 