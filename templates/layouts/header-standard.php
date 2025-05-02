<?php
/**
 * Template for standard header layout
 */

if (!defined('ABSPATH')) {
    exit;
}

// Extract variables
$company = $args['company'] ?? null;
$company_logo_url = $args['company_logo_url'] ?? '';
$company_header_color = $args['company_header_color'] ?? '#1C2856';
$company_text_color = $args['company_text_color'] ?? '#FFFFFF';

// If no company cover URL, use the default from settings or fallback to the default image
$company_cover_url = '';

// First try to get company-specific cover if available
if (!empty($args['company_cover_url'])) {
    $company_cover_url = $args['company_cover_url'];
} 
// Then try to get the global default from settings
else if (class_exists('SXS_Settings')) {
    $default_bg = SXS_Settings::get_default_header_background();
    if (!empty($default_bg)) {
        $company_cover_url = $default_bg;
    }
}

// If still empty, use plugin default
if (empty($company_cover_url)) {
    $company_cover_url = plugins_url('assets/images/default-cover.jpg', dirname(dirname(__FILE__)));
}

// Ensure jQuery and Slick are enqueued
wp_enqueue_script('jquery');
wp_enqueue_script('slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), '1.8.1', true);
wp_enqueue_style('slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', array(), '1.8.1');
wp_enqueue_style('slick-theme', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css', array('slick'), '1.8.1');
wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

// Initialize slick carousel
wp_add_inline_script('slick', "
    jQuery(document).ready(function(){
        jQuery('.sxs-recruiter-slider').slick({
            dots: true,
            arrows: false,
            infinite: true,
            speed: 300,
            slidesToShow: 1,
            slidesToScroll: 1,
            draggable: true,
            autoplay: true,
            adaptiveHeight: true,
        });
    });
");
?>

<style>
.sxs-hero-header {
    position: relative;
    width: 100%;
    height: auto;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    background-color: #1C2856;
    flex-direction: column;
    padding: 150px 20px 60px;
}

.sxs-hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: top right;
    filter: brightness(0.7);
    z-index: 1;
}

.sxs-hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    margin-bottom: 40px;
    width: 100%;
    max-width: 1200px;
}

.sxs-hero-logo {
    width: 180px;
    height: 180px;
    border-radius: 50%;
    background-color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    overflow: hidden;
    padding: 20px;
}

.sxs-hero-logo img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.sxs-hero-title {
    margin-top: 30px;
    color: white;
    font-size: 64px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-shadow: 0 2px 5px rgba(0,0,0,0.3);
}

.sxs-split-content {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    flex-wrap: wrap;
    gap: 40px;
    align-items: center;
    justify-content: space-between;
    padding: 50px 20px 0 20px;
}

.sxs-split-recruiters {
    flex: 1;
    min-width: 300px;
    max-width: calc(50% - 20px);
}

.sxs-split-text {
    flex: 1;
    min-width: 300px;
    max-width: calc(50% - 20px);
    /* display: flex;
    flex-direction: column;
    justify-content: center; */
}

.sxs-split-text h1,
.sxs-split-text h2,
.sxs-split-text h3,
.sxs-split-text h4,
.sxs-split-text h5,
.sxs-split-text h6 {
    color: black;
    margin-top: 0;
    margin-bottom: 20px;
}

.sxs-split-text p {
    margin-bottom: 20px;
}

.sxs-split-text a {
    color: #F26724;
    text-decoration: none;
    transition: color 0.3s ease;
}


.sxs-split-text ul,
.sxs-split-text ol {
    margin-left: 20px;
    margin-bottom: 20px;
}

.sxs-split-text li {
    margin-bottom: 10px;
}

.sxs-split-text img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 20px 0;
}

.sxs-split-text blockquote {
    border-left: 4px solid #F26724;
    margin: 20px 0;
    padding: 10px 20px;
    font-style: italic;
    background: rgba(255, 255, 255, 0.1);
}

.sxs-split-text table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

.sxs-split-text table th,
.sxs-split-text table td {
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 10px;
    text-align: left;
}

.sxs-split-text table th {
    background: rgba(255, 255, 255, 0.1);
}

/* Position Buttons */
.position-buttons {
    margin-top: 30px;
    display: flex;
    gap: 15px;
}

