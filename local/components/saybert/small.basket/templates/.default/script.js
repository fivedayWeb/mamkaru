var updateBasket;
$(function() {
    updateBasket = function (count) {
        $('#cart-count').attr('data-count', count);
        $('#cart-count').html(count);
    };
});
