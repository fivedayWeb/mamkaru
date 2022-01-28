<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?/*<link rel="stylesheet" type="text/css" href="/bitrix/js/socialservices/css/ss.css">*/?>
<?if( $arResult["FORM_TYPE"] == "login" ){?>
	<div id="ajax_auth">
		<div class="auth_wrapp form-block">
			<div class="wrap_md">
				<div class="main_info iblock">
					<?$sLoginEqual = COption::GetOptionString('aspro.mshop', 'LOGIN_EQUAL_EMAIL', 'Y');?>
					<div class="form-wr">
						<?if( $arResult["ERROR"] ){?>
							<div class="error_block_auth"><?=GetMessage('AUTH_ERROR')?></div>
						<?}?>
						<form id="avtorization-form" name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arParams["AUTH_URL"]?>?login=yes">
							<?if($arResult["BACKURL"] <> ''):?>
								<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
							<?endif?>
							<?foreach ($arResult["POST"] as $key => $value):?><input type="hidden" name="<?=$key?>" value="<?=$value?>" /><?endforeach?>
									<input type="hidden" name="AUTH_FORM" value="Y" />
									<input type="hidden" name="TYPE" value="AUTH" />
							<div class="r form-control bg">
								<label class="" for="USER_LOGIN_POPUP"><?=($sLoginEqual == 'Y' ? GetMessage("EMAIL") : GetMessage("AUTH_LOGIN"));?> <span class="star">*</span></label>
								<input type="text" name="USER_LOGIN" id="USER_LOGIN_POPUP" class="required" maxlength="50" value="<?=$arResult["USER_LOGIN"]?>" placeholder="<?=GetMessage("AUTH_LOGIN")?>" autocomplete="on" tabindex="1"/>
							</div>
							<div class="r form-control bg">
								<label  class="" for="USER_PASSWORD_POPUP"><?=GetMessage("AUTH_PASSWORD")?> <span class="star">*</span></label>	
								<input type="password" name="USER_PASSWORD" id="USER_PASSWORD_POPUP" class="required" maxlength="50" placeholder="<?=GetMessage("AUTH_PASSWORD")?>" tabindex="2"/>
							</div>
							<?if ($arResult["CAPTCHA_CODE"]):?>
								<div class="form-control captcha-row clearfix">
									<?echo GetMessage("AUTH_CAPTCHA_PROMT")?>:<br />
									<div class="captcha_image">
										<input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
										<img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
										<div class="captcha_reload"></div>
									</div>
									<div class="captcha_input">
										<input type="text" name="captcha_word" maxlength="50" value="" />
									</div>
								</div>
							<?endif?>
							<div class="but-r cleaarboth">
								<div class="filter block">
									<a class="forgot" href="<?=SITE_DIR?>auth/forgot-password/" tabindex="3"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a>
									<div class="prompt remember">
										<input type="checkbox" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y" tabindex="5"/>
										<label for="USER_REMEMBER_frm" title="<?=GetMessage("AUTH_REMEMBER_ME")?>" tabindex="5"><?echo GetMessage("AUTH_REMEMBER_SHORT")?></label>
									</div>
								</div>
								<div class="buttons">
									<button type="submit" class="button vbig_btn wides" name="Login" value="" tabindex="4">
										<span><?=GetMessage("AUTH_LOGIN_BUTTON")?></span>
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="iblock socserv">
					<!--noindex--><a href="<?=SITE_DIR?>auth/registration/" rel="nofollow" class="button transparent vbig_btn wides" tabindex="6"><?=GetMessage("AUTH_REGISTER_NEW")?></a><!--/noindex-->
					<div class="more_text_small">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/top_auth.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("TOP_AUTH_REGISTER")));?>
					</div>
				</div>
			</div>
						
			<?if($arResult["AUTH_SERVICES"]):?>
				<div class="reg-new">
					<div class="soc-avt">
						<?=GetMessage("SOCSERV_AS_USER_FORM");?>
						<?$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "icons", 
							array(
								"AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
								"AUTH_URL" => SITE_DIR."auth/?login=yes",
								"POST" => $arResult["POST"],
								"SUFFIX" => "form",
							), 
							$component, array("HIDE_ICONS"=>"Y")
						);
						?>
					</div>
				</div>
			<?endif;?> 
				
		</div>
	</div>
	<?
	}elseif($arResult["FORM_TYPE"] == "otp"){
	?>

	<form id="avtorization-form" class="form-wr otp" name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
	<?if($arResult["BACKURL"] <> ''):?>
		<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
	<?endif?>
		<input type="hidden" name="AUTH_FORM" value="Y" />
		<input type="hidden" name="TYPE" value="OTP" />
		<table>
			<tr>
				<td colspan="2">
					<div class="form-control">
						<label for="USER_OTP"><?echo GetMessage("auth_form_comp_otp")?> <span class="star">*</span></label>
						<input type="text" id="USER_OTP" name="USER_OTP" maxlength="50" value="" size="17" autocomplete="off" required aria-required="true" />
					</div>
				</td>
			</tr>
	<?if ($arResult["CAPTCHA_CODE"]):?>
			<tr>
				<td colspan="2">
				<?echo GetMessage("AUTH_CAPTCHA_PROMT")?>:<br />
				<input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
				<img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /><br /><br />
				<input type="text" name="captcha_word" maxlength="50" value="" /></td>
			</tr>
	<?endif?>
	<?if ($arResult["REMEMBER_OTP"] == "Y"):?>
			<tr>
				<td valign="top"><input type="checkbox" id="OTP_REMEMBER_frm" name="OTP_REMEMBER" value="Y" /></td>
				<td width="100%"><label for="OTP_REMEMBER_frm" title="<?echo GetMessage("auth_form_comp_otp_remember_title")?>"><?echo GetMessage("auth_form_comp_otp_remember")?></label></td>
			</tr>
	<?endif?>
			<tr>
				<td colspan="2"><input class="button vbig_btn wides" type="submit" name="Login" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" /></td>
			</tr>
			<tr>
				<td colspan="2"><noindex><a href="<?=$arResult["AUTH_LOGIN_URL"]?>" rel="nofollow"><?echo GetMessage("auth_form_comp_auth")?></a></noindex><br /></td>
			</tr>
		</table>
	</form>
	
<?}else{?>
	<script>
			BX.reload(true);
	</script>
<?}?>
			<script>
				$(document).ready(function()
				{
					$("form[name=bx_auth_servicesform]").validate(); 
					$( 'form#avtorization-form' ).validate( {
						highlight: function( element ){
							$( element ).addClass( 'error' );
						},
						unhighlight: function( element ){
							$( element ).removeClass( 'error' );
						},
						rules:{
							USER_LOGIN:{ 
								<?if($sLoginEqual == 'Y'):?>
								email: true,
								<?endif;?>
								required:true
							},
							USER_OTP: {
								required:true
							}
						},
						submitHandler: function( form ){
							if( $( form ).valid() ){
								//$( form ).find('input[name^="USER_LOGIN"]').val().replace(/[-\s+\(\)_]/g,'');
								jsAjaxUtil.CloseLocalWaitWindow( 'id', 'wrap_ajax_auth' );
								jsAjaxUtil.ShowLocalWaitWindow( 'id', 'wrap_ajax_auth', true );
								//$( form ).find( 'button[type="submit"]' ).attr( "disabled", "disabled" );
								$.ajax({
									type: "POST",
									url: $(form).attr('action'),
									data: $(form).serialize()
								}).done(function( text ){
									$('#ajax_auth').html( text );
									jsAjaxUtil.CloseLocalWaitWindow( 'id', 'wrap_ajax_auth' );
								});
							}
						},
						errorPlacement: function( error, element ){
							$( error ).attr( 'alt', $( error ).text() );
							$( error ).attr( 'title', $( error ).text() );
							error.insertBefore( element );
						}
					} );
				})
			</script>
<script>
BX.loadScript(['<?=Bitrix\Main\Page\Asset::getInstance()->getFullAssetPath('/bitrix/js/main/pageobject/pageobject.js')?>']);
</script>