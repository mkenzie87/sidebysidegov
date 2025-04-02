# Side by Side Candidate Comparison Plugin

A WordPress plugin for creating beautiful side-by-side candidate comparisons for recruitment professionals.

## Features

- Custom post type for managing candidate profiles
- Side-by-side comparison of multiple candidates
- Responsive design for all devices
- Print-friendly layouts
- Shortcode support for easy embedding
- Customizable styling options
- Modern, clean interface

## Installation

1. Upload the `sxs-candidate-comparison` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Start creating candidate profiles under the 'SxS Candidates' menu item

## Usage

### Creating Candidate Profiles

1. Navigate to 'SxS Candidates' in the WordPress admin menu
2. Click 'Add New' to create a new candidate profile
3. Fill in the candidate details:
   - Name/Title
   - Current Company/Title
   - Education/Certifications
   - Years of Experience
   - Relevant Experience Summary
   - Compensation Details

### Displaying Comparisons

Use the shortcode `[sxs_candidate_comparison]` to display candidate comparisons. The shortcode accepts the following parameters:

- `ids`: Comma-separated list of candidate IDs to compare
- `category`: Category slug to filter candidates
- `limit`: Maximum number of candidates to display (default: 3)

Examples:

```
[sxs_candidate_comparison ids="1,2,3"]
[sxs_candidate_comparison category="executive" limit="4"]
```

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