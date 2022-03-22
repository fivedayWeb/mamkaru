<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
/** @var CBitrixBasketComponent $component */
$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME'],
);
$this->addExternalCss($templateData['TEMPLATE_THEME']);

$curPage = $APPLICATION->GetCurPage().'?'.$arParams["ACTION_VARIABLE"].'=';
$arUrls = array(
	"delete" => $curPage."delete&id=#ID#",
	"delay" => $curPage."delay&id=#ID#",
	"add" => $curPage."add&id=#ID#",
);
unset($curPage);

$arBasketJSParams = array(
	'SALE_DELETE' => GetMessage("SALE_DELETE"),
	'SALE_DELAY' => GetMessage("SALE_DELAY"),
	'SALE_TYPE' => GetMessage("SALE_TYPE"),
	'TEMPLATE_FOLDER' => $templateFolder,
	'DELETE_URL' => $arUrls["delete"],
	'DELAY_URL' => $arUrls["delay"],
	'ADD_URL' => $arUrls["add"],
	'EVENT_ONCHANGE_ON_START' => (!empty($arResult['EVENT_ONCHANGE_ON_START']) && $arResult['EVENT_ONCHANGE_ON_START'] === 'Y') ? 'Y' : 'N'
);
foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader){
	$arHeader["name"] = (isset($arHeader["name"]) ? (string)$arHeader["name"] : '');
	if ($arHeader["name"] == '')
		$arHeader["name"] = GetMessage("SALE_".$arHeader["id"]);
	$arHeaders[] = $arHeader["id"];
}
?>

<script type="text/javascript">
	var basketJSParams = <?=CUtil::PhpToJSObject($arBasketJSParams);?>
</script>
<?
$APPLICATION->AddHeadScript($templateFolder."/script.js");

if (strlen($arResult["ERROR_MESSAGE"]) <= 0):?>
	<div id="warning_message" class="hidden">
		<?
		if (!empty($arResult["WARNING_MESSAGE"]) && is_array($arResult["WARNING_MESSAGE"]))
		{
			foreach ($arResult["WARNING_MESSAGE"] as $v)
				ShowError($v);
		}
		?>
	</div>
	<?

	$normalCount = count($arResult["ITEMS"]["AnDelCanBuy"]);
	$normalHidden = ($normalCount == 0) ? 'style="display:none;"' : '';

	$delayCount = count($arResult["ITEMS"]["DelDelCanBuy"]);
	$delayHidden = ($delayCount == 0) ? 'style="display:none;"' : '';

	$subscribeCount = count($arResult["ITEMS"]["ProdSubscribe"]);
	$subscribeHidden = ($subscribeCount == 0) ? 'style="display:none;"' : '';

	$naCount = count($arResult["ITEMS"]["nAnCanBuy"]);
	$naHidden = ($naCount == 0) ? 'style="display:none;"' : '';

	?>
		<div class="sale-backet-form">
			<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="basket_form" id="basket_form">
				<div id="basket_form_container">
					<div class="bx_ordercart <?=$templateData['TEMPLATE_CLASS']; ?>">
						<?
						include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items.php");
						?>
					</div>
				</div>
				<input type="hidden" name="BasketOrder" value="BasketOrder" />
			</form>
		</div>
<?else:?>
	<div id="bx_error_basket">
		<div class="center clear">
			<div id="block-404">
				<span><?=$arResult["ERROR_MESSAGE"]?></span>
			</div>
		</div>
	</div>
<?endif?>