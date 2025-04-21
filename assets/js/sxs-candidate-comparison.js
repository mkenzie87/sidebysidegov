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
            
            // Initialize scroll position tracker
            this.lastScrollTop = 0;
            this.scrollTimeout = null;
        },

        bindEvents: function() {
            $(window).on('resize', this.setupResponsiveColumns);
            
            // Add throttled scroll event to check indicator visibility
            var self = this;
            $(window).on('scroll', function() {
                if (!self.scrollTimeout) {
                    self.scrollTimeout = setTimeout(function() {
                        self.checkScrollIndicator();
                        self.scrollTimeout = null;
                    }, 100); // throttle to once every 100ms
                }
            });
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
            
            // Function to check if element is in viewport
            function isElementInViewport($el) {
                if (!$el.length) return false;
                
                var rect = $el[0].getBoundingClientRect();
                return (
                    rect.top >= 0 &&
                    rect.left >= 0 &&
                    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                );
            }
            
            // Check if comparison container is in viewport and if horizontal scrolling is needed
            if ($container.length && $firstRow.length && $firstRow.width() > $container.width()) {
                var isContainerVisible = isElementInViewport($container) || 
                                       // Also check if we're scrolled within the container
                                       $container.offset().top < window.pageYOffset + window.innerHeight &&
                                       $container.offset().top + $container.height() > window.pageYOffset;
                
                if (isContainerVisible) {
                    // Add scroll indicator if needed
                    if ($('.sxs-scroll-indicator').length === 0) {
                        $('<div class="sxs-scroll-indicator">Scroll to see more candidates →</div>')
                            .insertBefore($container)
                            .fadeIn();
                    } else {
                        $('.sxs-scroll-indicator').fadeIn();
                    }
                } else {
                    // Hide indicator when container not in viewport
                    $('.sxs-scroll-indicator').fadeOut();
                }
            } else {
                // Hide indicator if not needed
                $('.sxs-scroll-indicator').fadeOut();
            }
            
            // Set up horizontal scroll detection
            $container.on('scroll.indicator', function() {
                // Hide indicator as they start scrolling horizontally
                if ($(this).scrollLeft() > 0) {
                    $('.sxs-scroll-indicator').fadeOut();
                } else {
                    // Show it again when scrolled back to start
                    $('.sxs-scroll-indicator').fadeIn();
                }
            });
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