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
            
            // Create both indicator elements on page load to prevent layout jumps
            this.createScrollIndicators();
            
            // Setup top scrollbar only
            this.setupTopScrollbar();
            
            // Detect Windows to adjust scrollbar visibility
            this.detectWindows();
        },
        
        // Create both indicator elements to prevent layout jumps when showing/hiding
        createScrollIndicators: function() {
            var $container = $('.sxs-comparison-container');
            if ($container.length && $('.sxs-scroll-indicator').length === 0) {
                // Add desktop indicator (initially hidden)
                $('<div class="sxs-scroll-indicator desktop-indicator" style="visibility:hidden;">Scroll to see more candidates →</div>')
                    .insertBefore($container);
                
                // Add mobile indicator (initially hidden)
                $('<div class="sxs-scroll-indicator mobile-indicator" style="visibility:hidden;">Scroll to see more candidates →</div>')
                    .appendTo('body'); // Append to body for fixed positioning
            }
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
            var isMobile = window.innerWidth <= 768;
            var $desktopIndicator = $('.desktop-indicator');
            var $mobileIndicator = $('.mobile-indicator');
            
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
            
            // Create indicators if they don't exist yet
            if ($desktopIndicator.length === 0 || $mobileIndicator.length === 0) {
                this.createScrollIndicators();
                $desktopIndicator = $('.desktop-indicator');
                $mobileIndicator = $('.mobile-indicator');
            }
            
            // Check if comparison container is in viewport and if horizontal scrolling is needed
            if ($container.length && $firstRow.length && $firstRow.width() > $container.width()) {
                var isContainerVisible = isElementInViewport($container) || 
                                       // Also check if we're scrolled within the container
                                       $container.offset().top < window.pageYOffset + window.innerHeight &&
                                       $container.offset().top + $container.height() > window.pageYOffset;
                
                if (isContainerVisible) {
                    // Show the appropriate indicator based on device type
                    if (isMobile) {
                        $mobileIndicator.css('visibility', 'visible');
                        $desktopIndicator.css('visibility', 'hidden');
                    } else {
                        $desktopIndicator.css('visibility', 'visible');
                        $mobileIndicator.css('visibility', 'hidden');
                    }
                } else {
                    // Hide both indicators when container not in viewport
                    $desktopIndicator.css('visibility', 'hidden');
                    $mobileIndicator.css('visibility', 'hidden');
                }
            } else {
                // Hide both indicators if horizontal scrolling is not needed
                $desktopIndicator.css('visibility', 'hidden');
                $mobileIndicator.css('visibility', 'hidden');
            }
            
            // Set up horizontal scroll detection
            $container.on('scroll.indicator', function() {
                // Hide indicators as they start scrolling horizontally
                if ($(this).scrollLeft() > 0) {
                    $desktopIndicator.css('visibility', 'hidden');
                    $mobileIndicator.css('visibility', 'hidden');
                } else {
                    // Show it again when scrolled back to start
                    if (isElementInViewport($container) || 
                        $container.offset().top < window.pageYOffset + window.innerHeight &&
                        $container.offset().top + $container.height() > window.pageYOffset) {
                        
                        if (isMobile) {
                            $mobileIndicator.css('visibility', 'visible');
                        } else {
                            $desktopIndicator.css('visibility', 'visible');
                        }
                    }
                }
            });
        },

        // Setup top scrollbar for easier horizontal scrolling
        setupTopScrollbar: function() {
            var $container = $('.sxs-comparison-container');
            
            if (!$container.length) {
                return;
            }
            
            // Create top scrollbar
            var $topScrollContainer = $('<div class="sxs-top-scroll-container">');
            var $spacer = $('<div>');
            
            // Clone the width of the comparison container to ensure proper scrolling
            $spacer.width($container[0].scrollWidth);
            $spacer.height(1);
            $topScrollContainer.append($spacer);
            
            // Add top scrollbar before the comparison container
            $topScrollContainer.insertBefore($container);
            
            // Sync horizontal scrolling between scrollable elements
            $container.on('scroll', function() {
                $topScrollContainer.scrollLeft($(this).scrollLeft());
            });
            
            $topScrollContainer.on('scroll', function() {
                $container.scrollLeft($(this).scrollLeft());
            });
            
            // Update the spacer width on window resize
            $(window).on('resize', function() {
                // Update the spacer width to match the scrollable width
                $spacer.width($container[0].scrollWidth);
            });
            
            // Force overflow-x scroll for both containers to ensure scrollbars always show
            $container.css('overflow-x', 'scroll');
            $topScrollContainer.css('overflow-x', 'scroll');
            
            // Add a small delay before triggering a scroll event to ensure scrollbars are displayed
            setTimeout(function() {
                // Trigger a small scroll and back to ensure scrollbars render properly
                $container.scrollLeft(1).scrollLeft(0);
                $topScrollContainer.scrollLeft(1).scrollLeft(0);
            }, 100);
        },

        // Detect Windows OS to adjust scrollbar visibility
        detectWindows: function() {
            // Check if user is on Windows
            var isWindows = navigator.platform.indexOf('Win') > -1;
            
            // Add a class to body if user is on Windows
            if (isWindows) {
                $('body').addClass('sxs-windows-os');
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