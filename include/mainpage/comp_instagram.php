<?$bAjaxMode = (isset($_POST["AJAX_REQUEST_INSTAGRAM"]) && $_POST["AJAX_REQUEST_INSTAGRAM"] == "Y");
if($bAjaxMode)
{
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$APPLICATION->SetTitle("");
	global $APPLICATION;
	\Bitrix\Main\Loader::includeModule("aspro.mshop");
	$apiToken = \Bitrix\Main\Config\Option::get("aspro.mshop", "API_TOKEN_INSTAGRAMM", "");
}?>
<?if(\Bitrix\Main\Config\Option::get("aspro.mshop", "API_TOKEN_INSTAGRAMM", "")):?>
	<?$APPLICATION->IncludeComponent(
		"aspro:instargam.mshop",
		"main",
		Array(
			"COMPOSITE_FRAME_MODE" => "A",
			"COMPOSITE_FRAME_TYPE" => "AUTO",
			"TITLE" => \Bitrix\Main\Config\Option::get("aspro.mshop", "INSTAGRAMM_TITLE_BLOCK", ""),
			"TOKEN" => \Bitrix\Main\Config\Option::get("aspro.mshop", "API_TOKEN_INSTAGRAMM", ""),
			"INSTAGRAMM_ITEMS_VISIBLE" => \Bitrix\Main\Config\Option::get("aspro.mshop", "INSTAGRAMM_ITEMS_VISIBLE", "4")
		)
	);?>
<?endif;?>