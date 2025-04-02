# Side by Side Candidate Comparison Plugin

A WordPress plugin for creating beautiful side-by-side candidate comparisons for recruitment professionals.

## Features

- Custom post types for managing candidate profiles, companies, and jobs
- Side-by-side comparison of multiple candidates
- Responsive design for all devices
- Print-friendly layouts
- Shortcode support for easy embedding
- Customizable styling options
- Modern, clean interface
- Multiple layout options (Standard, Premium, Minimal, Branded)

## Installation

1. Upload the `sxs-candidate-comparison` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Start creating candidate profiles under the 'Side by Side > All Candidates' menu item

## Usage

### Managing Internal Data

The plugin uses several custom post types to organize your data internally. These don't create public-facing pages:

#### Candidate Profiles
1. Navigate to 'Side by Side > All Candidates' in the WordPress admin menu
2. Click 'Add New' to create a new candidate profile in your internal database
3. Fill in the candidate details for use in comparisons

#### Company Profiles
1. Navigate to 'Side by Side > Companies' in the WordPress admin menu
2. Click 'Add New' to create a new company profile in your internal database
3. Fill in the company details for branding your comparisons

#### Job Listings
1. Navigate to 'Side by Side > Jobs' in the WordPress admin menu
2. Click 'Add New' to create a new job in your internal database
3. Fill in the job details to provide context for your comparisons

### Creating Public Comparisons

1. Navigate to 'Side by Side > Comparisons' in the WordPress admin menu
2. Click 'Add New' to create a comparison set
3. Select a job, candidates, and layout 
4. Publish the comparison
5. Use the generated shortcode to display the comparison on any page

### Displaying Comparisons

Use the shortcode `[sxs_candidate_comparison]` to display candidate comparisons. The shortcode accepts the following parameters:

- `ids`: Comma-separated list of candidate IDs to compare
- `category`: Category slug to filter candidates
- `job`: ID of a job to use for comparison context
- `layout`: Style to use (standard, premium, minimal, branded)
- `limit`: Maximum number of candidates to display (default: 3)

Examples:

```
[sxs_candidate_comparison ids="1,2,3"]
[sxs_candidate_comparison category="executive" limit="4"]
[sxs_candidate_comparison job="45" layout="premium"]
```

### Layout Options

The plugin provides four distinct layout options for candidate comparisons:

1. **Standard**: The default layout with clean, professional design. Includes job details in the header and all candidate information.
   
2. **Premium**: Enhanced layout with additional visual elements and more detailed profiles. Ideal for executive searches and client presentations.
   
3. **Minimal**: Simplified layout with only essential information. Perfect for internal reviews or preliminary candidate screenings.
   
4. **Branded**: Company-focused layout that uses brand colors and emphasizes company identity. Best for client-facing reports.

You can select a layout when creating a comparison set or specify it in the shortcode with the `layout` parameter.

### Styling

The plugin includes default styles that match modern recruitment presentations. You can customize the appearance by:

1. Adding custom CSS to your theme
2. Modifying the color scheme through WordPress customizer
3. Overriding the default styles in your theme's stylesheet

## Requirements

- WordPress 6.0 or higher
- PHP 7.4 or higher
- Modern web browser

## Support

For support, feature requests, or bug reports, please visit our [support forum](https://wordpress.org/support/plugin/sxs-candidate-comparison/) or create an issue on GitHub.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This plugin is licensed under the GPL v2 or later.

## Changelog

### 1.0.0
- Initial release
- Custom post type for candidates
- Side-by-side comparison functionality
- Responsive design
- Print-friendly layouts
- Shortcode support 