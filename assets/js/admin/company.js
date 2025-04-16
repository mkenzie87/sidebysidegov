/**
 * SxS Company Admin JavaScript
 */
(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        initCompanyPreview();
        initShortcodeActions();
        initButtonsToggle();
    });

    /**
     * Initialize company preview functionality
     */
    function initCompanyPreview() {
        // Update preview when company selection changes
        $('#sxs_selected_company').on('change', function() {
            var companyId = $(this).val();
            
            if (companyId) {
                // Show loading message
                if (!$('.sxs-company-loading').length) {
                    $('<p class="sxs-company-loading">Loading company preview...</p>').insertAfter($(this));
                }
                
                // Make AJAX call to get company preview
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'sxs_get_company_preview',
                        company_id: companyId,
                        nonce: sxsAdmin.nonce
                    },
                    success: function(response) {
                        $('.sxs-company-loading').remove();
                        $('.sxs-company-preview').remove();
                        
                        if (response.success) {
                            $(response.data.html).insertAfter('#sxs_selected_company');
                        }
                    }
                });
            } else {
                // Remove preview if no company is selected
                $('.sxs-company-loading, .sxs-company-preview').remove();
            }
        });
    }

    /**
     * Initialize shortcode copy functionality
     */
    function initShortcodeActions() {
        $('.sxs-copy-shortcode').on('click', function() {
            var shortcode = $(this).data('clipboard-text');
            var tempInput = $('<input>');
            $('body').append(tempInput);
            tempInput.val(shortcode).select();
            document.execCommand('copy');
            tempInput.remove();
            
            var $button = $(this);
            var originalText = $button.text();
            $button.text('Copied!');
            
            setTimeout(function() {
                $button.text(originalText);
            }, 2000);
        });
    }

    /**
     * Initialize button toggle functionality for position brief and scorecard
     */
    function initButtonsToggle() {
        // Position Brief toggle
        $('input[name="sxs_position_brief_enabled"]').on('change', function() {
            $('.sxs-position-brief-url').toggle(this.checked);
            toggleButtonsMessage();
        });

        // Scorecard toggle
        $('input[name="sxs_scorecard_enabled"]').on('change', function() {
            $('.sxs-scorecard-url').toggle(this.checked);
            toggleButtonsMessage();
        });

        function toggleButtonsMessage() {
            var briefEnabled = $('input[name="sxs_position_brief_enabled"]').is(':checked');
            var scorecardEnabled = $('input[name="sxs_scorecard_enabled"]').is(':checked');
            $('.sxs-buttons-message').toggle(briefEnabled || scorecardEnabled);
        }
    }

})(jQuery); 