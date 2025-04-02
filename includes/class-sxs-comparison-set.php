<?php

if (!defined('ABSPATH')) {
    exit;
}

class SXS_Comparison_Set {
    
    public function init() {
        add_action('init', array($this, 'register_post_type'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
        add_filter('post_row_actions', array($this, 'modify_row_actions'), 10, 2);
        add_filter('template_include', array($this, 'load_comparison_template'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
        add_filter('manage_sxs_comparison_posts_columns', array($this, 'set_custom_columns'));
        add_action('manage_sxs_comparison_posts_custom_column', array($this, 'custom_column'), 10, 2);
    }

    public function register_post_type() {
        $labels = array(
            'name'               => _x('Side by Side Comparisons', 'post type general name', 'sxs-candidate-comparison'),
            'singular_name'      => _x('Side by Side Comparison', 'post type singular name', 'sxs-candidate-comparison'),
            'menu_name'          => _x('Side by Side', 'admin menu', 'sxs-candidate-comparison'),
            'name_admin_bar'     => _x('Side by Side', 'add new on admin bar', 'sxs-candidate-comparison'),
            'add_new'            => _x('Add New', 'comparison', 'sxs-candidate-comparison'),
            'add_new_item'       => __('Add New Comparison', 'sxs-candidate-comparison'),
            'new_item'           => __('New Comparison', 'sxs-candidate-comparison'),
            'edit_item'          => __('Edit Comparison', 'sxs-candidate-comparison'),
            'view_item'          => __('View Comparison', 'sxs-candidate-comparison'),
            'all_items'          => __('All Comparisons', 'sxs-candidate-comparison'),
            'search_items'       => __('Search Comparisons', 'sxs-candidate-comparison'),
            'parent_item_colon'  => __('Parent Comparisons:', 'sxs-candidate-comparison'),
            'not_found'          => __('No comparisons found.', 'sxs-candidate-comparison'),
            'not_found_in_trash' => __('No comparisons found in Trash.', 'sxs-candidate-comparison')
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __('Side by Side Candidate Comparisons', 'sxs-candidate-comparison'),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'edit.php?post_type=sxs_candidate',
            'query_var'          => true,
            'rewrite'            => array('slug' => 'side-by-side'),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 5,
            'menu_icon'          => 'dashicons-columns',
            'supports'           => array(
                'title',
                'author',
            ),
            'show_in_rest'       => true,
        );

        register_post_type('sxs_comparison', $args);
    }

    public function add_meta_boxes() {
        add_meta_box(
            'sxs_comparison_candidates',
            __('Select Candidates', 'sxs-candidate-comparison'),
            array($this, 'render_candidates_meta_box'),
            'sxs_comparison',
            'normal',
            'high'
        );

        add_meta_box(
            'sxs_comparison_shortcode',
            __('Shortcode', 'sxs-candidate-comparison'),
            array($this, 'render_shortcode_meta_box'),
            'sxs_comparison',
            'side',
            'default'
        );
    }

    public function render_candidates_meta_box($post) {
        wp_nonce_field('sxs_comparison_candidates_nonce', 'sxs_comparison_candidates_nonce');
        
        $selected_candidates = get_post_meta($post->ID, '_sxs_selected_candidates', true);
        if (!is_array($selected_candidates)) {
            $selected_candidates = array();
        }

        // Get all candidates
        $candidates = get_posts(array(
            'post_type' => 'sxs_candidate',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ));

        ?>
        <div class="sxs-meta-row">
            <p><?php _e('Select candidates to include in this comparison (2-6 candidates recommended):', 'sxs-candidate-comparison'); ?></p>
            
            <div class="sxs-candidate-selector">
                <div class="sxs-available-candidates">
                    <h4><?php _e('Available Candidates', 'sxs-candidate-comparison'); ?></h4>
                    <select multiple="multiple" size="10" class="sxs-candidates-list" id="sxs-available-candidates">
                        <?php foreach ($candidates as $candidate) : ?>
                            <?php if (!in_array($candidate->ID, $selected_candidates)) : ?>
                                <option value="<?php echo esc_attr($candidate->ID); ?>">
                                    <?php echo esc_html($candidate->post_title); ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="sxs-candidate-controls">
                    <button type="button" class="button" id="sxs-add-candidate">&rarr;</button>
                    <button type="button" class="button" id="sxs-remove-candidate">&larr;</button>
                </div>

                <div class="sxs-selected-candidates">
                    <h4><?php _e('Selected Candidates', 'sxs-candidate-comparison'); ?></h4>
                    <select multiple="multiple" size="10" class="sxs-candidates-list" id="sxs-selected-candidates" name="sxs_selected_candidates[]">
                        <?php foreach ($selected_candidates as $candidate_id) : ?>
                            <?php $candidate = get_post($candidate_id); ?>
                            <?php if ($candidate) : ?>
                                <option value="<?php echo esc_attr($candidate->ID); ?>" selected>
                                    <?php echo esc_html($candidate->post_title); ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <p class="description">
                <?php _e('Tip: Hold Ctrl/Cmd to select multiple candidates. Drag to reorder.', 'sxs-candidate-comparison'); ?>
            </p>
        </div>

        <style>
            .sxs-candidate-selector {
                display: flex;
                gap: 20px;
                align-items: flex-start;
                margin: 20px 0;
            }
            .sxs-available-candidates,
            .sxs-selected-candidates {
                flex: 1;
            }
            .sxs-candidates-list {
                width: 100%;
                margin-top: 10px;
            }
            .sxs-candidate-controls {
                display: flex;
                flex-direction: column;
                gap: 10px;
                padding-top: 40px;
            }
        </style>

        <script>
        jQuery(document).ready(function($) {
            // Make selected candidates sortable
            $('#sxs-selected-candidates').sortable({
                items: 'option',
                containment: 'parent'
            });

            // Add candidate
            $('#sxs-add-candidate').on('click', function() {
                $('#sxs-available-candidates option:selected').each(function() {
                    var option = $(this);
                    option.prop('selected', false);
                    option.appendTo('#sxs-selected-candidates');
                });
            });

            // Remove candidate
            $('#sxs-remove-candidate').on('click', function() {
                $('#sxs-selected-candidates option:selected').each(function() {
                    var option = $(this);
                    option.prop('selected', false);
                    option.appendTo('#sxs-available-candidates');
                });
            });

            // Select all options before form submit
            $('form#post').on('submit', function() {
                $('#sxs-selected-candidates option').prop('selected', true);
            });
        });
        </script>
        <?php
    }

    public function render_shortcode_meta_box($post) {
        ?>
        <p><?php _e('Use this shortcode to display the comparison:', 'sxs-candidate-comparison'); ?></p>
        <code>[sxs_candidate_comparison set="<?php echo $post->ID; ?>"]</code>
        <p class="description">
            <?php _e('Copy and paste this shortcode into any post or page where you want to display this comparison.', 'sxs-candidate-comparison'); ?>
        </p>
        <?php
    }

    public function save_meta_boxes($post_id) {
        // Verify nonce
        if (!isset($_POST['sxs_comparison_candidates_nonce']) ||
            !wp_verify_nonce($_POST['sxs_comparison_candidates_nonce'], 'sxs_comparison_candidates_nonce')) {
            return;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save selected candidates
        if (isset($_POST['sxs_selected_candidates'])) {
            $selected_candidates = array_map('intval', $_POST['sxs_selected_candidates']);
            update_post_meta($post_id, '_sxs_selected_candidates', $selected_candidates);
        } else {
            delete_post_meta($post_id, '_sxs_selected_candidates');
        }
    }

    public function modify_row_actions($actions, $post) {
        if ($post->post_type === 'sxs_comparison') {
            // Add view link that opens in new tab
            $actions['view'] = sprintf(
                '<a href="%s" target="_blank">%s</a>',
                esc_url(get_permalink($post->ID)),
                esc_html__('View Comparison', 'sxs-candidate-comparison')
            );
        }
        return $actions;
    }

    public function load_comparison_template($template) {
        if (is_singular('sxs_comparison')) {
            $custom_template = SXS_CC_PLUGIN_DIR . 'templates/single-sxs-comparison.php';
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }
        return $template;
    }

    public function enqueue_frontend_scripts() {
        if (is_singular('sxs_comparison')) {
            wp_enqueue_style(
                'sxs-comparison-view',
                plugins_url('assets/css/sxs-comparison-view.css', dirname(__FILE__)),
                array(),
                SXS_CC_VERSION
            );
        }
    }

    public function set_custom_columns($columns) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = $columns['title'];
        $new_columns['candidates'] = __('Candidates', 'sxs-candidate-comparison');
        $new_columns['shortcode'] = __('Shortcode', 'sxs-candidate-comparison');
        $new_columns['view'] = __('View', 'sxs-candidate-comparison');
        $new_columns['date'] = $columns['date'];
        return $new_columns;
    }

    public function custom_column($column, $post_id) {
        switch ($column) {
            case 'candidates':
                $selected_candidates = get_post_meta($post_id, '_sxs_selected_candidates', true);
                if (is_array($selected_candidates)) {
                    $candidate_names = array();
                    foreach ($selected_candidates as $candidate_id) {
                        $candidate = get_post($candidate_id);
                        if ($candidate) {
                            $candidate_names[] = $candidate->post_title;
                        }
                    }
                    echo esc_html(implode(', ', $candidate_names));
                }
                break;

            case 'shortcode':
                echo '<code>[sxs_candidate_comparison set="' . $post_id . '"]</code>';
                break;

            case 'view':
                printf(
                    '<a href="%s" target="_blank" class="button button-small">%s</a>',
                    esc_url(get_permalink($post_id)),
                    esc_html__('View', 'sxs-candidate-comparison')
                );
                break;
        }
    }
} 