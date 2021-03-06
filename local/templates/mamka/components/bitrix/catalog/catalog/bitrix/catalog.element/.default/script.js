$(function () {
    window.JCCatalogElement = function (arParams) {
        this.arParams = arParams;

        if ("OFFERS" in arParams)
            this.offers = arParams.OFFERS;
        else
            this.offers = null;
        this.SKU = arParams.SKU;
        this.currentOffer = arParams.OFFER_SELECTED;
        this.ID = arParams.ID;

        this.checkInFavorite();
    };

    window.JCCatalogElement.prototype.changeSku = function (target) {
        this.setCurrentOffer(target);
        this.updateVisualPart();
    };

    //Устанавливает текущее предложение
    window.JCCatalogElement.prototype.setCurrentOffer = function (target) {
        var currentOfferId = target.data('offer');
        for (var key in this.offers) {
            if (this.offers[key]['offerID'] == currentOfferId) {
                this.currentOffer = this.offers[key];
                break;
            }
        }
        return this;
    }

    //Обновляет визуальную часть
    window.JCCatalogElement.prototype.updateVisualPart = function () {
        var activePhotoOfferID = $('.bx-active-photo').attr('data-offer') * 1;
        var imgOffer = $('.offers_select .offers_option.selected img');
        var imgProduct = $('#small-product-images img[data-offer=' + this.currentOffer.offerID + ']:first');
        var imgDefault = $("#small-product-images img[data-type='product']:first");

        var imgResult = false;
        if (imgOffer.length > 0) {
            imgResult = imgOffer;
        } else if (imgProduct.length > 0) {
            imgResult = imgProduct;
        } else if (imgDefault.length > 0) {
            imgResult = imgDefault
        }

        if (imgResult) {
            $('#big-product-image img').attr('src', imgResult.attr('data-preview'));
            $('#big-product-image img').attr('data-src', imgResult.attr('data-preview'));
            $('#big-product-image img').attr('data-detail', imgResult.attr('data-detail'));
        }

        $('#big-product-image img').attr('data-offer', this.currentOffer.offerID);

        $('#small-product-images .small-product-image img[data-offer]').parent().hide();

        $('.bx-top-prop-block').hide();
        $('.bx-top-prop-block[data-offer=' + this.currentOffer['offerID'] + ']').show();
        $('.bx-bottom-prop-block').hide();
        $('.bx-bottom-prop-block[data-offer=' + this.currentOffer['offerID'] + ']').show();
        $('.bx-prices-block').hide();
        $('.bx-prices-block[data-offer=' + this.currentOffer['offerID'] + ']').show();
        $('.product-in-store').hide();
        $('.product-in-store[data-offer=' + this.currentOffer['offerID'] + ']').show();
        $('.bx-product').hide();
        $('.bx-product[data-offer=' + this.currentOffer['offerID'] + ']').show();
    };
    window.JCCatalogElement.prototype.checkInFavorite = function () {
        $.ajax({
            url: '/ajax/favorite.php',
            data: {
                sessid: BX.bitrix_sessid(),
                action: 'check',
                PRODUCT_ID: this.ID,
            },
            dataType: 'json',
            success: function (res) {
                if (typeof (res.error) !== "undefined") {
                    console.error(res.error);
                } else {
                    if (res.in_favorite) {
                        $('.bx-like-button').addClass('active');
                    }
                }
            },
            error: function (a) {
                console.error(a.status + ' ' + a.statusText);
            }
        });
    };

    addToBasket = function (picture, price, name, quantity) {
        $('#added-block img').attr('src', picture);
        $('#added-block .add-cart-block-title').html(name);
        $('#added-block .add-cart-block-price').html('+' + price);

        var currentBasket = $('#cart-count').attr('data-count') * 1;
        currentBasket += quantity;
        updateBasket(currentBasket);
        $('#added-block').parent().fadeIn();

        setTimeout(function () {
            $('#added-block').parent().fadeOut();
        }, 1000);
    };

    $("#small-product-images .small-product-image img").click(function () {
        var thisOfferID = $(this).data('offer');
        if (typeof (thisOfferID) == 'number')
            $('.bx-active-photo').attr('data-offer', thisOfferID);
        else
            $('.bx-active-photo').removeAttr('data-offer');
    });

    $('.bx-product-add-to-cart').on('click', function () {
        var block = $(this).closest('.bx-product');
        var disabled = $(this).is(':disabled');
        var offer = $(this).data('offer');
        var count = block.find('.product-count input').val() * 1;
        count = (typeof (count) == 'number' && count > 0) ? count : 1;
        if (disabled)
            return false;
        var ajaxData = {
            'action': 'BUY',
            'ajax_basket': "Y",
            'id': offer,
            'quantity': count,
        };
        $.ajax({
            'data': ajaxData,
            success: function (result) {
                var picture = $('#product-gallery img.bx-active-photo').attr('src'),
                    name = $('#product-header').html(),
                    price = $('.bx-prices-block[data-offer=' + offer + ']').html(),
                    quantity = count;
                if (!(result.STATUS == 'ERROR')) {
                    addToBasket(picture, price, name, quantity);
                } else {
                    $('#error-block .error-block-text').html(result.MESSAGE);

                    $('#error-block').parent().fadeIn();

                    setTimeout(function () {
                        $('#error-block').parent().fadeOut();
                    }, 3000);
                }
            }
        });
        return false;
    });


    var countReview = $('.bx-reviews div[data-count-reviews]').attr('data-count-reviews');
    if (countReview > 0)
        $('.bx-reviews-tabs').append(' (' + countReview + ')');


    var viewedBlock = $('.bx-catalog-viewed-products'),
        recommendBlock = $('.bx-sale-recommended-products'),
        countViewedBlock = $('.card', viewedBlock).length,
        countRecommendBlock = $('.card', recommendBlock).length;

    if (countRecommendBlock + countViewedBlock > 0) {
        if (countViewedBlock > 0) {
            viewedBlock.addClass('active');
            $('.bx-viewed-button').show();
            $('.bx-viewed-button').addClass('active');
        }

        if (countRecommendBlock > 0) {
            recommendBlock.addClass('active');
            viewedBlock.removeClass('active');
            $('.bx-recommended-button').show();
            $('.bx-viewed-button').removeClass('active');
            $('.bx-recommended-button').addClass('active');
        }


        $('.recomendet-block').show();
    }

    $('.bx-like-button').on('click', function () {
        var btn = $(this);
        var data = {
            sessid: BX.bitrix_sessid(),
            PRODUCT_ID: $(this).data('product'),
        };
        if (btn.hasClass('active')) {
            data.action = 'delete';
        } else {
            data.action = 'add';
        }
        $.ajax({
            url: '/ajax/favorite.php',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (typeof (res.error) !== "undefined") {
                    console.error(res.error);
                } else {
                    if (data.action == 'delete') {
                        btn.removeClass('active');
                    } else {
                        btn.addClass('active');
                    }
                }
            },
            error: function (a) {
                console.error(a.status + ' ' + a.statusText);
            }
        });
        return false;
    });

    window.modal = {
        selector: '._modal_window',
        isInitSlider: false,
        getSlider: function () {
            return $(this.selector + ' .modal-images-container');
        },
        open: function (number) {
            if (typeof (number) == "undefined") number = 1;

            $(this.selector).fadeIn();
            modal.initSlider(number);
        },
        close: function () {
            $(this.selector).fadeOut(function () {
                modal.destroySlider();
            });
        },
        loadImages: function () {
            this.getSlider().find('.owl-lazy').each(function (i, item) {
                var img = $(this);
                img.attr('src', img.attr('data-src'));
            });
        },
        initSlider: function (number) {
            if (this.isInitSlider) return;
            if (typeof (number) == "undefined") number = 1;

            if (this.getSlider().find('.owl-lazy').length > 1) {
                this.getSlider().owlCarousel({
                    nav: true,
                    loop: true,
                    items: 1,
                    autoplay: false,
                    navText: ["", ""],
                    lazyLoad: true,
                    dots: false,
                    startPosition: number - 1,
                });
            } else {
                this.loadImages();
            }
            this.isInitSlider = true;
        },
        destroySlider: function () {
            var slider = this.getSlider();
            slider.trigger('destroy.owl.carousel');
            slider.html(slider.find('.owl-stage-outer').html()).removeClass('owl-loaded');
            this.isInitSlider = false;
        }
    };
    $('#big-product-image img').on('click', function () {
        var slideNumber = 1;
        var offerID = $(this).attr('data-offer');
        if (offerID) {
            slideNumber = parseInt($('.small-product-image img[data-offer=' + offerID + ']').attr('data-index')) + 1;
        }
        modal.open(slideNumber);
        return false;
    });
    $('._modal_close').on('click', function () {
        modal.close();
        return false;
    });
    $('._modal_bg').on('click', function (e) {
        if (!$(e.target).hasClass('_modal_bg')) return true;
        modal.close();
        return false;
    });

    if ($('#photos-vertical').length) {
        function onResize() {
            $('#photos-vertical').css('max-height', $('#big-product-image').outerHeight());
        }

        onResize();
        $(window).on('resize', onResize);

        $("#photos-vertical").on('click', '.small-product-image', function () {
            var imgIndex = $(this).find('img').attr('data-index');
            modal.open(+imgIndex + 1);
            return false;
        });

        if ($(window).width() <= 767) {
            $('#photos-vertical .lazyload').each(function (i, item) {
                $(item).attr('src', $(item).attr('data-src'));
            });

            $('#photos-vertical').owlCarousel({
                nav: true,
                loop: true,
                items: 4,
                autoplay: false,
                navText: ["", ""],
                lazyLoad: true,
            });
        }
    }

    var offers_select = {
        $container: $('.offers_select'),
        init: function () {
            var select = this;

            $($.proxy(function () {
                var paramsUrl = window.location.search,
                    pidRegExp = new RegExp(/[?&]pid=(\d+)/),
                    pid = pidRegExp.exec(paramsUrl);

                if (pid && pid[1]) {
                    var $option = $('.offers_option[data-offer=' + pid[1] + ']');
                    if (select.isOpened()) {
                        select.select($option);
                        // select.close();
                    } else {
                        select.open();
                    }
                }
            }, this));

            this.$container.find('.offers_select_title').on('click', function () {
                if (select.isOpened()) {
                    select.close();
                } else {
                    select.open();
                }
                return false;
            });

            this.$container.find('.offers_option').on('click', function () {
                var $option = $(this);
                if (select.isOpened()) {
                    select.select($option);
                    // select.close();
                } else {
                    select.open();
                }
                return false;
            });

            return this;
        },
        isOpened: function () {
            return this.$container.hasClass('opened');
        },
        open: function () {
            // this.loadImages();
            this.$container.addClass('opened');
            this.$container.find('.offers_option').slideDown();
        },
        close: function () {
            this.$container.removeClass('opened');
            this.$container.find('.offers_option:not(.selected)').slideUp();
        },
        select: function ($option) {
            this.$container.find('.offers_option.selected').removeClass('selected');
            $option.addClass('selected');
            jsElement.changeSku($option);
        },
        imagesLoaded: false,
        loadImages: function () {
            if (this.imagesLoaded) {
                return;
            }
            this.$container.find('img').each(function (i, item) {
                $(item).attr('src', $(item).attr('data-src'));
            });
            this.imagesLoaded = true;
        }
    }.init();

});