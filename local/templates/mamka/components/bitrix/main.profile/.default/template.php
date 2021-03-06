<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

<div class="bx-auth-profile">
<?ShowError($arResult["strProfileError"]);?>


<script type="text/javascript">
<!--
var opened_sections = [<?
$arResult["opened"] = $_COOKIE[$arResult["COOKIE_PREFIX"]."_user_profile_open"];
$arResult["opened"] = preg_replace("/[^a-z0-9_,]/i", "", $arResult["opened"]);
if (strlen($arResult["opened"]) > 0)
{
	echo "'".implode("', '", explode(",", $arResult["opened"]))."'";
}
else
{
	$arResult["opened"] = "reg";
	echo "'reg'";
}
?>];
//-->
var cookie_prefix = '<?=$arResult["COOKIE_PREFIX"]?>';
</script>

<form method="post" name="form1" id="profile-left" action="<?=$arResult["FORM_TARGET"]?>" enctype="multipart/form-data">
	<div class="notification-block"><?if ($arResult['DATA_SAVED'] == 'Y')	ShowNote(GetMessage('PROFILE_DATA_SAVED'));?></div>
	<input type="hidden" name="lang" value="<?=LANG?>" />
	<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
	<input class="bx-input-profile-login" type="hidden" name="LOGIN" value=<?=$arResult["arUser"]["~LOGIN"]?> />

	<?=$arResult["BX_SESSION_CHECK"]?>
	<div class="input-row clear">
		<label for="name">Имя<span>*</span></label>
		<input type="text" name="NAME" value="<?=$arResult["arUser"]["NAME"]?>" required>
	</div>
	<div class="input-row clear">
		<label for="sername">Фамилия<span>*</span></label>
		<input type="text" name="LAST_NAME" value="<?=$arResult["arUser"]["LAST_NAME"]?>" required>
	</div>
	<div class="input-row clear">
		<label for="date">Дата рождения</label>
		<input type="text" name="PERSONAL_BIRTHDAY" value="<?=$arResult["arUser"]["PERSONAL_BIRTHDAY"]?>">
	</div>
	<div class="input-row clear">
		<label for="E-mail">E-mail<span>*</span></label>
		<input class="bx-input-profile-email" type="email" name="EMAIL" value="<?=$arResult["arUser"]["EMAIL"]?>"  required>
	</div>
	<div class="input-row clear">
		<label for="index">Индекс<span>*</span></label>
		<input type="text" name="PERSONAL_ZIP" required value="<?=$arResult["arUser"]["PERSONAL_ZIP"]?>">
	</div>
	<div class="input-row clear">
		<label for="city">Город<span>*</span></label>
		<input type="text" name="PERSONAL_CITY" required value="<?=$arResult["arUser"]["PERSONAL_CITY"]?>">
	</div>
	<div class="input-row clear">
		<label for="address">Улица, дом, квартира<span>*</span></label>
		<input type="text" name="PERSONAL_STREET" required value="<?=$arResult["arUser"]["PERSONAL_STREET"]?>">
	</div>
	<div class="input-row clear">
		<label for="tel">Телефон<span>*</span></label>
		<input type="tel" name="PERSONAL_PHONE" required value="<?=$arResult["arUser"]["PERSONAL_PHONE"]?>">
	</div>
	<div id="profile-left-info"><span>*</span> Поля обязательные для заполнения</div>
	<input type="submit" name="save" value="Сохранить">
	<br/>
	<a href="?logout=yes" class="logout">Выйти</a>
</form>
</div>