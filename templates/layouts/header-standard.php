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
$company_cover_url = $args['company_cover_url'] ?? '';
$job_title = $args['job_title'] ?? '';
$job_location = $args['job_location'] ?? '';
$job_description = $args['job_description'] ?? '';
$job_type = $args['job_type'] ?? '';
$job_experience = $args['job_experience'] ?? '';
$job_education = $args['job_education'] ?? '';

// If no company cover URL, use a default
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
        jQuery('.recruiter-slides').slick({
            dots: true,
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
    padding: 60px 20px;
}

.sxs-hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
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
    align-items: flex-start;
    justify-content: space-between;
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
    color: white;
    font-size: 16px;
    line-height: 1.6;
}

.sxs-split-text h1,
.sxs-split-text h2,
.sxs-split-text h3,
.sxs-split-text h4,
.sxs-split-text h5,
.sxs-split-text h6 {
    color: white;
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

.sxs-split-text a:hover {
    color: white;
    text-decoration: underline;
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

/* Recruiter Card Styles */
.recruiter-slides {
    width: 100%;
    max-width: 100%;
    margin: 0;
    position: relative;
    z-index: 3;
}

section.recruiter-info {
    background: #1C2856;
    padding: 30px 20px;
    border-radius: 15px;
    color: white;
    text-align: center;
}

.recruiter-info h5.contact-title {
    color: white;
    font-size: 18px;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 0;
    margin-bottom: 15px;
}

.recruiter-info h5.team-name {
    color: white;
    margin-top: 15px;
    font-size: 24px;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 5px;
}

.recruiter-info p.team-title {
    color: white;
    text-transform: uppercase;
    font-size: 14px;
    margin-bottom: 15px;
    margin-top: 0;
}

.recruiter-info .pres-team-social a {
    background: #F26724;
    color: white;
    border: 1px solid #F26724;
    height: 32px;
    width: 32px;
    display: inline-block;
    border-radius: 50%;
    font-size: 16px;
    line-height: 32px;
    margin: 0 5px;
}

.recruiter-info p.team-phone {
    margin-top: 7px;
    margin-bottom: 10px;
    font-size: 18px;
}

.recruiter-info .pres-team-social a:hover {
    background: transparent;
    color: white;
    border-color: white;
}

.recruiter-info .team-picture img {
    border-radius: 50%;
    height: 150px;
    width: 150px;
    object-fit: cover;
    border: 5px solid white;
    margin: 0 auto;
    object-position: top;
}

li.slick-active:only-child {
    display: none;
}

.team-divider {
    background: white;
    height: 1px;
    width: 22px;
    margin: 0 auto 15px;
}

.pres-team-social {
    position: relative;
    display: block;
    -webkit-transition: all .5s ease-in-out;
    -moz-transition: all .5s ease-in-out;
    -o-transition: all .5s ease-in-out;
    transition: all .5s ease-in-out;
    margin-bottom: 0;
}

@media screen and (max-width: 768px) {
    .sxs-hero-header {
        padding: 40px 20px;
    }
    
    .sxs-hero-logo {
        width: 150px;
        height: 150px;
    }
    
    .sxs-hero-title {
        font-size: 36px;
    }

    .sxs-split-content {
        flex-direction: column;
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
        <h1 class="sxs-hero-title"><?php echo esc_html($company ? $company->post_title : $job_title); ?></h1>
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
            <section class="recruiter-slides">
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
                    if (empty($title)) $title = get_post_meta($featured_post->ID, '_sxs_recruiter_title', true);
                    if (empty($phone)) $phone = get_post_meta($featured_post->ID, '_sxs_recruiter_phone', true);
                    if (empty($linkedin)) $linkedin = get_post_meta($featured_post->ID, '_sxs_recruiter_linkedin', true);
                    if (empty($email)) $email = get_post_meta($featured_post->ID, '_sxs_recruiter_email', true);
                    
                    $thumbnail = get_the_post_thumbnail_url($featured_post->ID);
                    if (empty($thumbnail)) $thumbnail = get_post_meta($featured_post->ID, '_sxs_recruiter_image', true);
                    
                    $recruiter_name = get_the_title($featured_post->ID);
                ?>
                <section class="recruiter-info">
                    <h5 class="contact-title">Recruiter Contact</h5>
                    
                    <div class="team-picture">
                        <?php
                        if (!empty($thumbnail)) {
                            echo '<img src="' . esc_url($thumbnail) . '">';
                        } elseif (!empty($placeholder)) {
                            echo '<img src="' . esc_url($placeholder) . '">';
                        } else {
                            // Default placeholder
                            echo '<img src="' . esc_url(plugins_url('assets/images/placeholder-user.jpg', dirname(dirname(__FILE__)))) . '">';
                        }
                        ?>
                    </div>
                    
                    <div class="team-info">
                        <h5 class="team-name uppercase"><?php echo esc_html($recruiter_name); ?></h5>
                        
                        <?php if(!empty($title)): ?>
                            <p class="team-title uppercase"><?php echo esc_html($title); ?></p>
                        <?php endif; ?>
                        
                        <div class="team-divider"></div>
                        
                        <?php if(!empty($phone)): ?>
                            <p class="team-phone"><?php echo esc_html($phone); ?></p>
                        <?php endif; ?>
                        
                        <div class="pres-team-social">
                            <?php if(!empty($linkedin)): ?>
                                <a href="<?php echo esc_url($linkedin); ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                            <?php endif; ?>
                            
                            <?php if(!empty($email)): ?>
                                <a id="pres-recruiter-email" data-email="<?php echo esc_attr($email); ?>" href="mailto:<?php echo esc_attr($email); ?>" target="_blank"><i class="fas fa-envelope"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
                <?php endforeach; ?>
            </section>
            <?php endif; ?>
        </div>

        <div class="sxs-split-text">
            <?php 
            // Get and display the header content
            if (isset($args['post_id'])) {
                $header_content = get_post_meta($args['post_id'], '_sxs_header_content', true);
                echo wp_kses_post($header_content);
            }
            ?>
        </div>
    </div>
</div> 