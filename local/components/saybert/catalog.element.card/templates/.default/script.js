/**
 * Created by saybe on 10.01.2017.
 */
$(function(){
    /*$('.card .card-form .card-add-button').on('click',function(){
        var card = $(this).parents('.card');
        var disabled = $(this).is(':disabled');
        var offer = card.data('offer');
        var count = $('input[name=count]',card).val()*1;
        count = (typeof(count) == 'number' && count > 0) ?  count : 1;
        if(disabled)
            return false;
        var ajaxData = {
            'action' : 'BUY',
            'ajax_basket' : "Y",
            'id' : offer,
            'quantity':count,
        };
        var url = $('.card-title',card).attr('href');
        
        $.ajax({
            'url': url,
            'data':ajaxData,
            'success':function(reponse){
                var picture = $('img',card).attr('src'),
                    name = $('.card-title',card).html(),
                    price = $('.price',card).html(),
                    quantity = count;
                addToBasket(picture,price,name,quantity);
            }
        });
        return false;
    })*/

    $('.count-button.minus').on('click',function(){
        var card = $(this).parents('.card');
        var curIndex = $('input[name=count]',card).val()*1;
        console.log(curIndex);
        if(curIndex > 1) {
            curIndex--;
            var card = $(this).parents('.card');
            $('.count-counter', card).html(curIndex);
            $('input[name=count]', card).val(curIndex);
        }
        return false
    });

    $('.count-button.plus').on('click',function(){
        var card = $(this).parents('.card');
        var curIndex = $('input[name=count]',card).val()*1;
        curIndex++;
        var card = $(this).parents('.card');
        $('.count-counter', card).html(curIndex);
        $('input[name=count]', card).val(curIndex);
        return false
    });

});