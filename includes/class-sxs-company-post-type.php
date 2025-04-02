<?php

if (!defined('ABSPATH')) {
    exit;
}

class SXS_Company_Post_Type {
    
    public function init() {
        add_action('init', array($this, 'register_post_type'));
        add_filter('manage_sxs_company_posts_columns', array($this, 'set_custom_columns'));
        add_action('manage_sxs_company_posts_custom_column', array($this, 'custom_column'), 10, 2);
    }

    public function register_post_type() {
        $labels = array(
            'name'               => _x('Companies', 'post type general name', 'sxs-candidate-comparison'),
            'singular_name'      => _x('Company', 'post type singular name', 'sxs-candidate-comparison'),
            'menu_name'          => _x('Companies', 'admin menu', 'sxs-candidate-comparison'),
            'name_admin_bar'     => _x('Company', 'add new on admin bar', 'sxs-candidate-comparison'),
            'add_new'            => _x('Add New', 'company', 'sxs-candidate-comparison'),
            'add_new_item'       => __('Add New Company', 'sxs-candidate-comparison'),
            'new_item'           => __('New Company', 'sxs-candidate-comparison'),
            'edit_item'          => __('Edit Company', 'sxs-candidate-comparison'),
            'view_item'          => __('View Company', 'sxs-candidate-comparison'),
            'all_items'          => __('All Companies', 'sxs-candidate-comparison'),
            'search_items'       => __('Search Companies', 'sxs-candidate-comparison'),
            'parent_item_colon'  => __('Parent Companies:', 'sxs-candidate-comparison'),
            'not_found'          => __('No companies found.', 'sxs-candidate-comparison'),
            'not_found_in_trash' => __('No companies found in Trash.', 'sxs-candidate-comparison')
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __('Companies for comparison', 'sxs-candidate-comparison'),
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => 'edit.php?post_type=sxs_candidate',
            'query_var'          => false,
            'rewrite'            => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 5,
            'menu_icon'          => 'dashicons-building',
            'supports'           => array(
                'title',
                'author',
            ),
            'show_in_rest'       => true,
        );

        register_post_type('sxs_company', $args);
    }

    public function set_custom_columns($columns) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['logo'] = __('Logo', 'sxs-candidate-comparison');
        $new_columns['title'] = $columns['title'];
        $new_columns['location'] = __('Location', 'sxs-candidate-comparison');
        $new_columns['candidates'] = __('Candidates', 'sxs-candidate-comparison');
        $new_columns['date'] = $columns['date'];
        return $new_columns;
    }

    public function custom_column($column, $post_id) {
        switch ($column) {
            case 'logo':
                $logo_id = get_post_meta($post_id, '_sxs_company_logo_id', true);
                if (!empty($logo_id)) {
                    $image = wp_get_attachment_image_src($logo_id, array(50, 50));
                    if ($image) {
                        echo '<img src="' . esc_url($image[0]) . '" alt="' . esc_attr__('Company Logo', 'sxs-candidate-comparison') . '" style="max-width: 50px; max-height: 50px;">';
                    } else {
                        echo '<span class="dashicons dashicons-building" style="font-size: 30px; width: 30px; height: 30px;"></span>';
                    }
                } else {
                    echo '<span class="dashicons dashicons-building" style="font-size: 30px; width: 30px; height: 30px;"></span>';
                }
                break;

            case 'location':
                $location = get_post_meta($post_id, '_sxs_company_location', true);
                echo esc_html($location);
                break;

            case 'candidates':
                $args = array(
                    'post_type' => 'sxs_candidate',
                    'meta_query' => array(
                        array(
                            'key' => '_sxs_company_id',
                            'value' => $post_id,
                            'compare' => '=',
                        ),
                    ),
                    'posts_per_page' => -1,
                    'fields' => 'ids',
                );
                $candidates = get_posts($args);
                echo count($candidates);
                break;
        }
    }
} 