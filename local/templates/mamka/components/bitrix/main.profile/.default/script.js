$(function(){
	$('.bx-input-profile-email').change('on',function () {
		$('.bx-input-profile-login').val($(this).val());
	});
	$('input[type=tel]').mask('+7 (999) 999-99-99');
	$('input[name=PERSONAL_BIRTHDAY]').mask("99.99.9999", {placeholder: 'дд.мм.гггг' });
});