.position-button {
    padding: 12px 24px;
    border-radius: 50px; /* Pill shape */
    font-size: 16px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.position-button.primary {
    background: #F26724;
    color: white;
    border: none;
}

.position-button.secondary {
    background: white;
    color: #1C2856;
    border: 1px solid #F26724; /* Orange border */
}

.position-button i {
    font-size: 14px;
    margin-left: 5px;
}

/* Slick Dots Customization */
.sxs-recruiter-slider .slick-dots {
    bottom: -25px;
}

.sxs-recruiter-slider .slick-dots li button:before {
    color: rgba(255, 255, 255, 0.8);
    opacity: 1;
    font-size: 10px;
}

.sxs-recruiter-slider .slick-dots li.slick-active button:before {
    color: rgba(255, 255, 255, 1);
    opacity: 1;
}

.position-button:hover {
    background: #1c2856;
    color: white;
    border: 1px solid #1c2856;
    text-decoration: none !important;
}

@media screen and (max-width: 768px) {
    .sxs-hero-header {
        padding: 150px 20px 20px;
    }
    
    .sxs-hero-logo {
        width: 150px;
        height: 150px;
    }
    
    .sxs-hero-title {
        font-size: 36px;
    }

    .sxs-split-content {
        flex-direction: column-reverse;
        gap: 30px;
    }

    .sxs-split-recruiters,
    .sxs-split-text {
        max-width: 100%;
    }

    .sxs-split-text {
        font-size: 15px;
    }
    
    .sxs-split-text h1 { font-size: 28px; }
    .sxs-split-text h2 { font-size: 24px; }
    .sxs-split-text h3 { font-size: 20px; }
    .sxs-split-text h4 { font-size: 18px; }
    .sxs-split-text h5 { font-size: 16px; }
    .sxs-split-text h6 { font-size: 14px; }

    /* Deprecated recruiter styles 
    section.recruiter-info {
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 20px;
    }
    */

    .position-buttons {
        flex-direction: column;
    }
}
</style>

<div class="sxs-hero-header">
    <div class="sxs-hero-bg" style="background-image: url('<?php echo esc_url($company_cover_url); ?>')"></div>
    <div class="sxs-hero-content">
        <div class="sxs-hero-logo">
            <?php if (!empty($company_logo_url)) : ?>
                <img src="<?php echo esc_url($company_logo_url); ?>" alt="<?php echo esc_attr($company ? $company->post_title : __('Company Logo', 'sxs-candidate-comparison')); ?>">
            <?php else : ?>
                <div style="font-size: 80px; color: #1C2856;">
                    <?php echo esc_html(mb_substr($company ? $company->post_title : 'C', 0, 1)); ?>
                </div>
            <?php endif; ?>
        </div>
        <h1 class="sxs-hero-title"><?php echo esc_html($company ? $company->post_title : ''); ?></h1>
    </div>
</div>

<div class="sxs-split-content">
    <div class="sxs-split-recruiters">
        <?php 
        // Get recruiters from the comparison set or from the company
        $featured_posts = array();
        
        // First, check if recruiters are directly selected in the comparison
        if (isset($args['post_id'])) {
            $comparison_id = $args['post_id'];
            $selected_recruiters = get_post_meta($comparison_id, '_sxs_selected_recruiters', true);
            
            if (!empty($selected_recruiters) && is_array($selected_recruiters)) {
                $featured_posts = array_map(function($id) {
                    return get_post($id);
                }, $selected_recruiters);
                
                // Filter out any invalid posts
                $featured_posts = array_filter($featured_posts, function($post) {
                    return $post !== null && $post->post_status === 'publish';
                });
            }
        }
        
        // If no recruiters selected in the comparison, try to get from ACF
        if (empty($featured_posts) && function_exists('get_field')) {
            $featured_posts = get_field('recruiters_company', $company ? $company->ID : 0);
        }
        
        // Fallback to meta fields if ACF isn't available or no recruiters found
        if (empty($featured_posts) && $company) {
            $recruiters_meta = get_post_meta($company->ID, '_sxs_company_recruiters', true);
            if (!empty($recruiters_meta) && is_array($recruiters_meta)) {
                $featured_posts = array_map(function($id) {
                    return get_post($id);
                }, $recruiters_meta);
            }
        }
        
        if (!empty($featured_posts)): 
        ?>
        <div class="sxs-recruiter-banner">
            <?php 
            // Add slider if multiple recruiters
            $slider_class = count($featured_posts) > 1 ? 'sxs-recruiter-slider' : '';
            ?>
            <div class="<?php echo $slider_class; ?>">
                <?php foreach($featured_posts as $featured_post):
                    // Try to get data from ACF fields if available
                    $title = '';
                    $phone = '';
                    $linkedin = '';
                    $email = '';
                    $thumbnail = '';
                    $placeholder = '';
                    
                    if (function_exists('get_field')) {
                        $title_data = get_field('team_title_data_field', 'option');
                        $phone_data = get_field('team_phone_data_field', 'option');
                        $linkedin_data = get_field('linkedin_data_filed', 'option');
                        $email_data = get_field('team_email_data_field', 'option');
                        
                        $title = get_field($title_data, $featured_post->ID);
                        $phone = get_field($phone_data, $featured_post->ID);
                        $linkedin = get_field($linkedin_data, $featured_post->ID);
                        $email = get_field($email_data, $featured_post->ID);
                        $placeholder = get_field('team_image_fallback', 'option');
                    }
                    
                    // Fallback to meta fields if ACF data isn't available
                    // if (empty($title)) $title = get_post_meta($featured_post->ID, '_sxs_recruiter_title', true);
                    // if (empty($phone)) $phone = get_post_meta($featured_post->ID, '_sxs_recruiter_phone', true);
                    // if (empty($linkedin)) $linkedin = get_post_meta($featured_post->ID, '_sxs_recruiter_linkedin', true);
                    // if (empty($email)) $email = get_post_meta($featured_post->ID, '_sxs_recruiter_email', true);
                    
                    $thumbnail = get_the_post_thumbnail_url($featured_post->ID);
                    if (empty($thumbnail)) $thumbnail = get_post_meta($featured_post->ID, '_sxs_recruiter_image', true);
                    
                    $recruiter_name = get_the_title($featured_post->ID);
                ?>
                <div class="sxs-recruiter-slide">
                    <div class="sxs-recruiter-slide-inner">
                        <div class="sxs-recruiter-photo">
                            <?php
                            if (!empty($thumbnail)) {
                                echo '<img src="' . esc_url($thumbnail) . '" alt="' . esc_attr($recruiter_name) . '">';
                            } elseif (!empty($placeholder)) {
                                echo '<img src="' . esc_url($placeholder) . '" alt="' . esc_attr($recruiter_name) . '">';
                            } else {
                                echo '<img src="' . esc_url(plugins_url('assets/images/placeholder-user.jpg', dirname(dirname(__FILE__)))) . '" alt="' . esc_attr($recruiter_name) . '">';
                            }
                            ?>
                        </div>
                            <div class="sxs-recruiter-name-container">
                            <h5 class="sxs-recruiter-name"><?php echo esc_html($recruiter_name); ?></h5>

                            <?php if($title): ?>
                                <p class="team-title uppercase"><?php echo $title; ?></p>
                            <?php endif; ?>

                            <div class="team-divider"></div>

                            <?php if($phone): ?>
                                <p class="team-phone"><?php echo $phone; ?></p>
                            <?php endif; ?>

                            <div class="pres-team-social">

                                <?php if($linkedin): ?>
                                    <a href="<?php echo $linkedin; ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                <?php endif; ?>

                                <?php if($email): ?>
                                    <a id="pres-recruiter-email" data-email="<?php echo $email; ?>" href="mailto:<?php echo $email; ?>" target="_blank"><i class="fas fa-envelope"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    
                    <?php if (count($featured_posts) > 1) : ?>
                    <div class="sxs-slide-controls">
                        <button class="sxs-prev-slide" aria-label="Previous recruiter"><i class="fas fa-chevron-left"></i></button>
                        <button class="sxs-next-slide" aria-label="Next recruiter"><i class="fas fa-chevron-right"></i></button>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="sxs-split-text">
        <?php 
        // Get and display the header content
        if (isset($args['post_id'])) {
            $header_content = get_post_meta($args['post_id'], '_sxs_header_content', true);
            $buttons_message = get_post_meta($args['post_id'], '_sxs_buttons_message', true);
            
            // Display the header content or buttons message if available, otherwise show default
            if (!empty($header_content)) {
                echo wp_kses_post($header_content);
            } elseif (!empty($buttons_message)) {
                echo '<h2 class="buttons-heading">' . wp_kses_post($buttons_message) . '</h2>';
            } else {
                echo '<h2>Side by Side <br> Candidate Comparison</h2>';
            }
            
            // Get button settings and URLs
            $enable_position_brief = get_post_meta($args['post_id'], '_sxs_position_brief_enabled', true);
            $enable_scorecard = get_post_meta($args['post_id'], '_sxs_scorecard_enabled', true);
            $position_brief_url = get_post_meta($args['post_id'], '_sxs_position_brief_url', true);
            $scorecard_url = get_post_meta($args['post_id'], '_sxs_scorecard_url', true);
            
            // Only show buttons section if at least one button is enabled and has a URL
            if (($enable_position_brief && !empty($position_brief_url)) || ($enable_scorecard && !empty($scorecard_url))) : ?>
                <div class="position-buttons">
                    <?php if ($enable_position_brief && !empty($position_brief_url)) : ?>
                        <a href="<?php echo esc_url($position_brief_url); ?>" class="position-button primary" target="_blank">
                            Position Brief <i class="fas fa-arrow-right"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($enable_scorecard && !empty($scorecard_url)) : ?>
                        <a href="<?php echo esc_url($scorecard_url); ?>" class="position-button secondary" target="_blank">
                            Crelate Portal <i class="fas fa-arrow-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif;
        }
        ?>
    </div>
</div> 