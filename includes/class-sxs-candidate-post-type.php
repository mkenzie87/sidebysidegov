<?php

if (!defined('ABSPATH')) {
    exit;
}

class SXS_Candidate_Post_Type {
    
    /**
     * Initialize the candidate post type
     */
    public function init() {
        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('admin_init', array($this, 'hide_editor'));
        add_filter('manage_sxs_candidate_posts_columns', array($this, 'set_columns'));
        add_action('manage_sxs_candidate_posts_custom_column', array($this, 'column_content'), 10, 2);
        add_filter('manage_edit-sxs_candidate_sortable_columns', array($this, 'sortable_columns'));
        
        // Clone functionality
        add_filter('post_row_actions', array($this, 'add_clone_action'), 10, 2);
        add_action('admin_action_sxs_clone_candidate', array($this, 'clone_candidate'));
        add_action('admin_notices', array($this, 'clone_admin_notice'));
    }

    public function register_post_type() {
        $labels = array(
            'name'                  => _x('All Candidates', 'Post type general name', 'sxs-candidate-comparison'),
            'singular_name'         => _x('Candidate', 'Post type singular name', 'sxs-candidate-comparison'),
            'menu_name'            => _x('Side by Side', 'Admin Menu text', 'sxs-candidate-comparison'),
            'add_new'              => __('Add New', 'sxs-candidate-comparison'),
            'add_new_item'         => __('Add New Candidate', 'sxs-candidate-comparison'),
            'edit_item'            => __('Edit Candidate', 'sxs-candidate-comparison'),
            'new_item'             => __('New Candidate', 'sxs-candidate-comparison'),
            'view_item'            => __('View Candidate', 'sxs-candidate-comparison'),
            'search_items'         => __('Search Candidates', 'sxs-candidate-comparison'),
            'not_found'            => __('No candidates found', 'sxs-candidate-comparison'),
            'not_found_in_trash'   => __('No candidates found in Trash', 'sxs-candidate-comparison'),
            'all_items'            => __('All Candidates', 'sxs-candidate-comparison'),
        );

        $args = array(
            'labels'              => $labels,
            'public'              => false,
            'publicly_queryable'  => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => false,
            'rewrite'            => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 5,
            'menu_icon'          => 'dashicons-groups',
            'supports'           => array(
                'title',
                'thumbnail',
                'revisions',
                'custom-fields',
            ),
            'show_in_rest'       => false, // Disable Gutenberg editor
        );

        register_post_type('sxs_candidate', $args);
    }

    public function register_taxonomies() {
        // Register taxonomies if needed
        $labels = array(
            'name'              => _x('Candidate Categories', 'taxonomy general name', 'sxs-candidate-comparison'),
            'singular_name'     => _x('Candidate Category', 'taxonomy singular name', 'sxs-candidate-comparison'),
            'search_items'      => __('Search Categories', 'sxs-candidate-comparison'),
            'all_items'         => __('All Categories', 'sxs-candidate-comparison'),
            'parent_item'       => __('Parent Category', 'sxs-candidate-comparison'),
            'parent_item_colon' => __('Parent Category:', 'sxs-candidate-comparison'),
            'edit_item'         => __('Edit Category', 'sxs-candidate-comparison'),
            'update_item'       => __('Update Category', 'sxs-candidate-comparison'),
            'add_new_item'      => __('Add New Category', 'sxs-candidate-comparison'),
            'new_item_name'     => __('New Category Name', 'sxs-candidate-comparison'),
            'menu_name'         => __('Categories', 'sxs-candidate-comparison'),
        );

        register_taxonomy('sxs_candidate_category', 'sxs_candidate', array(
            'hierarchical'      => true,
            'labels'           => $labels,
            'show_ui'          => true,
            'show_admin_column' => true,
            'query_var'        => true,
            'rewrite'          => array('slug' => 'candidate-category'),
            'show_in_rest'     => true,
        ));
    }

