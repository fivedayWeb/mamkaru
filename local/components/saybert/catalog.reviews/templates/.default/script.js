$(function(){
    $("#add_review form").on('submit',function () {
        var data = $(this).serialize();

        $.ajax({
            data:data,
            method:'POST',
            dataType: 'json',
            success:function(response){
                if(response.error)
                    alert(response.errorMessage);
                else
                    alert("Ваш отзыв отправлен на модерацию");

                $("#add_review form textarea").val('')
            }
        });
        return false;
    });

});