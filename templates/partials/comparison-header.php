<?php
/**
 * Comparison Header Section
 */

if (!defined('ABSPATH')) {
    exit;
}

if ($company_data) : ?>
<div class="sxs-company-header" style="background-color: <?php echo esc_attr($company_data['header_color']); ?>">
    <?php if (!empty($company_data['logo'])) : ?>
        <div class="sxs-company-logo">
            <img src="<?php echo esc_url($company_data['logo']); ?>" alt="<?php echo esc_attr($company_data['name']); ?> Logo">
        </div>
    <?php endif; ?>
    <div class="sxs-company-name"><?php echo esc_html($company_data['name']); ?></div>
</div>
<?php endif; ?> 