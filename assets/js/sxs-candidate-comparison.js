jQuery(document).ready(function($) {
    'use strict';

    // Initialize the comparison functionality
    var SxSComparison = {
        init: function() {
            this.bindEvents();
            this.initializeTooltips();
            this.setupPrintHandler();
            this.setupResponsiveColumns();
            this.setupSmoothScrolling();
        },

        bindEvents: function() {
            $(window).on('resize', this.setupResponsiveColumns);
        },

        initializeTooltips: function() {
            $('.sxs-tooltip').each(function() {
                $(this).tooltip({
                    position: {
                        my: 'center bottom-20',
                        at: 'center top',
                        using: function(position, feedback) {
                            $(this).css(position);
                            $('<div>')
                                .addClass('arrow')
                                .addClass(feedback.vertical)
                                .addClass(feedback.horizontal)
                                .appendTo(this);
                        }
                    }
                });
            });
        },

        setupPrintHandler: function() {
            $('.sxs-print-button').on('click', function(e) {
                e.preventDefault();
                window.print();
            });
        },

        setupResponsiveColumns: function() {
            var $wrapper = $('.sxs-comparison-wrapper');
            var $rows = $wrapper.find('.sxs-row');
            
            // Reset heights
            $rows.find('.sxs-col').css('height', '');
            
            // Only proceed if we're not in mobile view
            if ($(window).width() > 768) {
                $rows.each(function() {
                    var maxHeight = 0;
                    var $cols = $(this).find('.sxs-col');
                    
                    // Find the maximum height
                    $cols.each(function() {
                        var height = $(this).outerHeight();
                        maxHeight = height > maxHeight ? height : maxHeight;
                    });
                    
                    // Set all columns to the maximum height
                    $cols.css('height', maxHeight + 'px');
                });
            }
        },

        // Add smooth scrolling for horizontal scroll
        setupSmoothScrolling: function() {
            // Only apply this on desktop
            if ($(window).width() > 768) {
                // Check if we have more candidates than can fit in viewport
                var $container = $('.sxs-comparison-container');
                var $firstRow = $container.find('.sxs-row:first');
                
                if ($firstRow.width() > $container.width()) {
                    // Add scroll indicator if needed
                    if ($('.sxs-scroll-indicator').length === 0) {
                        $('<div class="sxs-scroll-indicator">Scroll to see more candidates →</div>')
                            .insertBefore($container)
                            .fadeIn();
                        
                        // Hide indicator after scrolling
                        $container.on('scroll', function() {
                            $('.sxs-scroll-indicator').fadeOut();
                        });
                    }
                    
                    // Enable smoother scrolling with mouse wheel
                    $container.on('wheel', function(e) {
                        if (e.originalEvent.deltaY === 0) {
                            e.preventDefault();
                            $(this).scrollLeft($(this).scrollLeft() + e.originalEvent.deltaX);
                        }
                    });
                }
            }
        },

        // Helper function to format currency
        formatCurrency: function(amount) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        },

        // Helper function to format years of experience
        formatExperience: function(years) {
            return years + (years === 1 ? ' year' : ' years');
        }
    };

    // Initialize the comparison functionality
    SxSComparison.init();

    // Export for use in other scripts if needed
    window.SxSComparison = SxSComparison;
}); 