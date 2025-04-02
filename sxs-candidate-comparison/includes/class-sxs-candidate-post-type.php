<?php

if (!defined('ABSPATH')) {
    exit;
}

class SXS_Candidate_Post_Type {
    
    public function init() {
        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('admin_init', array($this, 'hide_editor'));
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
} 