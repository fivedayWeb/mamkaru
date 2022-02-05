$(document).ready(function() {
	$.ajax({
		url: arMShopOptions['SITE_DIR']+'include/mainpage/comp_instagram.php',
		data: {'AJAX_REQUEST_INSTAGRAM': 'Y', 'SHOW_INSTAGRAM': arMShopOptions['THEME']['INSTAGRAMM_INDEX']},
		type: 'POST',
		success: function(html){
			$('.instagram_ajax').html(html).addClass('loaded');
			var eventdata = {action:'instagrammLoaded'};
			BX.onCustomEvent('onCompleteAction', [eventdata]);
			$('.instagram_ajax').height('auto');
		}
	});
});