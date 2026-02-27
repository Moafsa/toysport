/**
 * Main JavaScript - Toy Sport Theme
 */

(function ($) {
    'use strict';

    $(document).ready(function () {

        // Mobile Menu Toggle
        $('.mobile-menu-toggle').on('click', function () {
            $(this).attr('aria-expanded', function (i, attr) {
                return attr === 'true' ? 'false' : 'true';
            });
            $('.main-navigation').toggleClass('active');
        });

        // Close mobile menu on window resize
        $(window).on('resize', function () {
            if ($(window).width() > 768) {
                $('.main-navigation').removeClass('active');
                $('.mobile-menu-toggle').attr('aria-expanded', 'false');
            }
        });

        // Smooth Scroll
        $('a[href^="#"]').on('click', function (e) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 100
                }, 1000);
            }
        });

        // Add to Cart AJAX (WooCommerce)
        $('.add-to-cart').on('click', function (e) {
            e.preventDefault();
            var $button = $(this);
            var productId = $button.data('product-id');

            $button.addClass('loading').text('Adicionando...');

            $.ajax({
                type: 'POST',
                url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
                data: {
                    product_id: productId
                },
                success: function (response) {
                    if (response.error && response.product_url) {
                        window.location = response.product_url;
                        return;
                    }

                    // Update cart count
                    if (response.fragments) {
                        $.each(response.fragments, function (key, value) {
                            $(key).replaceWith(value);
                        });
                    }

                    // Show notification
                    showNotification('Produto adicionado ao carrinho!', 'success');

                    // Trigger event
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);
                },
                error: function () {
                    showNotification('Erro ao adicionar produto ao carrinho.', 'error');
                },
                complete: function () {
                    $button.removeClass('loading');
                }
            });
        });

        // Search Enhancement (Disabled per user request to rely on native search)
        /*
        var searchTimeout;
        $('.header-search input').on('input', function() {
            clearTimeout(searchTimeout);
            var $input = $(this);
            var query = $input.val();
            
            if (query.length > 2) {
                $.ajax({
                    url: toysportAjax.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'toysport_ajax_search',
                        nonce: toysportAjax.nonce,
                        query: query
                    },
                    beforeSend: function() {
                        $input.parent().addClass('loading');
                    },
                    success: function(response) {
                        $input.parent().removeClass('loading');
                        var $resultsContainer = $('.toysport-search-dropdown');
                        if (!$resultsContainer.length) {
                            $resultsContainer = $('<div class="toysport-search-dropdown"></div>');
                            $input.parent().append($resultsContainer);
                        }
                        
                        if (response.success) {
                            $resultsContainer.html(response.data).show();
                        } else {
                            $resultsContainer.html('<div class="no-results">Nenhum produto encontrado.</div>').show();
                        }
                    }
                });
            } else {
                $('.search-results').hide();
            }
        });
        */

        // Close search results on click outside
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.header-search').length) {
                $('.toysport-search-dropdown').hide();
            }
        });

        // Lazy Load Images
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                            img.classList.add('loaded');
                            observer.unobserve(img);
                        }
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }

        // Sticky Header
        var lastScroll = 0;
        var header = $('.site-header');

        $(window).on('scroll', function () {
            var currentScroll = $(this).scrollTop();

            if (currentScroll > 100) {
                header.addClass('scrolled');
            } else {
                header.removeClass('scrolled');
            }

            lastScroll = currentScroll;
        });

        // WhatsApp Button Animation
        $('.whatsapp-float').on('mouseenter', function () {
            $(this).addClass('pulse');
        }).on('mouseleave', function () {
            $(this).removeClass('pulse');
        });

        // Product Image Zoom (if needed)
        $('.product-image img').on('mouseenter', function () {
            $(this).css('transform', 'scale(1.1)');
        }).on('mouseleave', function () {
            $(this).css('transform', 'scale(1)');
        });

    });

    /**
     * Show notification
     */
    function showNotification(message, type) {
        type = type || 'info';
        var notification = $('<div class="notification notification-' + type + '">' + message + '</div>');

        $('body').append(notification);

        setTimeout(function () {
            notification.addClass('show');
        }, 100);

        setTimeout(function () {
            notification.removeClass('show');
            setTimeout(function () {
                notification.remove();
            }, 300);
        }, 3000);
    }

    /**
     * Update Cart Count
     */
    function updateCartCount() {
        $.ajax({
            url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments'),
            success: function (response) {
                if (response && response.fragments) {
                    $.each(response.fragments, function (key, value) {
                        $(key).replaceWith(value);
                    });
                }
            }
        });
    }

    // Update cart on page load
    if (typeof wc_add_to_cart_params !== 'undefined') {
        updateCartCount();
    }

})(jQuery);
