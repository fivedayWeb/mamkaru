<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arResult['SECTIONS'] = array_filter(
	$arResult['SECTIONS'],
	function($arItem)
	{
		return $arItem['ELEMENT_CNT'] > 0;
	}
);

$arResult['SECTIONS'] = array_map(
	function($arItem)
	{
		if (empty($arItem['PICTURE']))
		{
			$arItem['PICTURE']['SRC'] = SITE_TEMPLATE_PATH.'/i/category-card.png';
		}
		return $arItem;
	}, 
	$arResult['SECTIONS']
);