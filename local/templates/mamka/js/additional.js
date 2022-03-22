var addToBasket = false;
var bx_register_show_modal = false;
$(function(){
    addToBasket = function(picture,price,name,quantity){
        $('#added-block img').attr('src',picture);
        $('#added-block .add-cart-block-title').html(name);
        $('#added-block .add-cart-block-price').html('+'+price);

        var currentBasket  = $('#cart-count').attr('data-count')*1;
        currentBasket += quantity;
        updateBasket(currentBasket);
        $('#added-block').parent().fadeIn();

        setTimeout(function(){$('#added-block').parent().fadeOut();},1000);
    }

    window.onscroll = function() {
        $('#added-block').parent().fadeOut();
    }

    $('#call-you-modal form').on('submit',function(){
        var data = $(this).serialize();
        $.ajax({
            url: '/ajax/calltouser.php',
            data: data,
            dataType: 'json',
            success:function(response){
                if(!response.error)
				{message = "С вами свяжуться в ближайшее время";
					$('#thx .close-modal').click(function () {
					    $('#call-you-modal').fadeOut(300);
					    $('#thx').fadeOut(300).removeClass('active');
					});
				}
                else
				{
				    message = response.message;
				    $('#thx .close-modal').click(function () {
				        $('#thx').fadeOut(300).removeClass('active');
				        $('#call-you-modal').fadeIn(300);
                        $("#modal-backing").fadeToggle();
				    });
				}
                $('#call-you-modal').fadeToggle(300);
                $('#thx div').html(message);
                $('#thx').fadeIn(300).addClass('active');
            }
        });
        return false;
    });

    $('.modal-open.bx-product-add-to-order').on('click',function() {
        $('#order-you-modal form').attr('data-offer', $(this).attr('data-offer'));
    });

    $('#order-you-modal form').on('submit',function(){
        var data = $(this).serialize();
        if(parseInt($(this).attr('data-offer'))) {
            var id = "&id="+$(this).attr('data-offer');
        } else {
            var id = '';
        }
        var sku_props = '';
        if($.trim($('.offers_option.selected .offer_prop').html()).replace(/\s/g, '').length){
            $('.offers_option.selected .offer_prop span').each(function(k,i){
                sku_props += $(i).html();
            })
        }
        $.ajax({
            url: '/ajax/order.php',
            method: 'post',
            data: data + "&product="+ $('#product-header').text()+ sku_props +"&link=" + window.location + id,
            dataType: 'json',
            success:function(response){
                if(!response.error)
                {
                    message = "С вами свяжуться в ближайшее время";
                    $('#thx .close-modal').click(function () {
                        $('#order-you-modal').fadeOut(300);
                        $('#thx').fadeOut(300).removeClass('active');
                    });
                }
                else
                {
                    message = response.message;
                    $('#thx .close-modal').click(function () {
                        $('#thx').fadeOut(300).removeClass('active');
                        $('#order-you-modal').fadeIn(300)
                        $("#modal-backing").fadeToggle();
                    });
                }
                $('#order-you-modal').fadeToggle(300);
                $('#thx div').html(message);
                $('#thx').fadeIn(300).addClass('active');
            }
        });
        return false;
    });
});