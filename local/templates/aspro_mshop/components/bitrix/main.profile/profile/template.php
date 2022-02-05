<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="module-form-block-wr lk-page border_block">

<?ShowError($arResult["strProfileError"]);?>
<?if( $arResult['DATA_SAVED'] == 'Y' ) {?><?ShowNote(GetMessage('PROFILE_DATA_SAVED'))?><br /><?; }?>
<script>
	$(document).ready(function()
	{
		$(".form-block-wr form").validate({rules:{ EMAIL: { email: true }}	});
	})
</script>

	<div class="form-block-wr">
		<form method="post" name="form1" class="main" action="<?=$arResult["FORM_TARGET"]?>?" enctype="multipart/form-data">
			<?=$arResult["BX_SESSION_CHECK"]?>
			<?$sLoginEqual = COption::GetOptionString('aspro.mshop', 'LOGIN_EQUAL_EMAIL', 'Y');?>
			<?if($sLoginEqual == "Y"):?>
				<input type="hidden" name="LOGIN" maxlength="50" value="<? echo $arResult["arUser"]["LOGIN"]?>" />
			<?else:?>
				<div class="form-control">
					<div class="wrap_md">
						<div class="iblock label_block">
							<label><?=GetMessage("LOGIN")?><span class="star">*</span></label>
							<input required type="text" name="LOGIN" required value="<?=$arResult["arUser"]["LOGIN"]?>" />
						</div>
					</div>
				</div>
			<?endif?>
			<input type="hidden" name="lang" value="<?=LANG?>" />
			<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
			<div class="form-control">
				<div class="wrap_md">
					<div class="iblock label_block">
						<label><?=GetMessage("PERSONAL_NAME")?><span class="star">*</span></label>
						<?
						$arName = array();
						if(!$arResult["strProfileError"])
						{
							if($arResult["arUser"]["LAST_NAME"]){
								$arName[] = $arResult["arUser"]["LAST_NAME"];
							}
							if($arResult["arUser"]["NAME"]){
								$arName[] = $arResult["arUser"]["NAME"];
							}
							if($arResult["arUser"]["SECOND_NAME"]){
								$arName[] = $arResult["arUser"]["SECOND_NAME"];
							}
						}
						else
							$arName[] = htmlspecialcharsbx($_POST["NAME"]);
						?>
						<input required type="text" name="NAME" maxlength="50" value="<?=implode(' ', $arName);?>" />
					</div>
					<div class="iblock text_block">
						<?=GetMessage("PERSONAL_NAME_DESCRIPTION")?>
					</div>
				</div>
			</div>
			<div class="form-control">
				<div class="wrap_md">
					<div class="iblock label_block">
						<?
						$mask = \Bitrix\Main\Config\Option::get('aspro.market', 'PHONE_MASK', '+7 (999) 999-99-99');
						if(strpos($arResult["arUser"]["PERSONAL_PHONE"], '+') === false && strpos($mask, '+') !== false)
						{
							$arResult["arUser"]["PERSONAL_PHONE"] = '+'.$arResult["arUser"]["PERSONAL_PHONE"];
						}
						?>
						<label><?=GetMessage("PERSONAL_PHONE")?><span class="star">*</span></label>
						<input required type="text" name="PERSONAL_PHONE" class="phone" maxlength="255" value="<?=$arResult["arUser"]["PERSONAL_PHONE"]?>" />
					</div>
					<div class="iblock text_block">
						<?=GetMessage("PERSONAL_PHONE_DESCRIPTION")?>
					</div>
				</div>
			</div>
			<div class="form-control">
				<div class="wrap_md">
					<div class="iblock label_block">
						<label><?=GetMessage("PERSONAL_EMAIL")?><span class="star">*</span></label>
						<input required type="text" name="EMAIL" maxlength="50" placeholder="name@company.ru" value="<? echo $arResult["arUser"]["EMAIL"]?>" />
					</div>
					<div class="iblock text_block">
						<?if($sLoginEqual !='Y'):?>
							<?=GetMessage("PERSONAL_EMAIL_SHORT_DESCRIPTION")?>
						<?else:?>
							<?=GetMessage("PERSONAL_EMAIL_DESCRIPTION")?>
						<?endif;?>
					</div>
				</div>
			</div>
			<div class="but-r">
				<button class="button" type="submit" name="save" value="<?=(($arResult["ID"]>0) ? GetMessage("MAIN_SAVE_TITLE") : GetMessage("MAIN_ADD_TITLE"))?>"><span><?=(($arResult["ID"]>0) ? GetMessage("MAIN_SAVE_TITLE") : GetMessage("MAIN_ADD_TITLE"))?></span></button>
				<?/*<div class="prompt">
					<span class="star">*</span> &nbsp;&mdash;&nbsp; <?=GetMessage("REQUIRED_FIELDS")?>
				</div>
				<div class="clearboth"></div>
				<?/*<a class="cancel"><?=GetMessage('MAIN_RESET');?></a>*/?>
			</div>
			
		</form>
		<? if($arResult["SOCSERV_ENABLED"]){ $APPLICATION->IncludeComponent("bitrix:socserv.auth.split", "main", array("SUFFIX"=>"form", "SHOW_PROFILES" => "Y","ALLOW_DELETE" => "Y"),false);}?>
	</div>
</div>