<?php
/**
 * SXS Dashboard Widget
 *
 * @package SXS_Candidate_Comparison
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class SXS_Dashboard_Widget
 * Handles the dashboard widget for quick stats and shortcuts
 */
class SXS_Dashboard_Widget {

    /**
     * Initialize the dashboard widget
     */
    public function init() {
        add_action('wp_dashboard_setup', array($this, 'add_dashboard_widget'));
    }

    /**
     * Add the dashboard widget
     */
    public function add_dashboard_widget() {
        // Only add for users who can manage options
        if (current_user_can('manage_options')) {
            wp_add_dashboard_widget(
                'sxs_dashboard_widget',
                __('SxS Candidate Comparison', 'sxs-candidate-comparison'),
                array($this, 'render_dashboard_widget')
            );
        }
    }

    /**
     * Render the dashboard widget
     */
    public function render_dashboard_widget() {
        // Get counts
        $candidate_count = $this->get_post_count('sxs_candidate');
        $comparison_count = $this->get_post_count('sxs_comparison');
        
        // Get recent items
        $recent_candidates = $this->get_recent_posts('sxs_candidate', 5);
        $recent_comparisons = $this->get_recent_posts('sxs_comparison', 5);
        
        // Calculate stats
        $most_compared_candidates = $this->get_most_compared_candidates(5);
        
        // Output the widget content
        ?>
        <div class="sxs-dashboard-widget">
            <div class="sxs-dashboard-stats">
                <div class="sxs-stat-box">
                    <span class="sxs-stat-number"><?php echo $candidate_count; ?></span>
                    <span class="sxs-stat-label"><?php _e('Candidates', 'sxs-candidate-comparison'); ?></span>
                </div>
                
                <div class="sxs-stat-box">
                    <span class="sxs-stat-number"><?php echo $comparison_count; ?></span>
                    <span class="sxs-stat-label"><?php _e('Comparisons', 'sxs-candidate-comparison'); ?></span>
                </div>
            </div>
            
            <div class="sxs-dashboard-actions">
                <a href="<?php echo admin_url('post-new.php?post_type=sxs_candidate'); ?>" class="button">
                    <?php _e('Add Candidate', 'sxs-candidate-comparison'); ?>
                </a>
                <a href="<?php echo admin_url('post-new.php?post_type=sxs_comparison'); ?>" class="button">
                    <?php _e('Create Comparison', 'sxs-candidate-comparison'); ?>
                </a>
                <a href="<?php echo admin_url('edit.php?post_type=sxs_candidate&page=sxs-help-docs'); ?>" class="button">
                    <?php _e('Help Docs', 'sxs-candidate-comparison'); ?>
                </a>
            </div>
            
            <?php if (!empty($recent_candidates)) : ?>
                <div class="sxs-dashboard-section">
                    <h3><?php _e('Recent Candidates', 'sxs-candidate-comparison'); ?></h3>
                    <ul>
                        <?php foreach ($recent_candidates as $candidate) : ?>
                            <li>
                                <a href="<?php echo get_edit_post_link($candidate->ID); ?>">
                                    <?php echo esc_html($candidate->post_title); ?>
                                </a>
                                <span class="sxs-post-date">
                                    <?php echo get_the_date('M j, Y', $candidate->ID); ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?php echo admin_url('edit.php?post_type=sxs_candidate'); ?>" class="sxs-view-all">
                        <?php _e('View All Candidates', 'sxs-candidate-comparison'); ?> &rarr;
                    </a>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($recent_comparisons)) : ?>
                <div class="sxs-dashboard-section">
                    <h3><?php _e('Recent Comparisons', 'sxs-candidate-comparison'); ?></h3>
                    <ul>
                        <?php foreach ($recent_comparisons as $comparison) : ?>
                            <li>
                                <a href="<?php echo get_edit_post_link($comparison->ID); ?>">
                                    <?php echo esc_html($comparison->post_title); ?>
                                </a>
                                <span class="sxs-post-date">
                                    <?php echo get_the_date('M j, Y', $comparison->ID); ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?php echo admin_url('edit.php?post_type=sxs_comparison'); ?>" class="sxs-view-all">
                        <?php _e('View All Comparisons', 'sxs-candidate-comparison'); ?> &rarr;
                    </a>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($most_compared_candidates)) : ?>
                <div class="sxs-dashboard-section">
                    <h3><?php _e('Most Compared Candidates', 'sxs-candidate-comparison'); ?></h3>
                    <ul>
                        <?php foreach ($most_compared_candidates as $candidate) : ?>
                            <li>
                                <a href="<?php echo get_edit_post_link($candidate['id']); ?>">
                                    <?php echo esc_html($candidate['title']); ?>
                                </a>
                                <span class="sxs-badge"><?php echo $candidate['count']; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
        
        <style>
            .sxs-dashboard-widget {
                overflow: hidden;
            }
            
            .sxs-dashboard-stats {
                display: flex;
                justify-content: space-around;
                margin-bottom: 20px;
                border-bottom: 1px solid #eee;
                padding-bottom: 15px;
            }
            
            .sxs-stat-box {
                text-align: center;
            }
            
            .sxs-stat-number {
                display: block;
                font-size: 24px;
                font-weight: 600;
                color: #1C2856;
            }
            
            .sxs-stat-label {
                font-size: 13px;
                color: #666;
            }
            
            .sxs-dashboard-actions {
                display: flex;
                justify-content: space-between;
                margin-bottom: 20px;
            }
            
            .sxs-dashboard-section {
                margin-top: 15px;
                border-top: 1px solid #eee;
                padding-top: 10px;
            }
            
            .sxs-dashboard-section h3 {
                margin: 0 0 10px;
                font-size: 14px;
                color: #23282d;
            }
            
            .sxs-dashboard-section ul {
                margin: 0;
            }
            
            .sxs-dashboard-section li {
                margin-bottom: 8px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .sxs-post-date {
                font-size: 11px;
                color: #888;
            }
            
            .sxs-badge {
                background: #F26724;
                color: #fff;
                border-radius: 10px;
                padding: 2px 6px;
                font-size: 11px;
                font-weight: 600;
            }
            
            .sxs-view-all {
                display: block;
                text-decoration: none;
                margin-top: 8px;
                font-size: 12px;
                text-align: right;
            }
        </style>
        <?php
    }

    /**
     * Get post count for a specific post type
     *
     * @param string $post_type Post type to count
     * @return int Post count
     */
    private function get_post_count($post_type) {
        $count_posts = wp_count_posts($post_type);
        return $count_posts->publish;
    }

    /**
     * Get recent posts of a specific type
     *
     * @param string $post_type Post type to retrieve
     * @param int $count Number of posts to retrieve
     * @return array Array of recent posts
     */
    private function get_recent_posts($post_type, $count = 5) {
        return get_posts(array(
            'post_type' => $post_type,
            'posts_per_page' => $count,
            'orderby' => 'date',
            'order' => 'DESC'
        ));
    }

    /**
     * Get the most compared candidates
     *
     * @param int $count Number of candidates to retrieve
     * @return array Array of candidates with comparison counts
     */
    private function get_most_compared_candidates($count = 5) {
        global $wpdb;
        
        // Get all comparisons
        $comparisons = get_posts(array(
            'post_type' => 'sxs_comparison',
            'posts_per_page' => -1,
            'fields' => 'ids'
        ));
        
        // If no comparisons found, return empty array
        if (empty($comparisons)) {
            return array();
        }
        
        // Count candidates
        $candidate_counts = array();
        
        foreach ($comparisons as $comparison_id) {
            $selected_candidates = get_post_meta($comparison_id, '_sxs_selected_candidates', true);
            
            if (is_array($selected_candidates)) {
                foreach ($selected_candidates as $candidate_id) {
                    if (!isset($candidate_counts[$candidate_id])) {
                        $candidate_counts[$candidate_id] = 0;
                    }
                    $candidate_counts[$candidate_id]++;
                }
            }
        }
        
        // Sort by count and get top results
        arsort($candidate_counts);
        $top_candidates = array_slice($candidate_counts, 0, $count, true);
        
        // Build result array with candidate details
        $result = array();
        foreach ($top_candidates as $candidate_id => $count) {
            $candidate = get_post($candidate_id);
            if ($candidate) {
                $result[] = array(
                    'id' => $candidate_id,
                    'title' => $candidate->post_title,
                    'count' => $count
                );
            }
        }
        
        return $result;
    }
} 