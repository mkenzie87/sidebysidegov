<?php
/**
 * Job Post Type Class
 * 
 * Handles the registration and configuration of the SXS Job post type
 */

if (!defined('ABSPATH')) {
    exit;
}

class SXS_Job_Post_Type {
    
    public function init() {
        add_action('init', array($this, 'register_post_type'));
        add_filter('manage_sxs_job_posts_columns', array($this, 'set_custom_columns'));
        add_action('manage_sxs_job_posts_custom_column', array($this, 'custom_column'), 10, 2);
        add_filter('manage_edit-sxs_job_sortable_columns', array($this, 'sortable_columns'));
        add_action('pre_get_posts', array($this, 'sort_columns'));
    }
    
    /**
     * Register the Job post type
     */
    public function register_post_type() {
        $labels = array(
            'name'                  => _x('Jobs', 'Post type general name', 'sxs-candidate-comparison'),
            'singular_name'         => _x('Job', 'Post type singular name', 'sxs-candidate-comparison'),
            'menu_name'             => _x('Jobs', 'Admin Menu text', 'sxs-candidate-comparison'),
            'name_admin_bar'        => _x('Job', 'Add New on Toolbar', 'sxs-candidate-comparison'),
            'add_new'               => __('Add New', 'sxs-candidate-comparison'),
            'add_new_item'          => __('Add New Job', 'sxs-candidate-comparison'),
            'new_item'              => __('New Job', 'sxs-candidate-comparison'),
            'edit_item'             => __('Edit Job', 'sxs-candidate-comparison'),
            'view_item'             => __('View Job', 'sxs-candidate-comparison'),
            'all_items'             => __('All Jobs', 'sxs-candidate-comparison'),
            'search_items'          => __('Search Jobs', 'sxs-candidate-comparison'),
            'parent_item_colon'     => __('Parent Jobs:', 'sxs-candidate-comparison'),
            'not_found'             => __('No jobs found.', 'sxs-candidate-comparison'),
            'not_found_in_trash'    => __('No jobs found in Trash.', 'sxs-candidate-comparison'),
            'featured_image'        => _x('Job Cover Image', 'Overrides the "Featured Image" phrase', 'sxs-candidate-comparison'),
            'set_featured_image'    => _x('Set cover image', 'Overrides the "Set featured image" phrase', 'sxs-candidate-comparison'),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the "Remove featured image" phrase', 'sxs-candidate-comparison'),
            'use_featured_image'    => _x('Use as cover image', 'Overrides the "Use as featured image" phrase', 'sxs-candidate-comparison'),
            'archives'              => _x('Job archives', 'The post type archive label used in nav menus', 'sxs-candidate-comparison'),
            'insert_into_item'      => _x('Insert into job', 'Overrides the "Insert into post" phrase', 'sxs-candidate-comparison'),
            'uploaded_to_this_item' => _x('Uploaded to this job', 'Overrides the "Uploaded to this post" phrase', 'sxs-candidate-comparison'),
            'filter_items_list'     => _x('Filter jobs list', 'Screen reader text for the filter links', 'sxs-candidate-comparison'),
            'items_list_navigation' => _x('Jobs list navigation', 'Screen reader text for the pagination', 'sxs-candidate-comparison'),
            'items_list'            => _x('Jobs list', 'Screen reader text for the items list', 'sxs-candidate-comparison'),
        );
        
        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => 'edit.php?post_type=sxs_candidate',
            'query_var'          => false,
            'rewrite'            => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 25,
            'supports'           => array('title', 'thumbnail'),
            'menu_icon'          => 'dashicons-businessman',
        );
        
        register_post_type('sxs_job', $args);
    }
    
    /**
     * Set custom columns for job listing
     */
    public function set_custom_columns($columns) {
        $new_columns = array();
        
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = __('Job Title', 'sxs-candidate-comparison');
        $new_columns['job_company'] = __('Company', 'sxs-candidate-comparison');
        $new_columns['job_location'] = __('Location', 'sxs-candidate-comparison');
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }
    
    /**
     * Display custom column content
     */
    public function custom_column($column, $post_id) {
        switch ($column) {
            case 'job_company':
                $company_id = get_post_meta($post_id, '_sxs_job_company_id', true);
                if (!empty($company_id)) {
                    $company = get_post($company_id);
                    if ($company) {
                        $logo_id = get_post_meta($company_id, '_sxs_company_logo_id', true);
                        $logo_html = '';
                        
                        if (!empty($logo_id)) {
                            $image = wp_get_attachment_image_src($logo_id, array(30, 30));
                            if ($image) {
                                $logo_html = '<img src="' . esc_url($image[0]) . '" alt="' . esc_attr__('Company Logo', 'sxs-candidate-comparison') . '" style="max-width: 30px; max-height: 30px; vertical-align: middle; margin-right: 5px; border-radius: 3px;">';
                            }
                        }
                        
                        echo $logo_html . '<strong>' . esc_html($company->post_title) . '</strong>';
                    } else {
                        echo '<em>' . __('Invalid company', 'sxs-candidate-comparison') . '</em>';
                    }
                } else {
                    echo '<em>' . __('None', 'sxs-candidate-comparison') . '</em>';
                }
                break;
                
            case 'job_location':
                $location = get_post_meta($post_id, '_sxs_job_location', true);
                if (!empty($location)) {
                    echo esc_html($location);
                } else {
                    echo '<em>' . __('Not specified', 'sxs-candidate-comparison') . '</em>';
                }
                break;
        }
    }
    
    /**
     * Make columns sortable
     */
    public function sortable_columns($columns) {
        $columns['job_location'] = 'job_location';
        return $columns;
    }
    
    /**
     * Handle sorting of custom columns
     */
    public function sort_columns($query) {
        if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== 'sxs_job') {
            return;
        }
        
        $orderby = $query->get('orderby');
        
        if ('job_location' === $orderby) {
            $query->set('meta_key', '_sxs_job_location');
            $query->set('orderby', 'meta_value');
        }
    }
} 