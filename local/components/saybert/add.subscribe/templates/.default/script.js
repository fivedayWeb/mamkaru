$(function(){
    $('.bx-news-subscribe-form').on('submit',function(){
        var data = $('form',$(this)).serialize();
        $.ajax({
            data:data,
            dataType: 'json',
            method: 'post',
            success:function(response){
                if(response.error)
                    alert(response.message);
                else
                    $('.bx-news-subscribe-form').html('<h2>' + 'Вы подписаны на рассылку' +'</h2>')
            }
        });
        return false;
    })
})