jQuery(document).ready(function($) {
    'use strict';

    // Initialize the comparison functionality
    var SXSComparison = {
        init: function() {
            this.bindEvents();
            this.initializeTooltips();
            this.setupPrintHandler();
            this.setupResponsiveColumns();
            this.setupSmoothScrolling();
            this.initializeRecruiterSlider();
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
            this.checkScrollIndicator();
            
            // Add resize listener to update scroll indicator dynamically
            $(window).on('resize', $.proxy(this.checkScrollIndicator, this));
            
            // Enable smoother scrolling with mouse wheel
            $('.sxs-comparison-container').on('wheel', function(e) {
                if (e.originalEvent.deltaY === 0) {
                    e.preventDefault();
                    $(this).scrollLeft($(this).scrollLeft() + e.originalEvent.deltaX);
                }
            });
        },
        
        // Check if scroll indicator should be shown or hidden
        checkScrollIndicator: function() {
            var $container = $('.sxs-comparison-container');
            var $firstRow = $container.find('.sxs-row:first');
            
            // Remove existing handler to prevent multiple bindings
            $container.off('scroll.indicator');
            
            // Check if horizontal scrolling is needed (on any device)
            if ($firstRow.length && $firstRow.width() > $container.width()) {
                // Add scroll indicator if needed
                if ($('.sxs-scroll-indicator').length === 0) {
                    $('<div class="sxs-scroll-indicator">Scroll to see more candidates →</div>')
                        .insertBefore($container)
                        .fadeIn();
                } else {
                    $('.sxs-scroll-indicator').fadeIn();
                }
                
                // Keep indicator visible at all times when horizontal scrolling is needed
            } else {
                // Hide indicator if not needed
                $('.sxs-scroll-indicator').fadeOut();
            }
        },

        // Initialize recruiter slider
        initializeRecruiterSlider: function() {
            // Check if we have multiple recruiters
            if ($('.sxs-recruiter-slider').length) {
                // If slick is available, initialize it
                if ($.fn.slick) {
                    $('.sxs-recruiter-slider').slick({
                        arrows: false,
                        dots: false,
                        infinite: true,
                        speed: 500,
                        fade: true,
                        cssEase: 'linear',
                        adaptiveHeight: true
                    });
                    
                    // Setup custom prev/next buttons
                    $('.sxs-prev-slide').on('click', function() {
                        $('.sxs-recruiter-slider').slick('slickPrev');
                    });
                    
                    $('.sxs-next-slide').on('click', function() {
                        $('.sxs-recruiter-slider').slick('slickNext');
                    });
                } else {
                    // Simple slider if slick is not available
                    var $slides = $('.sxs-recruiter-slide');
                    var currentSlide = 0;
                    var slideCount = $slides.length;
                    
                    // Hide all slides except the first one
                    $slides.hide().eq(0).show();
                    
                    // Setup navigation
                    $('.sxs-prev-slide').on('click', function() {
                        $slides.eq(currentSlide).hide();
                        currentSlide = (currentSlide - 1 + slideCount) % slideCount;
                        $slides.eq(currentSlide).fadeIn();
                    });
                    
                    $('.sxs-next-slide').on('click', function() {
                        $slides.eq(currentSlide).hide();
                        currentSlide = (currentSlide + 1) % slideCount;
                        $slides.eq(currentSlide).fadeIn();
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
    SXSComparison.init();

    // Export for use in other scripts if needed
    window.SXSComparison = SXSComparison;
}); 