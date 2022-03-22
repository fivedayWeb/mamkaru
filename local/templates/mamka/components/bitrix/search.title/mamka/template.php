<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
//$this->addExternalCss("/bitrix/css/main/bootstrap.css");
//$this->addExternalCss("/bitrix/css/main/font-awesome.css");

$INPUT_ID = trim($arParams["~INPUT_ID"]);
if(strlen($INPUT_ID) <= 0)
	$INPUT_ID = "title-search-input";
$INPUT_ID = CUtil::JSEscape($INPUT_ID);

$CONTAINER_ID = trim($arParams["~CONTAINER_ID"]);
if(strlen($CONTAINER_ID) <= 0)
	$CONTAINER_ID = "title-search";
$CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);

if($arParams["SHOW_INPUT"] !== "N"):?>
<div id="<?echo $CONTAINER_ID?>" class="bx-searchtitle">
	<form id="search-form" action="<?echo $arResult["FORM_ACTION"]?>">
		<input type="search" placeholder="Название товара" id="<?echo $INPUT_ID?>" name="q" value="<?=htmlspecialcharsbx($_REQUEST["q"])?>" autocomplete="off" class="bx-form-control">
	</form>
	<button id="search-button" type="submit" name="s">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 300"><defs><style>.cls-1{fill:none;stroke-linecap:round;stroke-linejoin:round;stroke-width:6px;}</style></defs><circle class="cls-1" cx="135.6" cy="139" r="62"/><path class="cls-1" d="M218 226l-40.8-41M139 93.3a39.7 39.7 0 0 0-44 32"/></svg><span>Поиск по сайту</span>
	</button>
</div>

<?endif?>
<script>
	BX.ready(function(){
		new JCTitleSearch({
			'AJAX_PAGE' : '<?echo CUtil::JSEscape(POST_FORM_ACTION_URI)?>',
			'CONTAINER_ID': '<?echo $CONTAINER_ID?>',
			'INPUT_ID': '<?echo $INPUT_ID?>',
			'MIN_QUERY_LEN': 2
		});
	});
</script>

