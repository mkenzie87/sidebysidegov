<?php
/**
 * Template for displaying the comparison content
 */
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="sxs-comparison-wrapper">
    <div class="sxs-comparison-container">
        <!-- Header Row -->
        <div class="sxs-row sxs-comparison-header">
            <div class="sxs-col-header">SIDE BY SIDE</div>
            <?php foreach ($candidates as $candidate) : ?>
                <div class="sxs-col">
                    <?php echo esc_html($candidate->post_title); ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Body Section -->
        <div class="sxs-comparison-body">
            <!-- Current Company/Title -->
            <div class="sxs-row">
                <div class="sxs-col-header">CURRENT COMPANY/TITLE</div>
                <?php foreach ($candidates as $candidate) : ?>
                    <div class="sxs-col">
                        <div class="company"><?php echo esc_html(get_post_meta($candidate->ID, '_sxs_company', true)); ?></div>
                        <div class="title"><?php echo esc_html(get_post_meta($candidate->ID, '_sxs_title', true)); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Degrees/Certifications -->
            <div class="sxs-row">
                <div class="sxs-col-header">DEGREES/CERTIFICATIONS</div>
                <?php foreach ($candidates as $candidate) : ?>
                    <div class="sxs-col">
                        <ul class="sxs-degrees">
                            <?php 
                            $degrees = get_post_meta($candidate->ID, '_sxs_degrees', true);
                            if (is_array($degrees) && !empty($degrees)) :
                                foreach ($degrees as $degree) : ?>
                                    <li><?php 
                                        echo esc_html($degree['name'] ?? '');
                                        if (!empty($degree['institution'])) {
                                            echo ' • ' . esc_html($degree['institution']);
                                        }
                                    ?></li>
                                <?php endforeach;
                            endif; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Years of Experience -->
            <div class="sxs-row">
                <div class="sxs-col-header">YEARS OF INDUSTRY EXPERIENCE/ROLE EXPERIENCE</div>
                <?php foreach ($candidates as $candidate) : ?>
                    <div class="sxs-col">
                        <div class="sxs-experience">
                            <?php 
                            $industry_exp = get_post_meta($candidate->ID, '_sxs_industry_experience', true);
                            $role_exp = get_post_meta($candidate->ID, '_sxs_role_experience', true);
                            echo esc_html($industry_exp) . ' years\' industry experience; ' . esc_html($role_exp) . ' years\' specific role experience';
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Relevant Experience -->
            <div class="sxs-row">
                <div class="sxs-col-header">RELEVANT EXPERIENCE SUMMARY</div>
                <?php foreach ($candidates as $candidate) : ?>
                    <div class="sxs-col">
                        <ul class="sxs-list">
                            <?php 
                            $experience = get_post_meta($candidate->ID, '_sxs_experience', true);
                            if (is_array($experience) && !empty($experience)) :
                                foreach ($experience as $item) : ?>
                                    <li><?php echo esc_html($item); ?></li>
                                <?php endforeach;
                            endif; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Compensation -->
            <div class="sxs-row">
                <div class="sxs-col-header">COMPENSATION</div>
                <?php foreach ($candidates as $candidate) : ?>
                    <div class="sxs-col">
                        <div class="sxs-compensation">
                            <?php 
                            $application_comp = get_post_meta($candidate->ID, '_sxs_application_compensation', true);
                            $current_comp = get_post_meta($candidate->ID, '_sxs_current_compensation', true);
                            if ($application_comp) echo 'Application: ' . esc_html($application_comp) . '<br>';
                            if ($current_comp) echo 'Current: ' . esc_html($current_comp);
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Resume -->
            <div class="sxs-row">
                <div class="sxs-col-header">RESUME</div>
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