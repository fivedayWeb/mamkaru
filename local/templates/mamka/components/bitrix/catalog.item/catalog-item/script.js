$(function () {
    if (window.JSCatalogItem) return;

    window.JSCatalogItem = function (params) {
        this.uid = params.uid;
        this.offers = params.offers;
        this.url = params.url;
        this.no_photo = params.no_photo;
        this.current = 0;

        $(window.document).on('DOMContentLoaded', $.proxy(this.onDOMContentLoad, this))
    };

    window.JSCatalogItem.prototype.onDOMContentLoad = function () {
        this.initElements();
        this.initEvents();
    };

    window.JSCatalogItem.prototype.initElements = function () {
        var container = $('#' + this.uid + '-catalog-item');
        this.elements = {
            container: container,
            carousel: $('#' + this.uid + '-offers-horizontal-carousel'),
            image: container.find('[data-item-image]'),
            available: container.find('[data-item-is-available]'),
            price: container.find('[data-item-card-price]'),
            input: container.find('[data-item-input-id]'),
            addToBasketButton: container.find('[data-item-button-basket]'),
            href: container.find('[data-item-href]'),
            addToOrderButton: container.find('[data-add-to-order-button]'),
        };
        $(this.elements.carousel).removeClass('hidden');
    };

    window.JSCatalogItem.prototype.initEvents = function () {
        if (this.elements.carousel.length) {
            if ($(this.elements.carousel).find('[data-carousel-item]').length < 4) {
                $(this.elements.carousel).owlCarousel({
                    loop: true,
                    items: 3,
                    autoplay: false,
                    dots: false,
                    onInitialized: $.proxy(this.carouselInit, this),
                });
            } else {
                $(this.elements.carousel).owlCarousel({
                    nav: true,
                    loop: true,
                    items: 3,
                    autoplay: false,
                    lazyLoad: true,
                    dots: false,
                    navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
                    onInitialized: $.proxy(this.carouselInit, this),
                });
            }

        }
    };

    window.JSCatalogItem.prototype.carouselInit = function () {
        $(this.elements.carousel).find('[data-carousel-item]').on('click', $.proxy(this.onCarouselItemClick, this));
    };

    window.JSCatalogItem.prototype.onCarouselItemClick = function (event) {
        if (this.current !== $(event.target).attr('data-carousel-offer-id')) {
            this.current = $(event.target).attr('data-carousel-offer-id');

            $(this.elements.carousel).find('[data-carousel-item]').removeClass('active');
            $(event.target).parent().addClass('active');

            this.redrawProduct();
        }
    };

    window.JSCatalogItem.prototype.redrawProduct = function () {
        if (this.offers[this.current].PREVIEW_PICTURE.SRC) {
            $(this.elements.image).attr("src", this.offers[this.current].PREVIEW_PICTURE.SRC);
        } else {
            $(this.elements.image).attr("src", this.no_photo);
        }

        console.log(this.offers[this.current]);

        var price = this.offers[this.current].PRICES.BASE2.PRINT_DISCOUNT_VALUE;

        if (this.offers[this.current].CATALOG_AVAILABLE === 'Y') {
            $(this.elements.addToBasketButton).removeClass('hidden');
            $(this.elements.addToOrderButton).addClass('hidden');

            $(this.elements.available).html('Товар на складе: <span class="green">В наличии</span>');
			$(this.elements.price).html('').append(
				$('<div>').addClass('price _price').text(price)
			);
        } else {
            $(this.elements.addToBasketButton).addClass('hidden');
            $(this.elements.addToOrderButton).removeClass('hidden');

            $(this.elements.available).html('Товар на складе: <span class="red">Под заказ</span>');
			$(this.elements.price).html('').append(
				$('<div>').addClass('price noprice').text('Цену уточняйте у менеджера')
			);
        }

		/*if (this.offers[this.current].PRODUCT.QUANTITY === '0') {
			$(this.elements.addToBasketButton).addClass('hidden');
            $(this.elements.addToOrderButton).removeClass('hidden');

            $(this.elements.available).html('Товар на складе: <span class="red">Под заказ</span>');
			$(this.elements.price).html('').append(
				$('<div>').addClass('price noprice').text('Цену уточняйте у менеджера')
			);
        } else {
			$(this.elements.addToBasketButton).removeClass('hidden');
            $(this.elements.addToOrderButton).addClass('hidden');

            $(this.elements.available).html('Товар на складе: <span class="green">В наличии</span>');
			$(this.elements.price).html('').append(
				$('<div>').addClass('price _price').text(price)
			);

        }*/

        /*$(this.elements.price).html('').append(
            $('<div>').addClass('price _price').text(price)
        );*/

        $(this.elements.href).attr('href', this.url + '?pid=' + this.current);

        $(this.elements.input).val(this.current);

        $(this.elements.addToOrderButton).attr('data-offer', this.current);
    };
});