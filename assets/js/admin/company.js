/**
 * SxS Company Admin JavaScript
 */
(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        initButtonsToggle();
    });

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