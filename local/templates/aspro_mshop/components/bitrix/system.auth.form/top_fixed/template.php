<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<form id="auth_params" action="<?=SITE_DIR?>ajax/show_personal_block.php">
	<input type="hidden" name="REGISTER_URL" value="<?=$arParams["REGISTER_URL"]?>" />
	<input type="hidden" name="FORGOT_PASSWORD_URL" value="<?=$arParams["FORGOT_PASSWORD_URL"]?>" />
	<input type="hidden" name="PROFILE_URL" value="<?=$arParams["PROFILE_URL"]?>" />
	<input type="hidden" name="SHOW_ERRORS" value="<?=$arParams["SHOW_ERRORS"]?>" />
</form>
<?
$frame = $this->createFrame()->begin('');
$frame->setBrowserStorage(true);
?>
<?if(!$USER->IsAuthorized()):?>
	<div class="module-enter no-have-user f-left">
		<span class="avtorization-call enter">
			<span>
				<?=CMShop::showIconSvg('wraps_icon_block', SITE_TEMPLATE_PATH.'/images/svg/user_not_auth.svg', '', 'auth_icon_block');?>
			</span>
		</span>
		
		<script type="text/javascript">
		$(document).ready(function(){
			jqmEd('enter', 'auth', '.avtorization-call.enter');
		});
		</script>
	</div>
<?else:?>
	<div class="module-enter have-user f-left">
		<!--noindex-->
			<a href="<?=$arResult["PROFILE_URL"]?>" class="reg" rel="nofollow">
				<span>
					<?
					//echo SITE_TEMPLATE_PATH.'images/svg/user_auth.svg';
					?>
					<?=CMShop::showIconSvg('wraps_icon_block', SITE_TEMPLATE_PATH.'/images/svg/user_auth.svg', '', 'auth_icon_block');?>
				</span>
			</a>
		<!--/noindex-->
	</div>	
<?endif;?>
<?$frame->end();?>
