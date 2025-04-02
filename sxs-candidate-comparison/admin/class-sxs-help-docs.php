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
                    <a href="#companies" class="nav-tab" data-tab="companies"><?php _e('Managing Companies', 'sxs-candidate-comparison'); ?></a>
                    <a href="#jobs" class="nav-tab" data-tab="jobs"><?php _e('Managing Jobs', 'sxs-candidate-comparison'); ?></a>
                    <a href="#comparisons" class="nav-tab" data-tab="comparisons"><?php _e('Creating Comparisons', 'sxs-candidate-comparison'); ?></a>
                    <a href="#layouts" class="nav-tab" data-tab="layouts"><?php _e('Layouts', 'sxs-candidate-comparison'); ?></a>
                    <a href="#shortcodes" class="nav-tab" data-tab="shortcodes"><?php _e('Using Shortcodes', 'sxs-candidate-comparison'); ?></a>
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
                                <li><?php _e('Custom post types for candidates, companies, and jobs', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Job-focused comparison for proper candidate evaluation', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Company branding integration for consistent presentation', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Multiple layout options (Standard, Premium, Minimal, Branded)', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Horizontal scrolling for more than 3 candidates', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Sticky left column for better usability', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Clean, modern styling with alternating columns', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Flexible shortcode implementation', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Reusable comparison sets', 'sxs-candidate-comparison'); ?></li>
                            </ul>
                        </div>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('Basic Workflow', 'sxs-candidate-comparison'); ?></h3>
                            <ol>
                                <li><?php _e('<strong>Set Up Companies</strong> - Add companies that you\'ll be recruiting for (for internal database only)', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('<strong>Set Up Jobs</strong> - Add jobs with specific requirements and details (for internal database only)', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('<strong>Set Up Candidates</strong> - Add candidate profiles and information (for internal database only)', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('<strong>Create Comparison Sets</strong> - Select jobs and candidates to include in comparisons (this is what will be visible to users)', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('<strong>Use Shortcodes</strong> - Add comparisons to your pages or posts', 'sxs-candidate-comparison'); ?></li>
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
                    
                    <!-- Managing Companies Tab -->
                    <div id="companies" class="tab-pane">
                        <h2><?php _e('Managing Companies', 'sxs-candidate-comparison'); ?></h2>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('About Company Profiles', 'sxs-candidate-comparison'); ?></h3>
                            <p class="sxs-notice sxs-notice-info"><?php _e('Company profiles are for internal organization only and do not create public-facing pages. They provide branding and information for jobs and comparison sets.', 'sxs-candidate-comparison'); ?></p>
                        </div>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('Adding a New Company', 'sxs-candidate-comparison'); ?></h3>
                            <ol>
                                <li><?php _e('From the WordPress dashboard, go to <strong>Side by Side > Companies > Add New</strong>', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Enter the company name in the title field', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Fill in all the relevant fields in the metaboxes below:', 'sxs-candidate-comparison'); ?>
                                    <ul>
                                        <li><?php _e('<strong>Company Logo</strong> - Upload a company logo image', 'sxs-candidate-comparison'); ?></li>
                                        <li><?php _e('<strong>Company Details</strong> - Location, industry, size, etc.', 'sxs-candidate-comparison'); ?></li>
                                        <li><?php _e('<strong>Branding</strong> - Company colors and styling options', 'sxs-candidate-comparison'); ?></li>
                                        <li><?php _e('<strong>Contact Information</strong> - Website, email, phone', 'sxs-candidate-comparison'); ?></li>
                                    </ul>
                                </li>
                                <li><?php _e('Click <strong>Publish</strong> to save the company', 'sxs-candidate-comparison'); ?></li>
                            </ol>
                        </div>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('Editing Existing Companies', 'sxs-candidate-comparison'); ?></h3>
                            <ol>
                                <li><?php _e('Go to <strong>Side by Side > Companies</strong>', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Find the company you want to edit and click on its name', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Make your changes and click <strong>Update</strong>', 'sxs-candidate-comparison'); ?></li>
                            </ol>
                        </div>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('Using Companies in Comparisons', 'sxs-candidate-comparison'); ?></h3>
                            <p><?php _e('Companies can be associated with candidates and jobs to provide consistent branding and information across your comparisons:', 'sxs-candidate-comparison'); ?></p>
                            <ul>
                                <li><?php _e('When creating a candidate, you can select their current company', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('When creating a job, you can associate it with a company', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Company information and branding will automatically appear in comparisons', 'sxs-candidate-comparison'); ?></li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Managing Jobs Tab -->
                    <div id="jobs" class="tab-pane">
                        <h2><?php _e('Managing Jobs', 'sxs-candidate-comparison'); ?></h2>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('About Job Listings', 'sxs-candidate-comparison'); ?></h3>
                            <p class="sxs-notice sxs-notice-info"><?php _e('Job listings are for internal organization only and do not create public-facing pages. They provide context for candidate comparisons and are meant to be included in comparison sets.', 'sxs-candidate-comparison'); ?></p>
                        </div>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('Adding a New Job', 'sxs-candidate-comparison'); ?></h3>
                            <ol>
                                <li><?php _e('From the WordPress dashboard, go to <strong>Side by Side > Jobs > Add New</strong>', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Enter the job title in the title field', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Fill in all the relevant fields in the metaboxes below:', 'sxs-candidate-comparison'); ?>
                                    <ul>
                                        <li><?php _e('<strong>Job Details</strong> - Location, type (full-time, part-time, etc.), department', 'sxs-candidate-comparison'); ?></li>
                                        <li><?php _e('<strong>Company</strong> - Select the associated company', 'sxs-candidate-comparison'); ?></li>
                                        <li><?php _e('<strong>Requirements</strong> - Required skills, education, certifications, experience', 'sxs-candidate-comparison'); ?></li>
                                        <li><?php _e('<strong>Application</strong> - Compensation range, benefits, application process', 'sxs-candidate-comparison'); ?></li>
                                    </ul>
                                </li>
                                <li><?php _e('Click <strong>Publish</strong> to save the job', 'sxs-candidate-comparison'); ?></li>
                            </ol>
                        </div>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('Editing Existing Jobs', 'sxs-candidate-comparison'); ?></h3>
                            <ol>
                                <li><?php _e('Go to <strong>Side by Side > Jobs</strong>', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Find the job you want to edit and click on its title', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Make your changes and click <strong>Update</strong>', 'sxs-candidate-comparison'); ?></li>
                            </ol>
                        </div>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('Using Jobs in Comparisons', 'sxs-candidate-comparison'); ?></h3>
                            <p><?php _e('Jobs can be selected within comparison sets to provide context for candidate evaluations:', 'sxs-candidate-comparison'); ?></p>
                            <ol>
                                <li><?php _e('When creating a comparison set, select a job in the job selection field', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('You can also create a new job directly from the comparison editor', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Job details and requirements will be displayed in the comparison header', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Candidates will be compared against the job\'s requirements', 'sxs-candidate-comparison'); ?></li>
                            </ol>
                            <p><?php _e('This creates a more robust evaluation system where candidates are compared in the context of specific job requirements.', 'sxs-candidate-comparison'); ?></p>
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
                                <li><?php _e('Select a job for this comparison set (optional but recommended)', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('In the <strong>Select Candidates</strong> box, check the candidates you want to include', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('You can drag and drop to reorder the candidates as needed', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Choose a layout style for your comparison', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Click <strong>Publish</strong> to save your comparison set', 'sxs-candidate-comparison'); ?></li>
                            </ol>
                            
                            <h4><?php _e('Using Your Comparison Set', 'sxs-candidate-comparison'); ?></h4>
                            <p><?php _e('After saving your comparison set, you can:', 'sxs-candidate-comparison'); ?></p>
                            <ul>
                                <li><?php _e('Copy the shortcode from the metabox to use in any page or post', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Click the <strong>View</strong> link to see the comparison directly', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Share the permalink to the comparison page', 'sxs-candidate-comparison'); ?></li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Layouts Tab -->
                    <div id="layouts" class="tab-pane">
                        <h2><?php _e('Layout Options', 'sxs-candidate-comparison'); ?></h2>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('Available Layouts', 'sxs-candidate-comparison'); ?></h3>
                            <p><?php _e('The plugin offers multiple layout options that you can choose from when creating comparison sets:', 'sxs-candidate-comparison'); ?></p>
                            
                            <table class="widefat sxs-help-table">
                                <thead>
                                    <tr>
                                        <th><?php _e('Layout', 'sxs-candidate-comparison'); ?></th>
                                        <th><?php _e('Description', 'sxs-candidate-comparison'); ?></th>
                                        <th><?php _e('Best Used For', 'sxs-candidate-comparison'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong><?php _e('Standard', 'sxs-candidate-comparison'); ?></strong></td>
                                        <td><?php _e('The default layout with a clean, professional design featuring the core comparison table with job details in the header.', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('Most recruitment scenarios, general purpose comparisons.', 'sxs-candidate-comparison'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong><?php _e('Premium', 'sxs-candidate-comparison'); ?></strong></td>
                                        <td><?php _e('An enhanced layout with additional visual elements, more detailed candidate profiles, and prominent company branding.', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('Executive searches, high-value positions, client presentations.', 'sxs-candidate-comparison'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong><?php _e('Minimal', 'sxs-candidate-comparison'); ?></strong></td>
                                        <td><?php _e('A simplified, compact layout focusing only on essential candidate information with reduced visual elements.', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('Internal reviews, preliminary screenings, situations requiring less detail.', 'sxs-candidate-comparison'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong><?php _e('Branded', 'sxs-candidate-comparison'); ?></strong></td>
                                        <td><?php _e('A layout that emphasizes company branding, using company colors and featuring larger logos and imagery.', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('Client-facing documents, reports shared with hiring companies, branded presentations.', 'sxs-candidate-comparison'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('Layout Features Comparison', 'sxs-candidate-comparison'); ?></h3>
                            <table class="widefat sxs-help-table">
                                <thead>
                                    <tr>
                                        <th><?php _e('Feature', 'sxs-candidate-comparison'); ?></th>
                                        <th><?php _e('Standard', 'sxs-candidate-comparison'); ?></th>
                                        <th><?php _e('Premium', 'sxs-candidate-comparison'); ?></th>
                                        <th><?php _e('Minimal', 'sxs-candidate-comparison'); ?></th>
                                        <th><?php _e('Branded', 'sxs-candidate-comparison'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php _e('Job Requirements', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('✓', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('✓', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('Limited', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('✓', 'sxs-candidate-comparison'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Company Branding', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('Basic', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('Enhanced', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('Minimal', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('Maximum', 'sxs-candidate-comparison'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Candidate Photo', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('✓', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('Large', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('Small', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('✓', 'sxs-candidate-comparison'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Experience Details', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('✓', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('Detailed', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('Summary', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('✓', 'sxs-candidate-comparison'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Visual Elements', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('Standard', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('Rich', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('Basic', 'sxs-candidate-comparison'); ?></td>
                                        <td><?php _e('Company-themed', 'sxs-candidate-comparison'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('How to Select a Layout', 'sxs-candidate-comparison'); ?></h3>
                            <p><?php _e('You can select a layout in two ways:', 'sxs-candidate-comparison'); ?></p>
                            
                            <h4><?php _e('1. When Creating a Comparison Set', 'sxs-candidate-comparison'); ?></h4>
                            <ol>
                                <li><?php _e('Go to <strong>Side by Side > Comparisons > Add New</strong>', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('In the Layout section, select your preferred layout from the dropdown', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('You\'ll see a preview image of the selected layout update in real-time', 'sxs-candidate-comparison'); ?></li>
                                <li><?php _e('Save your comparison set, and it will use the selected layout', 'sxs-candidate-comparison'); ?></li>
                            </ol>
                            
                            <h4><?php _e('2. Using the Shortcode Parameter', 'sxs-candidate-comparison'); ?></h4>
                            <p><?php _e('Add the layout parameter to your shortcode:', 'sxs-candidate-comparison'); ?></p>
                            <ul>
                                <li><code>[sxs_candidate_comparison layout="standard"]</code></li>
                                <li><code>[sxs_candidate_comparison layout="premium"]</code></li>
                                <li><code>[sxs_candidate_comparison layout="minimal"]</code></li>
                                <li><code>[sxs_candidate_comparison layout="branded"]</code></li>
                            </ul>
                            <p><?php _e('This parameter will override any layout setting saved with the comparison set.', 'sxs-candidate-comparison'); ?></p>
                        </div>
                    </div>
                    
                    <!-- Using Shortcodes Tab -->
                    <div id="shortcodes" class="tab-pane">
                        <h2><?php _e('Using Shortcodes', 'sxs-candidate-comparison'); ?></h2>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('Basic Shortcode', 'sxs-candidate-comparison'); ?></h3>
                            <p><?php _e('The basic shortcode to display a comparison is:', 'sxs-candidate-comparison'); ?></p>
                            <code>[sxs_candidate_comparison]</code>
                        </div>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('Shortcode Parameters', 'sxs-candidate-comparison'); ?></h3>
                            <p><?php _e('You can customize the comparison display using these parameters:', 'sxs-candidate-comparison'); ?></p>
                            
                            <table class="widefat sxs-help-table">
                                <thead>
                                    <tr>
                                        <th><?php _e('Parameter', 'sxs-candidate-comparison'); ?></th>
                                        <th><?php _e('Description', 'sxs-candidate-comparison'); ?></th>
                                        <th><?php _e('Example', 'sxs-candidate-comparison'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>ids</code></td>
                                        <td><?php _e('Comma-separated list of candidate IDs to display', 'sxs-candidate-comparison'); ?></td>
                                        <td><code>[sxs_candidate_comparison ids="42,56,78"]</code></td>
                                    </tr>
                                    <tr>
                                        <td><code>set</code></td>
                                        <td><?php _e('ID of a saved comparison set to display', 'sxs-candidate-comparison'); ?></td>
                                        <td><code>[sxs_candidate_comparison set="123"]</code></td>
                                    </tr>
                                    <tr>
                                        <td><code>category</code></td>
                                        <td><?php _e('Category slug to filter candidates by (if used)', 'sxs-candidate-comparison'); ?></td>
                                        <td><code>[sxs_candidate_comparison category="executives"]</code></td>
                                    </tr>
                                    <tr>
                                        <td><code>job</code></td>
                                        <td><?php _e('ID of a job to use for the comparison', 'sxs-candidate-comparison'); ?></td>
                                        <td><code>[sxs_candidate_comparison job="45"]</code></td>
                                    </tr>
                                    <tr>
                                        <td><code>layout</code></td>
                                        <td><?php _e('Layout style to use (standard, premium, minimal, branded)', 'sxs-candidate-comparison'); ?></td>
                                        <td><code>[sxs_candidate_comparison layout="premium"]</code></td>
                                    </tr>
                                    <tr>
                                        <td><code>limit</code></td>
                                        <td><?php _e('Maximum number of candidates to display (default: 6)', 'sxs-candidate-comparison'); ?></td>
                                        <td><code>[sxs_candidate_comparison limit="4"]</code></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="sxs-help-section">
                            <h3><?php _e('Examples', 'sxs-candidate-comparison'); ?></h3>
                            <p><?php _e('Display a specific comparison set:', 'sxs-candidate-comparison'); ?></p>
                            <code>[sxs_candidate_comparison set="123"]</code>
                            
                            <p><?php _e('Display specific candidates in a custom order:', 'sxs-candidate-comparison'); ?></p>
                            <code>[sxs_candidate_comparison ids="42,56,78"]</code>
                            
                            <p><?php _e('Display candidates from a specific category with a limit:', 'sxs-candidate-comparison'); ?></p>
                            <code>[sxs_candidate_comparison category="executives" limit="3"]</code>
                            
                            <p><?php _e('Display candidates for a specific job with custom layout:', 'sxs-candidate-comparison'); ?></p>
                            <code>[sxs_candidate_comparison job="45" layout="premium"]</code>
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