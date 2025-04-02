<?php

if (!defined('ABSPATH')) {
    exit;
}

class SXS_Company_Metaboxes {
    
    public function init() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post_sxs_company', array($this, 'save_meta_boxes'), 10, 2);
    }

    public function add_meta_boxes() {
        add_meta_box(
            'sxs_company_details',
            __('Company Details', 'sxs-candidate-comparison'),
            array($this, 'render_details_meta_box'),
            'sxs_company',
            'normal',
            'high'
        );

        add_meta_box(
            'sxs_company_branding',
            __('Branding', 'sxs-candidate-comparison'),
            array($this, 'render_branding_meta_box'),
            'sxs_company',
            'normal',
            'high'
        );
        
        add_meta_box(
            'sxs_company_recruiters',
            __('Recruiters', 'sxs-candidate-comparison'),
            array($this, 'render_recruiters_meta_box'),
            'sxs_company',
            'normal',
            'high'
        );
    }

    public function render_details_meta_box($post) {
        wp_nonce_field('sxs_company_details_nonce', 'sxs_company_details_nonce');
        
        $location = get_post_meta($post->ID, '_sxs_company_location', true);
        $website = get_post_meta($post->ID, '_sxs_company_website', true);
        $industry = get_post_meta($post->ID, '_sxs_company_industry', true);
        $description = get_post_meta($post->ID, '_sxs_company_description', true);
        
        ?>
        <div class="sxs-meta-row">
            <h3 class="sxs-section-title"><?php _e('Basic Information', 'sxs-candidate-comparison'); ?></h3>
            <p>
                <label for="sxs_company_location">
                    <?php _e('Location', 'sxs-candidate-comparison'); ?>
                    <span class="sxs-required">*</span>
                    <span class="sxs-tooltip">
                        <span class="sxs-tooltip-icon">?</span>
                        <span class="sxs-tooltip-text"><?php _e('Enter the primary location of the company (e.g., City, State or Country).', 'sxs-candidate-comparison'); ?></span>
                    </span>
                </label>
                <input type="text" id="sxs_company_location" name="sxs_company_location" 
                       value="<?php echo esc_attr($location); ?>" 
                       class="widefat" 
                       placeholder="<?php _e('e.g., Phoenix, AZ', 'sxs-candidate-comparison'); ?>" required>
            </p>
            <p>
                <label for="sxs_company_website">
                    <?php _e('Website', 'sxs-candidate-comparison'); ?>
                    <span class="sxs-tooltip">
                        <span class="sxs-tooltip-icon">?</span>
                        <span class="sxs-tooltip-text"><?php _e('Enter the company website URL.', 'sxs-candidate-comparison'); ?></span>
                    </span>
                </label>
                <input type="url" id="sxs_company_website" name="sxs_company_website" 
                       value="<?php echo esc_attr($website); ?>" 
                       class="widefat" 
                       placeholder="<?php _e('e.g., https://company.com', 'sxs-candidate-comparison'); ?>">
            </p>
            <p>
                <label for="sxs_company_industry">
                    <?php _e('Industry', 'sxs-candidate-comparison'); ?>
                    <span class="sxs-tooltip">
                        <span class="sxs-tooltip-icon">?</span>
                        <span class="sxs-tooltip-text"><?php _e('Enter the industry sector this company operates in.', 'sxs-candidate-comparison'); ?></span>
                    </span>
                </label>
                <input type="text" id="sxs_company_industry" name="sxs_company_industry" 
                       value="<?php echo esc_attr($industry); ?>" 
                       class="widefat" 
                       placeholder="<?php _e('e.g., Technology, Healthcare, Finance', 'sxs-candidate-comparison'); ?>">
            </p>
        </div>

        <div class="sxs-meta-row">
            <h3 class="sxs-section-title"><?php _e('Company Description', 'sxs-candidate-comparison'); ?></h3>
            <p>
                <label for="sxs_company_description">
                    <?php _e('Brief Description', 'sxs-candidate-comparison'); ?>
                    <span class="sxs-tooltip">
                        <span class="sxs-tooltip-icon">?</span>
                        <span class="sxs-tooltip-text"><?php _e('Provide a brief description of the company.', 'sxs-candidate-comparison'); ?></span>
                    </span>
                </label>
                <textarea id="sxs_company_description" name="sxs_company_description" 
                          class="widefat" rows="4" 
                          placeholder="<?php _e('Enter a brief description of the company...', 'sxs-candidate-comparison'); ?>"><?php echo esc_textarea($description); ?></textarea>
            </p>
        </div>
        <?php
    }

    public function render_branding_meta_box($post) {
        wp_nonce_field('sxs_company_branding_nonce', 'sxs_company_branding_nonce');
        
        $header_color = get_post_meta($post->ID, '_sxs_company_header_color', true);
        if (empty($header_color)) {
            $header_color = '#1C2856'; // Default to navy blue
        }
        
        $text_color = get_post_meta($post->ID, '_sxs_company_text_color', true);
        if (empty($text_color)) {
            $text_color = '#FFFFFF'; // Default to white
        }
        
        $logo_id = get_post_meta($post->ID, '_sxs_company_logo_id', true);
        $cover_id = get_post_meta($post->ID, '_sxs_company_cover_id', true);
        
        ?>
        <div class="sxs-meta-row">
            <h3 class="sxs-section-title"><?php _e('Company Logo', 'sxs-candidate-comparison'); ?></h3>
            <p>
                <label>
                    <?php _e('Logo', 'sxs-candidate-comparison'); ?>
                    <span class="sxs-required">*</span>
                    <span class="sxs-tooltip">
                        <span class="sxs-tooltip-icon">?</span>
                        <span class="sxs-tooltip-text"><?php _e('Upload a company logo (recommended size: 200x200px).', 'sxs-candidate-comparison'); ?></span>
                    </span>
                </label>
                <div class="sxs-media-field" id="logo-field">
                    <div class="sxs-media-preview">
                        <?php if (!empty($logo_id)) : 
                            $image = wp_get_attachment_image_src($logo_id, 'thumbnail');
                            if ($image) : ?>
                                <img src="<?php echo esc_url($image[0]); ?>" alt="<?php _e('Company Logo', 'sxs-candidate-comparison'); ?>">
                            <?php endif; ?>
                        <?php else : ?>
                            <div class="sxs-no-image">
                                <span class="dashicons dashicons-format-image"></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="sxs_company_logo_id" id="sxs_company_logo_id" value="<?php echo esc_attr($logo_id); ?>">
                    <div class="sxs-media-buttons">
                        <button type="button" class="button sxs-upload-media" data-field="logo"><?php _e('Upload Logo', 'sxs-candidate-comparison'); ?></button>
                        <button type="button" class="button sxs-remove-media" data-field="logo" <?php echo empty($logo_id) ? 'style="display:none"' : ''; ?>><?php _e('Remove Logo', 'sxs-candidate-comparison'); ?></button>
                    </div>
                    <p class="description"><?php _e('Upload a square logo for the company. This will be displayed in the comparison header.', 'sxs-candidate-comparison'); ?></p>
                </div>
            </p>
        </div>

        <div class="sxs-meta-row">
            <h3 class="sxs-section-title"><?php _e('Company Cover Image', 'sxs-candidate-comparison'); ?></h3>
            <p>
                <label>
                    <?php _e('Cover Image', 'sxs-candidate-comparison'); ?>
                    <span class="sxs-tooltip">
                        <span class="sxs-tooltip-icon">?</span>
                        <span class="sxs-tooltip-text"><?php _e('Upload a cover/banner image for the company (recommended size: 1200x300px).', 'sxs-candidate-comparison'); ?></span>
                    </span>
                </label>
                <div class="sxs-media-field" id="cover-field">
                    <div class="sxs-media-preview sxs-cover-preview">
                        <?php if (!empty($cover_id)) : 
                            $image = wp_get_attachment_image_src($cover_id, 'medium');
                            if ($image) : ?>
                                <img src="<?php echo esc_url($image[0]); ?>" alt="<?php _e('Company Cover Image', 'sxs-candidate-comparison'); ?>">
                            <?php endif; ?>
                        <?php else : ?>
                            <div class="sxs-no-image">
                                <span class="dashicons dashicons-format-image"></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="sxs_company_cover_id" id="sxs_company_cover_id" value="<?php echo esc_attr($cover_id); ?>">
                    <div class="sxs-media-buttons">
                        <button type="button" class="button sxs-upload-media" data-field="cover"><?php _e('Upload Cover Image', 'sxs-candidate-comparison'); ?></button>
                        <button type="button" class="button sxs-remove-media" data-field="cover" <?php echo empty($cover_id) ? 'style="display:none"' : ''; ?>><?php _e('Remove Cover Image', 'sxs-candidate-comparison'); ?></button>
                    </div>
                    <p class="description"><?php _e('Upload a cover/banner image for the company. This will be displayed at the top of company profiles.', 'sxs-candidate-comparison'); ?></p>
                </div>
            </p>
        </div>

        <div class="sxs-meta-row">
            <h3 class="sxs-section-title"><?php _e('Company Colors', 'sxs-candidate-comparison'); ?></h3>
            <p>
                <label for="sxs_company_header_color">
                    <?php _e('Header Background Color', 'sxs-candidate-comparison'); ?>
                    <span class="sxs-tooltip">
                        <span class="sxs-tooltip-icon">?</span>
                        <span class="sxs-tooltip-text"><?php _e('Choose a background color for company headers in comparisons.', 'sxs-candidate-comparison'); ?></span>
                    </span>
                </label>
                <input type="color" id="sxs_company_header_color" name="sxs_company_header_color" 
                       value="<?php echo esc_attr($header_color); ?>" class="sxs-color-picker">
                <input type="text" id="sxs_company_header_color_text" 
                       value="<?php echo esc_attr($header_color); ?>" class="sxs-color-text">
            </p>
            <p>
                <label for="sxs_company_text_color">
                    <?php _e('Header Text Color', 'sxs-candidate-comparison'); ?>
                    <span class="sxs-tooltip">
                        <span class="sxs-tooltip-icon">?</span>
                        <span class="sxs-tooltip-text"><?php _e('Choose a text color for company headers in comparisons.', 'sxs-candidate-comparison'); ?></span>
                    </span>
                </label>
                <input type="color" id="sxs_company_text_color" name="sxs_company_text_color" 
                       value="<?php echo esc_attr($text_color); ?>" class="sxs-color-picker">
                <input type="text" id="sxs_company_text_color_text" 
                       value="<?php echo esc_attr($text_color); ?>" class="sxs-color-text">
            </p>
            <div class="sxs-color-preview">
                <div id="sxs-header-preview" style="background-color: <?php echo esc_attr($header_color); ?>; color: <?php echo esc_attr($text_color); ?>;">
                    <div class="company-logo-preview">
                        <?php if (!empty($logo_id)) : 
                            $logo_img = wp_get_attachment_image_src($logo_id, array(40, 40));
                            if ($logo_img) : ?>
                                <img src="<?php echo esc_url($logo_img[0]); ?>" alt="<?php _e('Logo', 'sxs-candidate-comparison'); ?>">
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="placeholder-logo">
                                <span class="dashicons dashicons-building"></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="company-name-preview">
                        <?php echo esc_html(get_the_title($post->ID)); ?>
                    </div>
                </div>
                <p class="description"><?php _e('Preview of how the company header will appear in comparisons.', 'sxs-candidate-comparison'); ?></p>
            </div>
        </div>
        
        <style>
            .sxs-color-picker {
                vertical-align: middle;
                width: 50px;
                height: 30px;
                padding: 0;
                border: none;
                cursor: pointer;
            }
            
            .sxs-color-text {
                width: 80px;
                margin-left: 10px;
                vertical-align: middle;
            }
            
            .sxs-color-preview {
                margin-top: 20px;
                border: 1px solid #ddd;
                border-radius: 4px;
                overflow: hidden;
            }
            
            #sxs-header-preview {
                padding: 15px;
                display: flex;
                align-items: center;
                font-weight: bold;
                font-size: 16px;
            }
            
            .company-logo-preview {
                margin-right: 10px;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .company-logo-preview img {
                display: block;
                width: 40px;
                height: 40px;
                object-fit: contain;
                background: white;
                border-radius: 4px;
            }
            
            .placeholder-logo {
                width: 40px;
                height: 40px;
                background: white;
                border-radius: 4px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .placeholder-logo .dashicons {
                font-size: 24px;
                color: #666;
            }
            
            .sxs-media-field {
                margin-top: 10px;
            }
            
            .sxs-media-preview {
                border: 1px solid #ddd;
                padding: 5px;
                background: #f9f9f9;
                width: 200px;
                height: 200px;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                margin-bottom: 10px;
            }
            
            .sxs-media-preview img {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
            }
            
            .sxs-cover-preview {
                width: 100%;
                height: 150px;
            }
            
            .sxs-no-image {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                height: 100%;
                background: #f1f1f1;
            }
            
            .sxs-no-image .dashicons {
                font-size: 40px;
                color: #ccc;
            }
            
            .sxs-media-buttons {
                margin-bottom: 10px;
            }
            
            .sxs-media-buttons .button {
                margin-right: 5px;
            }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            // Media uploader
            $('.sxs-upload-media').on('click', function(e) {
                e.preventDefault();
                
                var button = $(this);
                var fieldType = button.data('field');
                var fieldId = '#sxs_company_' + fieldType + '_id';
                var previewContainer = button.closest('.sxs-media-field').find('.sxs-media-preview');
                var removeButton = button.closest('.sxs-media-buttons').find('.sxs-remove-media');
                
                var frame = wp.media({
                    title: fieldType === 'logo' ? '<?php _e('Select or Upload a Company Logo', 'sxs-candidate-comparison'); ?>' : '<?php _e('Select or Upload a Cover Image', 'sxs-candidate-comparison'); ?>',
                    button: {
                        text: '<?php _e('Use this image', 'sxs-candidate-comparison'); ?>'
                    },
                    multiple: false
                });
                
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $(fieldId).val(attachment.id);
                    
                    // Update the preview
                    previewContainer.html('<img src="' + attachment.url + '" alt="">');
                    
                    // Show the remove button
                    removeButton.show();
                    
                    // Update the header preview if it's a logo
                    if (fieldType === 'logo') {
                        $('.company-logo-preview').html('<img src="' + attachment.url + '" alt="">');
                    }
                    
                    updatePreview();
                });
                
                frame.open();
            });
            
            // Remove media
            $('.sxs-remove-media').on('click', function(e) {
                e.preventDefault();
                
                var button = $(this);
                var fieldType = button.data('field');
                var fieldId = '#sxs_company_' + fieldType + '_id';
                var previewContainer = button.closest('.sxs-media-field').find('.sxs-media-preview');
                
                // Clear the field value
                $(fieldId).val('');
                
                // Update the preview
                previewContainer.html('<div class="sxs-no-image"><span class="dashicons dashicons-format-image"></span></div>');
                
                // Hide the remove button
                button.hide();
                
                // Update the header preview if it's a logo
                if (fieldType === 'logo') {
                    $('.company-logo-preview').html('<div class="placeholder-logo"><span class="dashicons dashicons-building"></span></div>');
                }
                
                updatePreview();
            });
            
            // Update color text field when color picker changes
            $('#sxs_company_header_color').on('input', function() {
                $('#sxs_company_header_color_text').val($(this).val());
                updatePreview();
            });
            
            $('#sxs_company_text_color').on('input', function() {
                $('#sxs_company_text_color_text').val($(this).val());
                updatePreview();
            });
            
            // Update color picker when text field changes
            $('#sxs_company_header_color_text').on('input', function() {
                var color = $(this).val();
                if (/^#[0-9A-F]{6}$/i.test(color)) {
                    $('#sxs_company_header_color').val(color);
                    updatePreview();
                }
            });
            
            $('#sxs_company_text_color_text').on('input', function() {
                var color = $(this).val();
                if (/^#[0-9A-F]{6}$/i.test(color)) {
                    $('#sxs_company_text_color').val(color);
                    updatePreview();
                }
            });
            
            function updatePreview() {
                var headerColor = $('#sxs_company_header_color').val();
                var textColor = $('#sxs_company_text_color').val();
                
                $('#sxs-header-preview').css({
                    'background-color': headerColor,
                    'color': textColor
                });
            }
        });
        </script>
        <?php
    }

    public function render_recruiters_meta_box($post) {
        wp_nonce_field('sxs_company_recruiters_nonce', 'sxs_company_recruiters_nonce');
        
        // Get selected recruiters
        $selected_recruiters = get_post_meta($post->ID, '_sxs_company_recruiters', true);
        if (!is_array($selected_recruiters)) {
            $selected_recruiters = array();
        }
        
        // Query for users who might be recruiters
        $args = array(
            'posts_per_page' => -1,
            'post_type' => 'post',
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC',
        );
        
        // Check if a specific post type exists for team members or recruiters
        if (post_type_exists('team') || post_type_exists('recruiter')) {
            $args['post_type'] = post_type_exists('recruiter') ? 'recruiter' : 'team';
        }
        
        // Query posts
        $recruiters = get_posts($args);
        ?>
        <div class="sxs-meta-row">
            <h3 class="sxs-section-title"><?php _e('Assign Recruiters', 'sxs-candidate-comparison'); ?></h3>
            <p class="description">
                <?php _e('Select recruiters to display on this company\'s comparison pages.', 'sxs-candidate-comparison'); ?>
                <span class="sxs-tooltip">
                    <span class="sxs-tooltip-icon">?</span>
                    <span class="sxs-tooltip-text"><?php _e('The selected recruiters will be displayed in a slider above the comparison table.', 'sxs-candidate-comparison'); ?></span>
                </span>
            </p>
            
            <?php if (!empty($recruiters)) : ?>
                <div class="sxs-recruiter-selection">
                    <select id="sxs_company_recruiters" name="sxs_company_recruiters[]" class="widefat" multiple="multiple" style="height: 200px; width: 100%;">
                        <?php foreach ($recruiters as $recruiter) : ?>
                            <option value="<?php echo esc_attr($recruiter->ID); ?>" <?php echo in_array($recruiter->ID, $selected_recruiters) ? 'selected="selected"' : ''; ?>>
                                <?php echo esc_html($recruiter->post_title); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="description"><?php _e('Hold down the Ctrl (Windows) / Command (Mac) key to select multiple recruiters.', 'sxs-candidate-comparison'); ?></p>
                </div>
            <?php else : ?>
                <div class="sxs-notice">
                    <?php _e('No recruiters or team members found. Please create team members as regular posts.', 'sxs-candidate-comparison'); ?>
                </div>
            <?php endif; ?>
            
            <div class="sxs-meta-notice" style="margin-top: 15px;">
                <p><strong><?php _e('Note:', 'sxs-candidate-comparison'); ?></strong> <?php _e('To display recruiter information correctly, make sure each recruiter/team member post has the following:', 'sxs-candidate-comparison'); ?></p>
                <ul style="list-style-type: disc; margin-left: 20px;">
                    <li><?php _e('Featured image (for profile photo)', 'sxs-candidate-comparison'); ?></li>
                    <li><?php _e('Title (for name)', 'sxs-candidate-comparison'); ?></li>
                    <li><?php _e('Custom fields for job title, phone, LinkedIn URL, and email', 'sxs-candidate-comparison'); ?></li>
                </ul>
                <p><?php _e('If using ACF, configure the field names in your theme options.', 'sxs-candidate-comparison'); ?></p>
            </div>
        </div>
        <?php
    }

    public function save_meta_boxes($post_id, $post) {
        // Verify nonces
        if (!isset($_POST['sxs_company_details_nonce']) ||
            !isset($_POST['sxs_company_branding_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['sxs_company_details_nonce'], 'sxs_company_details_nonce') ||
            !wp_verify_nonce($_POST['sxs_company_branding_nonce'], 'sxs_company_branding_nonce')) {
            return;
        }
        
        // Verify recruiter nonce
        $save_recruiters = isset($_POST['sxs_company_recruiters_nonce']) && 
                         wp_verify_nonce($_POST['sxs_company_recruiters_nonce'], 'sxs_company_recruiters_nonce');

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save company details
        if (isset($_POST['sxs_company_location'])) {
            update_post_meta($post_id, '_sxs_company_location', sanitize_text_field($_POST['sxs_company_location']));
        }
        
        if (isset($_POST['sxs_company_website'])) {
            update_post_meta($post_id, '_sxs_company_website', sanitize_url($_POST['sxs_company_website']));
        }
        
        if (isset($_POST['sxs_company_industry'])) {
            update_post_meta($post_id, '_sxs_company_industry', sanitize_text_field($_POST['sxs_company_industry']));
        }
        
        if (isset($_POST['sxs_company_description'])) {
            update_post_meta($post_id, '_sxs_company_description', sanitize_textarea_field($_POST['sxs_company_description']));
        }
        
        // Save branding details
        if (isset($_POST['sxs_company_header_color'])) {
            update_post_meta($post_id, '_sxs_company_header_color', sanitize_hex_color($_POST['sxs_company_header_color']));
        }
        
        if (isset($_POST['sxs_company_text_color'])) {
            update_post_meta($post_id, '_sxs_company_text_color', sanitize_hex_color($_POST['sxs_company_text_color']));
        }
        
        if (isset($_POST['sxs_company_logo_id'])) {
            update_post_meta($post_id, '_sxs_company_logo_id', sanitize_text_field($_POST['sxs_company_logo_id']));
        }
        
        if (isset($_POST['sxs_company_cover_id'])) {
            update_post_meta($post_id, '_sxs_company_cover_id', sanitize_text_field($_POST['sxs_company_cover_id']));
        }
        
        // Save recruiters
        if ($save_recruiters && isset($_POST['sxs_company_recruiters'])) {
            $recruiters = array_map('intval', $_POST['sxs_company_recruiters']);
            update_post_meta($post_id, '_sxs_company_recruiters', $recruiters);
        } elseif ($save_recruiters) {
            // If no recruiters are selected, save an empty array
            update_post_meta($post_id, '_sxs_company_recruiters', array());
        }
    }
} 