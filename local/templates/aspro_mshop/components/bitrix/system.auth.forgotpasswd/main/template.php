<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(isset($APPLICATION->arAuthResult))
	$arResult['ERROR_MESSAGE'] = $APPLICATION->arAuthResult;?>

<div class="border_block">
	<div class="module-form-block-wr lk-page">
		<?ShowMessage($arResult['ERROR_MESSAGE']);?>
		<div class="form-block">
			<form name="bform" method="post" target="_top" class="bf" action="<?=SITE_DIR?>auth/forgot-password/">
				<?if (strlen($arResult["BACKURL"]) > 0){?><input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" /><?}?>
				<input type="hidden" name="AUTH_FORM" value="Y">
				<input type="hidden" name="TYPE" value="SEND_PWD">
				<?=GetMessage("AUTH_FORGOT_PASSWORD_1")?>
				<br /><br />
				<div class="r form-control">
					<label><?=GetMessage("AUTH_EMAIL")?> <span class="star">*</span></label>
					<input type="email" name="USER_EMAIL" required  maxlength="255" />
				</div>	

				<?if ($arResult["USE_CAPTCHA"]):?>
					<div class="form-control captcha-row clearfix">
						<label><span><?=GetMessage("FORM_CAPTCHA_TITLE")?>&nbsp;<span class="star">*</span></span></label>
						<div class="captcha_image">
							<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
							<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
							<div class="captcha_reload"></div>
						</div>
						<div class="captcha_input">
							<input type="text" class="inputtext captcha" name="captcha_word" size="30" maxlength="50" value="" required />
						</div>
					</div>
				<?endif?>
				
				<div class="but-r">
					<button class="button vbig_btn wides" type="submit" name="send_account_info" value=""><span><?=GetMessage("RETRIEVE")?></span></button>		
					<?/*<div class="prompt"><span class="star">*</span> &mdash;&nbsp; <?=GetMessage("REQUIRED_FIELDS")?></div>
					<div class="clearboth"></div>*/?>
				</div>
			</form>	
		</div>
		<script type="text/javascript">document.bform.USER_EMAIL.focus();</script>
	</div>
</div>
<script>
$(document).ready(function(){
	$('form[name=bform]').validate({
		highlight: function( element ){
			$(element).parent().addClass('error');
		},
		unhighlight: function( element ){
			$(element).parent().removeClass('error');
		},
		submitHandler: function( form ){
			if( $('form[name=bform]').valid() ){
				var eventdata = {type: 'form_submit', form: form, form_name: 'FORGOT'};
				BX.onCustomEvent('onSubmitForm', [eventdata]);
			}
		},
	})
})
</script>