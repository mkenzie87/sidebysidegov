<?php
/**
 * Help Documentation for SXS Candidate Comparison Plugin
 *
 * @package SXS_Candidate_Comparison
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class SXS_Help_Docs
 * Handles the help documentation functionality
 */
class SXS_Help_Docs {

    /**
     * Initialize the help documentation
     */
    public function init() {
        add_action('admin_menu', array($this, 'add_help_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    /**
     * Add help documentation menu
     */
    public function add_help_menu() {
        add_submenu_page(
            'edit.php?post_type=sxs_candidate',
            __('Help Documentation', 'sxs-candidate-comparison'),
            __('Help Docs', 'sxs-candidate-comparison'),
            'manage_options',
            'sxs-help-docs',
            array($this, 'render_help_page')
        );
    }

    /**
     * Enqueue scripts and styles for the help page
     */
    public function enqueue_scripts($hook) {
        if ('sxs_candidate_page_sxs-help-docs' !== $hook) {
            return;
        }

        wp_enqueue_style(
            'sxs-help-docs',
            plugins_url('assets/css/sxs-help-docs.css', dirname(__FILE__)),
            array(),
            SXS_CC_VERSION
        );

        // Ensure jQuery is loaded
        wp_enqueue_script('jquery');
        
        // Add inline styles for notices
        $notice_styles = "
            .sxs-notice {
                margin: 15px 0;
                padding: 12px 15px;
                border-radius: 4px;
                border-left: 4px solid;
            }
            .sxs-notice p {
                margin: 0;
            }
            .sxs-notice-info {
                background-color: #f0f6fc;
                border-left-color: #72aee6;
            }
            .sxs-notice-warning {
                background-color: #fcf9e8;
                border-left-color: #dba617;
            }
        ";
        wp_add_inline_style('sxs-help-docs', $notice_styles);
    }

    /**
     * Render the help documentation page
     */
    public function render_help_page() {
        ?>
        <div class="wrap sxs-help-docs">
            <h1><?php _e('Side by Side Candidate Comparison - Help Documentation', 'sxs-candidate-comparison'); ?></h1>
            
            <div class="sxs-help-tabs">
                <nav class="nav-tab-wrapper">
                    <a href="#getting-started" class="nav-tab nav-tab-active" data-tab="getting-started"><?php _e('Getting Started', 'sxs-candidate-comparison'); ?></a>
                    <a href="#candidates" class="nav-tab" data-tab="candidates"><?php _e('Managing Candidates', 'sxs-candidate-comparison'); ?></a>
                    <a href="#comparisons" class="nav-tab" data-tab="comparisons"><?php _e('Creating Comparisons', 'sxs-candidate-comparison'); ?></a>
                    <a href="#styling" class="nav-tab" data-tab="styling"><?php _e('Styling Options', 'sxs-candidate-comparison'); ?></a>
                </nav>
                
                <div class="sxs-tab-content">
                    <!-- Getting Started Tab -->
                    <div id="getting-started" class="tab-pane active">
                        <h2><?php _e('Getting Started with Side by Side Candidate Comparison', 'sxs-candidate-comparison'); ?></h2>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('Overview', 'sxs-candidate-comparison'); ?></h3>
                            <p><?php _e('The Side by Side Candidate Comparison plugin allows you to create beautiful, responsive comparison tables for candidates. This tool is perfect for displaying job candidates, political candidates, or any type of profile that requires side-by-side comparison.', 'sxs-candidate-comparison'); ?></p>
                        </div>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('Key Features', 'sxs-candidate-comparison'); ?></h3>
                            <ul>
                                <li><?php _e('Responsive comparison grid for all devices', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Custom post types for candidates', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Horizontal scrolling for more than 3 candidates', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Sticky left column for better usability', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Clean, modern styling with alternating columns', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Reusable comparison sets', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Resume upload and download functionality', 'sxs-candidate-comparison'); ?></li>
                            </ul>
                        </div>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('Basic Workflow', 'sxs-candidate-comparison'); ?></h3>
                            <ol>
                                <li><?php _e('<strong>Set Up Candidates</strong> - Add candidate profiles including resumes and information', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('<strong>Create Comparison Sets</strong> - Select candidates to include in comparisons', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('<strong>View Comparisons</strong> - Access your comparisons via the permalink', 'sxs-candidate-comparison'); ?></li>
                            </ol>
                        </div>
                    </div>
                    
                    <!-- Managing Candidates Tab -->
                    <div id="candidates" class="tab-pane">
                        <h2><?php _e('Managing Candidates', 'sxs-candidate-comparison'); ?></h2>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('About Candidate Profiles', 'sxs-candidate-comparison'); ?></h3>
                            <p class="sxs-notice sxs-notice-info"><?php _e('Candidate profiles are for internal organization only and do not create public-facing pages. They\'re designed to be included in comparison sets, which is what your users will see.', 'sxs-candidate-comparison'); ?></p>
                        </div>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('Adding a New Candidate', 'sxs-candidate-comparison'); ?></h3>
                            <ol>
                                <li><?php _e('From the WordPress dashboard, go to <strong>Side by Side > All Candidates > Add New</strong>', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Enter the candidate\'s name in the title field', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Fill in all the relevant fields in the metaboxes below:', 'sxs-candidate-comparison'); ?>
                                    <ul>
                                        <li><?php _e('<strong>Current Company/Title</strong> - Current employer and position', 'sxs-candidate-comparison'); ?></li>
                                        <li><?php _e('<strong>Degrees/Certifications</strong> - Educational background and certifications', 'sxs-candidate-comparison'); ?></li>
                                        <li><?php _e('<strong>Experience</strong> - Years of industry and role-specific experience', 'sxs-candidate-comparison'); ?></li>
                                        <li><?php _e('<strong>Relevant Experience</strong> - Key accomplishments and qualifications', 'sxs-candidate-comparison'); ?></li>
                                        <li><?php _e('<strong>Compensation</strong> - Current and application compensation details', 'sxs-candidate-comparison'); ?></li>
                                    </ul>
                                </li>
                                <li><?php _e('Click <strong>Publish</strong> to save the candidate', 'sxs-candidate-comparison'); ?></li>
                            </ol>
                        </div>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('Editing Existing Candidates', 'sxs-candidate-comparison'); ?></h3>
                            <ol>
                                <li><?php _e('Go to <strong>Side by Side > All Candidates</strong>', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Find the candidate you want to edit and click on their name', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Make your changes and click <strong>Update</strong>', 'sxs-candidate-comparison'); ?></li>
                            </ol>
                        </div>
                    </div>
                    
                    <!-- Creating Comparisons Tab -->
                    <div id="comparisons" class="tab-pane">
                        <h2><?php _e('Creating Comparisons', 'sxs-candidate-comparison'); ?></h2>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('Using Comparison Sets', 'sxs-candidate-comparison'); ?></h3>
                            <p><?php _e('Comparison Sets allow you to save groups of candidates for easy reuse and sharing.', 'sxs-candidate-comparison'); ?></p>
                            
                            <h4><?php _e('Creating a Comparison Set', 'sxs-candidate-comparison'); ?></h4>
                            <ol>
                                <li><?php _e('Go to <strong>Side by Side > Comparisons > Add New</strong>', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Enter a title for your comparison (e.g., "Marketing Director Candidates")', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('In the <strong>Select Candidates</strong> box, check the candidates you want to include', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('You can drag and drop to reorder the candidates as needed', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Add Position Brief and Crelate Portal links if available', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Click <strong>Publish</strong> to save your comparison set', 'sxs-candidate-comparison'); ?></li>
                            </ol>
                            
                            <h4><?php _e('Using Your Comparison Set', 'sxs-candidate-comparison'); ?></h4>
                            <p><?php _e('After saving your comparison set, you can:', 'sxs-candidate-comparison'); ?></p>
                            <ul>
                                <li><?php _e('Click the <strong>View</strong> link to see the comparison directly', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Share the permalink to the comparison page', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('View candidates\' resumes by clicking the "Download Resume" button on the comparison page', 'sxs-candidate-comparison'); ?></li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Styling Options Tab -->
                    <div id="styling" class="tab-pane">
                        <h2><?php _e('Styling Options', 'sxs-candidate-comparison'); ?></h2>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('Default Styling', 'sxs-candidate-comparison'); ?></h3>
                            <p><?php _e('The plugin comes with carefully designed default styles that work well in most WordPress themes:', 'sxs-candidate-comparison'); ?></p>
                            <ul>
                                <li><?php _e('<strong>Headers:</strong> Navy blue background with white text for candidate names', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('<strong>Left Column:</strong> Orange header for "SIDE BY SIDE" text, white backgrounds with orange text for row headers', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('<strong>Content Columns:</strong> Alternating white and light gray backgrounds for easy scanning', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('<strong>Mobile:</strong> Responsive design with horizontal scrolling for more than 3 candidates', 'sxs-candidate-comparison'); ?></li>
                            </ul>
                        </div>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('Custom CSS', 'sxs-candidate-comparison'); ?></h3>
                            <p><?php _e('You can customize the appearance of your comparisons by adding custom CSS to your theme. Here are some common CSS selectors:', 'sxs-candidate-comparison'); ?></p>
                            
                            <table class="widefat sxs-help-table">
                                <thead>
                                    <tr>
                                        <th><?php _e('Element', 'sxs-candidate-comparison'); ?></th>
                                        <th><?php _e('CSS Selector', 'sxs-candidate-comparison'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php _e('Comparison Container', 'sxs-candidate-comparison'); ?></td>
                                        <td><code>.sxs-comparison-container</code></td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Header Row', 'sxs-candidate-comparison'); ?></td>
                                        <td><code>.sxs-comparison-header</code></td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Candidate Names', 'sxs-candidate-comparison'); ?></td>
                                        <td><code>.sxs-comparison-header .sxs-col</code></td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Side by Side Header', 'sxs-candidate-comparison'); ?></td>
                                        <td><code>.sxs-comparison-header .sxs-col-header</code></td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Row Headers', 'sxs-candidate-comparison'); ?></td>
                                        <td><code>.sxs-col-header</code></td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Content Cells', 'sxs-candidate-comparison'); ?></td>
                                        <td><code>.sxs-col</code></td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <h4><?php _e('Example Custom CSS', 'sxs-candidate-comparison'); ?></h4>
                            <p><?php _e('Here\'s an example of custom CSS to change the header color:', 'sxs-candidate-comparison'); ?></p>
                            <pre>
/* Change the header background color */
.sxs-comparison-header .sxs-col {
    background-color: #4a6741 !important; /* Green */
}

/* Change the left column header color */
.sxs-comparison-header .sxs-col-header {
    background-color: #8a4f2d !important; /* Brown */
}
                            </pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            // Tab functionality
            $('.sxs-help-tabs .nav-tab').on('click', function(e) {
                e.preventDefault();
                
                // Get the tab to show
                var tabId = $(this).data('tab');
                
                // Hide all tabs and show the selected one
                $('.sxs-help-tabs .tab-pane').removeClass('active');
                $('#' + tabId).addClass('active');
                
                // Update active tab
                $('.sxs-help-tabs .nav-tab').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');
                
                // Update URL hash
                window.location.hash = tabId;
            });
            
            // Check for hash in URL
            if (window.location.hash) {
                var hash = window.location.hash.substring(1);
                $('.sxs-help-tabs .nav-tab[data-tab="' + hash + '"]').trigger('click');
            }
        });
        </script>
        <?php
    }
} 