    /**
     * Hide the standard editor on candidate edit screens
     */
    public function hide_editor() {
        global $pagenow;
        
        if (!('post.php' == $pagenow || 'post-new.php' == $pagenow)) {
            return;
        }
        
        // Get the post type
        global $post;
        $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : (isset($post) ? $post->post_type : '');
        
        // If we're on the candidate edit screen
        if ('sxs_candidate' == $post_type) {
            // Remove editor
            remove_post_type_support('sxs_candidate', 'editor');
            
            // Add CSS to hide any remaining editor elements
            add_action('admin_head', function() {
                echo '<style type="text/css">
                    #postdivrich, #wp-content-editor-container, 
                    #wp-content-editor-tools, .wp-editor-area {
                        display: none;
                    }
                </style>';
            });
        }
    }

    /**
     * Add clone action to candidate list
     */
    public function add_clone_action($actions, $post) {
        // Only add for candidate post type
        if ($post->post_type === 'sxs_candidate') {
            $actions['clone'] = sprintf(
                '<a href="%s">%s</a>',
                wp_nonce_url(
                    admin_url('admin.php?action=sxs_clone_candidate&post=' . $post->ID),
                    'sxs_clone_candidate_' . $post->ID
                ),
                __('Clone', 'sxs-candidate-comparison')
            );
        }
        return $actions;
    }

    /**
     * Clone candidate post and all its meta data
     */
    public function clone_candidate() {
        // Check if post ID is provided
        if (!isset($_GET['post'])) {
            wp_die(__('No candidate to clone.', 'sxs-candidate-comparison'));
        }

        // Check nonce
        $post_id = intval($_GET['post']);
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'sxs_clone_candidate_' . $post_id)) {
            wp_die(__('Security check failed.', 'sxs-candidate-comparison'));
        }

        // Get the original post
        $post = get_post($post_id);
        if (!$post) {
            wp_die(__('Candidate not found.', 'sxs-candidate-comparison'));
        }

        // Create new post data array
        $new_post_data = array(
            'post_title'     => $post->post_title . ' (Copy)',
            'post_content'   => $post->post_content,
            'post_excerpt'   => $post->post_excerpt,
            'post_type'      => $post->post_type,
            'post_status'    => 'draft',
            'ping_status'    => $post->ping_status,
            'comment_status' => $post->comment_status,
            'post_author'    => get_current_user_id(),
        );

        // Insert new post
        $new_post_id = wp_insert_post($new_post_data);

        if (is_wp_error($new_post_id)) {
            wp_die($new_post_id->get_error_message());
        }

        // Get all meta data
        $meta_keys = get_post_custom_keys($post_id);
        if ($meta_keys) {
            foreach ($meta_keys as $meta_key) {
                // Skip internal meta keys
                if (in_array($meta_key, array('_edit_lock', '_edit_last', '_wp_old_slug'))) {
                    continue;
                }
                
                $meta_values = get_post_custom_values($meta_key, $post_id);
                foreach ($meta_values as $meta_value) {
                    $meta_value = maybe_unserialize($meta_value);
                    add_post_meta($new_post_id, $meta_key, $meta_value);
                }
            }
        }

        // Copy taxonomy terms
        $taxonomies = get_object_taxonomies($post->post_type);
        foreach ($taxonomies as $taxonomy) {
            $terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
            wp_set_object_terms($new_post_id, $terms, $taxonomy);
        }

        // Set clone flag for admin notice
        set_transient('sxs_candidate_cloned', $new_post_id, 60);

        // Redirect to edit screen
        wp_safe_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
        exit;
    }

    /**
     * Display admin notice after cloning
     */
    public function clone_admin_notice() {
        $new_post_id = get_transient('sxs_candidate_cloned');
        
        if ($new_post_id) {
            delete_transient('sxs_candidate_cloned');
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php _e('Candidate cloned successfully. You are now editing the clone.', 'sxs-candidate-comparison'); ?></p>
            </div>
            <?php
        }
    }
} 