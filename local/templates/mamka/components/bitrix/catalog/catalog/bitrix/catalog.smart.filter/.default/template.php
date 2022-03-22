<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>
<button class="sidebar-filter-button">Фильтр</button>
<section id="sidebar-filter" class="clear">
	<form  action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="smartfilter bx-filter">
		<h2>Фильтр</h2>
		<?foreach($arResult["ITEMS"] as $key=>$arItem)
		{
			if (empty($arItem["VALUES"]) || isset($arItem["PRICE"])) continue;
			if ($arItem["DISPLAY_TYPE"] == "A" && ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)) continue;
			if ($arItem["DISPLAY_TYPE"] == "A" && ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] > 0))
			{
				include('types/slider_type.php');
			}
			elseif ($arItem['CODE'] == 'BRAND' && $arItem['PROPERTY_TYPE'] == 'E' && !empty($arItem['VALUES']))
			{
				include('types/brand_type.php');
			}
			else
			{
				include('types/checkbox_type.php');
			}
		}
		foreach($arResult["ITEMS"] as $key=>$arItem)
		{
			$key = $arItem["ENCODED_ID"];
			if(isset($arItem["PRICE"])):
				if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
					continue;
				include('types/price_type.php');
			endif;
		}?>
		<? /**Всплывающая подсказка о количестве элементов*/?>
		<div class="bx-filter-popup-result right" id="modef" style="display: <?=isset($arResult["ELEMENT_COUNT"]) ? 'inline-block' : 'none'?>;">
			<?echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?>
			<span class="arrow"></span>
			<br/>
			<a href="<?echo $arResult["FILTER_URL"]?>"><?echo GetMessage("CT_BCSF_FILTER_SHOW")?></a>
		</div>
		<input
			type="submit"
			id="set_filter"
			name="set_filter"
			value="Показать"
		/>
		<button type="reset" id="reset-filter">Сбросить все</button>
	</form>
</section>

<script>
	var smartFilter = new JCSmartFilter(
		'<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', 
		'<?=CUtil::JSEscape($arParams["FILTER_VIEW_MODE"])?>', 
		<?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>
	);
</script>