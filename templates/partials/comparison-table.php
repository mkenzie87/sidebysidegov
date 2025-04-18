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
        
        <div class="sxs-table-wrapper">
            <table class="sxs-comparison-table">
                <thead>
                    <tr>
                        <th class="sxs-header-cell sxs-sticky-col">SIDE BY SIDE</th>
                        <?php foreach ($candidates as $candidate) : ?>
                            <th class="sxs-header-cell"><?php echo esc_html($candidate->post_title); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- Current Company Row -->
                    <tr>
                        <td class="sxs-label-cell sxs-sticky-col">CURRENT COMPANY/ TITLE</td>
                        <?php foreach ($candidates as $candidate) : ?>
                            <td class="sxs-data-cell">
                                <div class="company"><?php echo esc_html(get_post_meta($candidate->ID, '_sxs_current_company', true)); ?></div>
                                <div class="title"><?php echo esc_html(get_post_meta($candidate->ID, '_sxs_current_title', true)); ?></div>
                            </td>
                        <?php endforeach; ?>
                    </tr>

                    <!-- Degrees/Certifications Row -->
                    <tr>
                        <td class="sxs-label-cell sxs-sticky-col">DEGREES/ CERTIFICATIONS</td>
                        <?php foreach ($candidates as $candidate) : ?>
                            <td class="sxs-data-cell">
                                <?php 
                                $education = get_post_meta($candidate->ID, '_sxs_education', true);
                                if (is_array($education)) {
                                    echo '<ul class="education-list">';
                                    foreach ($education as $degree) {
                                        echo '<li>' . esc_html($degree) . '</li>';
                                    }
                                    echo '</ul>';
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>

                    <!-- Years Experience Row -->
                    <tr>
                        <td class="sxs-label-cell sxs-sticky-col">YEARS OF INDUSTRY EXPERIENCE/ROLE EXPERIENCE</td>
                        <?php foreach ($candidates as $candidate) : ?>
                            <td class="sxs-data-cell">
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
                            </td>
                        <?php endforeach; ?>
                    </tr>

                    <!-- Relevant Experience Row -->
                    <tr>
                        <td class="sxs-label-cell sxs-sticky-col">RELEVANT EXPERIENCE SUMMARY</td>
                        <?php foreach ($candidates as $candidate) : ?>
                            <td class="sxs-data-cell">
                                <?php 
                                $experience = get_post_meta($candidate->ID, '_sxs_relevant_experience', true);
                                if (is_array($experience)) {
                                    echo '<ul class="experience-list">';
                                    foreach ($experience as $item) {
                                        echo '<li>' . esc_html($item) . '</li>';
                                    }
                                    echo '</ul>';
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>

                    <!-- Compensation Row -->
                    <tr>
                        <td class="sxs-label-cell sxs-sticky-col">COMPENSATION</td>
                        <?php foreach ($candidates as $candidate) : ?>
                            <td class="sxs-data-cell">
                                <?php 
                                $base = get_post_meta($candidate->ID, '_sxs_current_base', true);
                                $bonus = get_post_meta($candidate->ID, '_sxs_current_bonus', true);
                                $total = get_post_meta($candidate->ID, '_sxs_application_compensation', true);
                                
                                if ($base || $bonus || $total) {
                                    echo '<ul class="compensation-list">';
                                    if ($base) echo '<li>Base: ' . esc_html($base) . '</li>';
                                    if ($bonus) echo '<li>Bonus: ' . esc_html($bonus) . '</li>';
                                    if ($total) echo '<li>Total: ' . esc_html($total) . '</li>';
                                    echo '</ul>';
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div> 