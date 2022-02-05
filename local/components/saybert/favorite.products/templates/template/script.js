$(function(){
	$('._change_quantity').on('click', function(){
		var $this = $(this);
		var $form = $this.closest('form');
		var $inputQuantity = $form.find('[name=quantity]');

		var currentQuantity = parseInt($inputQuantity.val());
		var changeQuantity = 0;
		switch($this.attr('data-type'))
		{
			case 'minus':
				if (currentQuantity > 1) 
				{
					changeQuantity = -1;
				}
				break;
			case 'plus':
				var maxQuantity = parseInt($inputQuantity.attr('data-max'));
				if (currentQuantity < maxQuantity)
				{
					changeQuantity = 1;
				}
				break;
			default: break;
		}
		var newQuantity = currentQuantity + changeQuantity;

		$inputQuantity.val(newQuantity);
		$form.find('._count_quantity_text').text(newQuantity);

		return false;
	});
	$('._deleteFromFavorite').on('submit', function(){
        var $this = $(this);
        var callbackSuccess = function(message) {
            $this.closest('._card').slideUp(function(){
            	$(this).remove();
            });
        }
        var callbackError = function(error) {
            alert(error);
        }
        var params = {
            url: $(this).attr('action') || '',
            data: $(this).serialize(),
            method: $(this).attr('method') || 'POST',
            dataType: $(this).data('type') || 'json',
        };
        if (params.dataType == 'json') {
            params.success = function(res) {
                if (typeof(res.error) !== "undefined") {
                    callbackError(res.error);
                } else {
                    callbackSuccess(res.message);
                }
            }
        } else {
            params.success = function(res) {
            	callbackSuccess(res);
            }
        }
        params.error = function(a) {
        	callbackError('Ошибка: ' + a.status + ' ' + a.statusText);
        }
        $.ajax(params);
        return false;
    });
});