<?php

if (!defined('ABSPATH')) {
    exit;
}

class SXS_Candidate_Post_Type {
    
    public function init() {
        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_taxonomies'));
    }

    public function register_post_type() {
        $labels = array(
            'name'                  => _x('SxS Candidates', 'Post type general name', 'sxs-candidate-comparison'),
            'singular_name'         => _x('SxS Candidate', 'Post type singular name', 'sxs-candidate-comparison'),
            'menu_name'            => _x('SxS Candidates', 'Admin Menu text', 'sxs-candidate-comparison'),
            'add_new'              => __('Add New', 'sxs-candidate-comparison'),
            'add_new_item'         => __('Add New Candidate', 'sxs-candidate-comparison'),
            'edit_item'            => __('Edit Candidate', 'sxs-candidate-comparison'),
            'new_item'             => __('New Candidate', 'sxs-candidate-comparison'),
            'view_item'            => __('View Candidate', 'sxs-candidate-comparison'),
            'search_items'         => __('Search Candidates', 'sxs-candidate-comparison'),
            'not_found'            => __('No candidates found', 'sxs-candidate-comparison'),
            'not_found_in_trash'   => __('No candidates found in Trash', 'sxs-candidate-comparison'),
        );

        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'sxs-candidate'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 5,
            'menu_icon'          => 'dashicons-groups',
            'supports'           => array(
                'title',
                'editor',
                'thumbnail',
                'revisions',
                'custom-fields',
            ),
            'show_in_rest'       => true, // Enable Gutenberg editor
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
} 