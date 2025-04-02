/**
 * SxS Candidate Comparison Admin JavaScript
 */
(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        // Initialize tooltips
        initTooltips();
        
        // Initialize candidate form
        if ($('body').hasClass('post-type-sxs_candidate')) {
            initCandidateForm();
        }
        
        // Initialize comparison form
        if ($('body').hasClass('post-type-sxs_comparison')) {
            initComparisonForm();
        }
    });

    /**
     * Initialize tooltips
     */
    function initTooltips() {
        $('.sxs-tooltip').hover(
            function() {
                var tooltip = $(this).find('.sxs-tooltip-text');
                tooltip.fadeIn(200);
            },
            function() {
                var tooltip = $(this).find('.sxs-tooltip-text');
                tooltip.fadeOut(200);
            }
        );
    }

    /**
     * Initialize the candidate form
     */
    function initCandidateForm() {
        // Add field buttons for education
        $('.add-education-field').on('click', function() {
            var field = '<div class="education-field">' +
                       '<input type="text" name="sxs_education[]" value="" class="widefat" placeholder="' + 
                       'e.g., MBA from Harvard University, CPA certification' + '">' +
                       '<button type="button" class="button remove-field">' + sxsAdmin.i18n.remove + '</button>' +
                       '</div>';
            $('#sxs_education_fields').append(field);
        });

        // Add field buttons for experience
        $('.add-experience-field').on('click', function() {
            var field = '<div class="experience-field">' +
                       '<textarea name="sxs_relevant_experience[]" class="widefat" rows="2" placeholder="' + 
                       'e.g., Led a team of 10 developers to deliver a major project under budget' + '"></textarea>' +
                       '<button type="button" class="button remove-field">' + sxsAdmin.i18n.remove + '</button>' +
                       '</div>';
            $('#sxs_relevant_experience_fields').append(field);
        });

        // Remove field buttons
        $(document).on('click', '.remove-field', function() {
            $(this).parent().fadeOut(300, function() {
                $(this).remove();
            });
        });

        // Form validation
        $('#post').on('submit', function(e) {
            var isValid = validateCandidateForm();
            if (!isValid) {
                e.preventDefault();
                showErrorNotice(__('Please fill in all required fields', 'sxs-candidate-comparison'));
                return false;
            }
        });
    }

    /**
     * Initialize the comparison form
     */
    function initComparisonForm() {
        // Make selected candidates sortable
        $('#sxs-selected-candidates').sortable({
            items: 'option',
            containment: 'parent',
            update: function(event, ui) {
                // Add "dragged" class to show the item was moved
                $(ui.item).addClass('dragged');
                setTimeout(function() {
                    $(ui.item).removeClass('dragged');
                }, 1000);
            }
        });

        // Search functionality for available candidates
        $('#sxs-candidate-search').on('keyup', function() {
            var searchText = $(this).val().toLowerCase();
            $('#sxs-available-candidates option').each(function() {
                var candidateText = $(this).text().toLowerCase();
                if (candidateText.indexOf(searchText) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Double-click to add or remove candidates
        $('#sxs-available-candidates').on('dblclick', 'option', function() {
            $(this).appendTo('#sxs-selected-candidates');
        });

        $('#sxs-selected-candidates').on('dblclick', 'option', function() {
            $(this).appendTo('#sxs-available-candidates');
        });

        // Add candidate button
        $('#sxs-add-candidate').on('click', function() {
            $('#sxs-available-candidates option:selected').each(function() {
                var option = $(this);
                option.prop('selected', false);
                option.appendTo('#sxs-selected-candidates');
            });
        });

        // Remove candidate button
        $('#sxs-remove-candidate').on('click', function() {
            $('#sxs-selected-candidates option:selected').each(function() {
                var option = $(this);
                option.prop('selected', false);
                option.appendTo('#sxs-available-candidates');
            });
        });

        // Select all candidates before form submit
        $('form#post').on('submit', function() {
            $('#sxs-selected-candidates option').prop('selected', true);
        });

        // Form validation
        $('#post').on('submit', function(e) {
            var isValid = validateComparisonForm();
            if (!isValid) {
                e.preventDefault();
                showErrorNotice(__('Please select at least 2 candidates for comparison', 'sxs-candidate-comparison'));
                return false;
            }
        });
    }

    /**
     * Validate the candidate form
     */
    function validateCandidateForm() {
        var isValid = true;
        
        // Check required fields
        $('.sxs-required').each(function() {
            var input = $(this).closest('p').find('input, textarea');
            if (input.val() === '') {
                input.addClass('invalid');
                isValid = false;
            } else {
                input.removeClass('invalid');
            }
        });
        
        return isValid;
    }

    /**
     * Validate the comparison form
     */
    function validateComparisonForm() {
        var selectedCount = $('#sxs-selected-candidates option').length;
        if (selectedCount < 2) {
            $('#sxs-selected-candidates').addClass('invalid');
            return false;
        } else {
            $('#sxs-selected-candidates').removeClass('invalid');
            return true;
        }
    }

    /**
     * Show error notice
     */
    function showErrorNotice(message) {
        var notice = $('<div class="notice notice-error is-dismissible"><p>' + message + '</p></div>');
        $('#wpbody-content').prepend(notice);
        
        // Auto dismiss after 5 seconds
        setTimeout(function() {
            notice.fadeOut(function() {
                $(this).remove();
            });
        }, 5000);
    }

})(jQuery); 