/**
 * SxS Comparison Table JavaScript
 */
(function($) {
    'use strict';

    // Initialize the comparison table functionality when the DOM is ready
    $(document).ready(function() {
        initComparisonTable();
    });

    function initComparisonTable() {
        // Set up horizontal scroll navigation
        setupScrollControls();
    }

    function setupScrollControls() {
        const scrollContainer = $('.sxs-comparison-container');
        const leftButton = $('.sxs-scroll-left');
        const rightButton = $('.sxs-scroll-right');
        
        if (scrollContainer.length === 0) {
            return;
        }

        // Scroll amount - approximately 75% of container width
        const scrollAmount = scrollContainer.width() * 0.75;

        // Add click handlers to the buttons
        leftButton.on('click', function() {
            scrollContainer.animate({
                scrollLeft: '-=' + scrollAmount
            }, 300);
        });

        rightButton.on('click', function() {
            scrollContainer.animate({
                scrollLeft: '+=' + scrollAmount
            }, 300);
        });

        // Show/hide buttons based on scroll position
        function updateScrollButtons() {
            const maxScrollLeft = scrollContainer[0].scrollWidth - scrollContainer[0].clientWidth;
            
            // If at the beginning, hide left button
            if (scrollContainer.scrollLeft() <= 0) {
                leftButton.fadeOut(200);
            } else {
                leftButton.fadeIn(200);
            }
            
            // If at the end, hide right button
            if (scrollContainer.scrollLeft() >= maxScrollLeft - 10) { // 10px buffer
                rightButton.fadeOut(200);
            } else {
                rightButton.fadeIn(200);
            }
        }

        // Initial check
        updateScrollButtons();
        
        // Update on scroll
        scrollContainer.on('scroll', function() {
            updateScrollButtons();
        });
        
        // Update on window resize
        $(window).on('resize', function() {
            updateScrollButtons();
        });

        // Add keyboard navigation support
        $(document).on('keydown', function(e) {
            // Only if focus is within the comparison container
            if (!scrollContainer.is(':focus-within')) {
                return;
            }

            // Left arrow
            if (e.keyCode === 37) {
                scrollContainer.animate({
                    scrollLeft: '-=' + scrollAmount
                }, 300);
                e.preventDefault();
            }
            
            // Right arrow
            if (e.keyCode === 39) {
                scrollContainer.animate({
                    scrollLeft: '+=' + scrollAmount
                }, 300);
                e.preventDefault();
            }
        });
    }

})(jQuery); 