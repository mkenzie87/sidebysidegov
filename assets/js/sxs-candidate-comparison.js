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
            // For all viewport widths, not just mobile
            var $container = $('.sxs-comparison-container');
            var $body = $('.sxs-comparison-body');
            var $rows = $body.find('.sxs-row');
            
            // Check if we need horizontal scrolling
            if ($body.width() > $container.width()) {
                // Make sure scroll indicator is visible
                $('.scroll-indicator').show();
                
                // Ensure sticky columns work correctly
                $rows.find('.sticky-col, .sxs-col-header').each(function() {
                    $(this).css({
                        'position': 'sticky',
                        'left': '0',
                        'z-index': '10'
                    });
                });
                
                // Make sure "SIDE BY SIDE" header has higher z-index
                $('.sxs-comparison-header .sxs-col-header').css('z-index', '11');
                
                // Enable smoother scrolling with mouse wheel
                $container.on('wheel', function(e) {
                    if (e.originalEvent.deltaY === 0) {
                        e.preventDefault();
                        $(this).scrollLeft($(this).scrollLeft() + e.originalEvent.deltaX);
                    }
                });
                
                // Hide indicator after scrolling
                $container.on('scroll', function() {
                    $('.scroll-indicator').fadeOut();
                });
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