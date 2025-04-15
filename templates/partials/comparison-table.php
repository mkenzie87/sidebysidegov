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
            <table class="sxs-comparison-table">
                <thead class="sxs-comparison-header">
                    <tr>
                        <th class="sxs-col-header">SIDE BY SIDE</th>
                        <?php foreach ($candidates as $candidate) : ?>
                            <th class="sxs-col"><?php echo esc_html($candidate->post_title); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- Current Company Row -->
                    <tr class="sxs-comparison-row">
                        <td class="sxs-col-header">CURRENT COMPANY</td>
                        <?php foreach ($candidates as $candidate) : ?>
                            <td class="sxs-col">
                                <?php echo esc_html(get_post_meta($candidate->ID, '_sxs_current_company', true)); ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>

                    <!-- Current Title Row -->
                    <tr class="sxs-comparison-row">
                        <td class="sxs-col-header">CURRENT TITLE</td>
                        <?php foreach ($candidates as $candidate) : ?>
                            <td class="sxs-col">
                                <?php echo esc_html(get_post_meta($candidate->ID, '_sxs_current_title', true)); ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>

                    <!-- Years Experience Row -->
                    <tr class="sxs-comparison-row">
                        <td class="sxs-col-header">YEARS EXPERIENCE</td>
                        <?php foreach ($candidates as $candidate) : ?>
                            <td class="sxs-col">
                                <?php 
                                $industry_exp = get_post_meta($candidate->ID, '_sxs_industry_experience', true);
                                echo esc_html($industry_exp ? $industry_exp . ' years' : 'N/A');
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>

                    <!-- Education Row -->
                    <tr class="sxs-comparison-row">
                        <td class="sxs-col-header">EDUCATION</td>
                        <?php foreach ($candidates as $candidate) : ?>
                            <td class="sxs-col">
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

                    <!-- Relevant Experience Row -->
                    <tr class="sxs-comparison-row">
                        <td class="sxs-col-header">RELEVANT EXPERIENCE</td>
                        <?php foreach ($candidates as $candidate) : ?>
                            <td class="sxs-col">
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
                    <tr class="sxs-comparison-row">
                        <td class="sxs-col-header">COMPENSATION</td>
                        <?php foreach ($candidates as $candidate) : ?>
                            <td class="sxs-col">
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