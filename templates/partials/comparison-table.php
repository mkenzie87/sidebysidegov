<?php
/**
 * Template for the comparison table
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="sxs-comparison-wrapper">
    <div class="sxs-comparison-container">
        <div class="scroll-indicator">
            <span>Scroll to see more</span>
            <i class="fas fa-arrow-right"></i>
        </div>
        
        <div class="sxs-comparison-scroll">
            <!-- Header Row -->
            <div class="sxs-row sxs-header-row">
                <div class="sxs-col sxs-col-header">SIDE BY SIDE</div>
                <?php foreach ($candidates as $candidate) : ?>
                    <div class="sxs-col"><?php echo esc_html($candidate->post_title); ?></div>
                <?php endforeach; ?>
            </div>

            <!-- Current Company/Title Row -->
            <div class="sxs-row">
                <div class="sxs-col sxs-col-header">CURRENT COMPANY/<br>TITLE</div>
                <?php foreach ($candidates as $candidate) : ?>
                    <div class="sxs-col">
                        <div class="sxs-company"><?php echo esc_html(get_post_meta($candidate->ID, '_sxs_current_company', true)); ?></div>
                        <div class="sxs-title"><?php echo esc_html(get_post_meta($candidate->ID, '_sxs_current_title', true)); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Degrees/Certifications Row -->
            <div class="sxs-row">
                <div class="sxs-col sxs-col-header">DEGREES/<br>CERTIFICATIONS</div>
                <?php foreach ($candidates as $candidate) : ?>
                    <div class="sxs-col">
                        <?php 
                        $education = get_post_meta($candidate->ID, '_sxs_education', true);
                        if (is_array($education) && !empty($education)) {
                            echo '<ul class="sxs-list">';
                            foreach ($education as $degree) {
                                echo '<li>' . esc_html($degree) . '</li>';
                            }
                            echo '</ul>';
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Years Experience Row -->
            <div class="sxs-row">
                <div class="sxs-col sxs-col-header">YEARS OF INDUSTRY<br>EXPERIENCE/ROLE<br>EXPERIENCE</div>
                <?php foreach ($candidates as $candidate) : ?>
                    <div class="sxs-col">
                        <?php 
                        $industry_exp = get_post_meta($candidate->ID, '_sxs_industry_experience', true);
                        $role_exp = get_post_meta($candidate->ID, '_sxs_role_experience', true);
                        
                        if ($industry_exp || $role_exp) {
                            if ($industry_exp) echo '<div>' . esc_html($industry_exp) . ' years industry experience</div>';
                            if ($role_exp) echo '<div>' . esc_html($role_exp) . ' years role experience</div>';
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Relevant Experience Row -->
            <div class="sxs-row">
                <div class="sxs-col sxs-col-header">RELEVANT EXPERIENCE<br>SUMMARY</div>
                <?php foreach ($candidates as $candidate) : ?>
                    <div class="sxs-col">
                        <?php 
                        $experience = get_post_meta($candidate->ID, '_sxs_relevant_experience', true);
                        if (is_array($experience) && !empty($experience)) {
                            echo '<ul class="sxs-list">';
                            foreach ($experience as $item) {
                                echo '<li>' . esc_html($item) . '</li>';
                            }
                            echo '</ul>';
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Compensation Row -->
            <div class="sxs-row">
                <div class="sxs-col sxs-col-header">COMPENSATION</div>
                <?php foreach ($candidates as $candidate) : ?>
                    <div class="sxs-col">
                        <?php 
                        $base = get_post_meta($candidate->ID, '_sxs_current_base', true);
                        $bonus = get_post_meta($candidate->ID, '_sxs_current_bonus', true);
                        $total = get_post_meta($candidate->ID, '_sxs_application_compensation', true);
                        
                        if ($base || $bonus || $total) {
                            echo '<ul class="sxs-list">';
                            if ($base) echo '<li>Base: ' . esc_html($base) . '</li>';
                            if ($bonus) echo '<li>Bonus: ' . esc_html($bonus) . '</li>';
                            if ($total) echo '<li>Total: ' . esc_html($total) . '</li>';
                            echo '</ul>';
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Resume Row -->
            <div class="sxs-row">
                <div class="sxs-col sxs-col-header">RESUME</div>
                <?php foreach ($candidates as $candidate) : ?>
                    <div class="sxs-col">
                        <?php 
                        $resume_url = get_post_meta($candidate->ID, '_sxs_resume_url', true);
                        if (!empty($resume_url)) {
                            echo '<a href="' . esc_url($resume_url) . '" class="sxs-button sxs-download-button" target="_blank" download>';
                            echo '<i class="fas fa-download"></i> Download Resume';
                            echo '</a>';
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div> 