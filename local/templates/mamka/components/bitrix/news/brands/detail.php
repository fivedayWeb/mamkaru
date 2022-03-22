<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<? echo "1: news/brands/detail.php";?>
<div class="center clear">
	<?$ElementID = $APPLICATION->IncludeComponent(
		"bitrix:news.detail",
		"before",
		Array(
			"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
			"DISPLAY_NAME" => $arParams["DISPLAY_NAME"],
			"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
			"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"FIELD_CODE" => $arParams["DETAIL_FIELD_CODE"],
			"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
			"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
			"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
			"META_KEYWORDS" => $arParams["META_KEYWORDS"],
			"META_DESCRIPTION" => $arParams["META_DESCRIPTION"],
			"BROWSER_TITLE" => $arParams["BROWSER_TITLE"],
			"SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
			"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
			"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
			"SET_TITLE" => "Y",
			"MESSAGE_404" => $arParams["MESSAGE_404"],
			"SET_STATUS_404" => $arParams["SET_STATUS_404"],
			"SHOW_404" => $arParams["SHOW_404"],
			"FILE_404" => $arParams["FILE_404"],
			"INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
			"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
			"ACTIVE_DATE_FORMAT" => $arParams["DETAIL_ACTIVE_DATE_FORMAT"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
			"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
			"DISPLAY_TOP_PAGER" => $arParams["DETAIL_DISPLAY_TOP_PAGER"],
			"DISPLAY_BOTTOM_PAGER" => $arParams["DETAIL_DISPLAY_BOTTOM_PAGER"],
			"PAGER_TITLE" => $arParams["DETAIL_PAGER_TITLE"],
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => $arParams["DETAIL_PAGER_TEMPLATE"],
			"PAGER_SHOW_ALL" => $arParams["DETAIL_PAGER_SHOW_ALL"],
			"CHECK_DATES" => $arParams["CHECK_DATES"],
			"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
			"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
			"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
			"SEARCH_PAGE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["search"],
			"USE_SHARE" => $arParams["USE_SHARE"],
			"SHARE_HIDE" => $arParams["SHARE_HIDE"],
			"SHARE_TEMPLATE" => $arParams["SHARE_TEMPLATE"],
			"SHARE_HANDLERS" => $arParams["SHARE_HANDLERS"],
			"SHARE_SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
			"SHARE_SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
			"ADD_ELEMENT_CHAIN" => (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : ''),
			"USE_RATING" => $arParams["USE_RATING"],
			"MAX_VOTE" => $arParams["MAX_VOTE"],
			"VOTE_NAMES" => $arParams["VOTE_NAMES"],
			"MEDIA_PROPERTY" => $arParams["MEDIA_PROPERTY"],
			"SLIDER_PROPERTY" => $arParams["SLIDER_PROPERTY"],
			"TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
		),
		$component
	);?>

	<? echo "2: news/brands/detail.php";?>
	<?
	$brandName = $arResult["VARIABLES"]["ELEMENT_CODE"];
	/*$GLOBALS['arrBrandName'] = array("PROPERTY_31809_VALUE"=>$brandName);*/
	//print_r($brandName);
	//print_r($arProperty);

	$resProperty = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $ElementID, array(), array('CODE' => 'PROPERTY_ID'));
	$arProperty = $resProperty->Fetch();

	if ($arProperty['VALUE']):?>
	<? echo "if ($arProperty[VALUE])";?>
		<div class="bx-products">

			<hr>

			<?
			global $FILTER_FOR_BRAND;
			$FILTER_FOR_BRAND = array(
				'PROPERTY_CML2_MANUFACTURER' => $arProperty['VALUE']
			);
			$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"catalog.item",
				Array(
					"IBLOCK_TYPE" => $arParams["CATALOG_IBLOCK_TYPE"],
					"IBLOCK_ID" => $arParams["CATALOG_IBLOCK_ID"],
					"CACHE_TYPE" => $arParams["CACHE_TYPE"],
					"CACHE_TIME" => $arParams["CACHE_TIME"],
					"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
					"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
					"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
					"FILTER_NAME" => '\$FILTER_FOR_BRAND',
					"NEWS_COUNT" => 12,
					"PAGER_TEMPLATE" => "catalog",
					"PROPERTY_CODE" => array("PROPERTY_ID",""),

                    "SORT_BY1" => "SORT",
                    "SORT_BY2" => "NAME",
                    "SORT_ORDER1" => "ASC",
                    "SORT_ORDER2" => "ASC",
					
					"INCLUDE_IBLOCK_INTO_CHAIN" => 'N',
				),
				$component
			);?>
		</div>
	<?endif;?>
	<? echo "3: news/brands/detail.php";?>
	<?$ElementID = $APPLICATION->IncludeComponent(
		"bitrix:news.detail",
		"after",
		Array(
			"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
			"DISPLAY_NAME" => $arParams["DISPLAY_NAME"],
			"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
			"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"FIELD_CODE" => $arParams["DETAIL_FIELD_CODE"],
			"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
			"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
			"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
			"META_KEYWORDS" => $arParams["META_KEYWORDS"],
			"META_DESCRIPTION" => $arParams["META_DESCRIPTION"],
			"BROWSER_TITLE" => $arParams["BROWSER_TITLE"],
			"SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
			"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
			"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
			"SET_TITLE" => "Y",
			"MESSAGE_404" => $arParams["MESSAGE_404"],
			"SET_STATUS_404" => $arParams["SET_STATUS_404"],
			"SHOW_404" => $arParams["SHOW_404"],
			"FILE_404" => $arParams["FILE_404"],
			"INCLUDE_IBLOCK_INTO_CHAIN" => 'N',//$arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
			"ADD_SECTIONS_CHAIN" => 'N',//$arParams["ADD_SECTIONS_CHAIN"],
			"ACTIVE_DATE_FORMAT" => $arParams["DETAIL_ACTIVE_DATE_FORMAT"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
			"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
			"DISPLAY_TOP_PAGER" => $arParams["DETAIL_DISPLAY_TOP_PAGER"],
			"DISPLAY_BOTTOM_PAGER" => $arParams["DETAIL_DISPLAY_BOTTOM_PAGER"],
			"PAGER_TITLE" => $arParams["DETAIL_PAGER_TITLE"],
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => $arParams["DETAIL_PAGER_TEMPLATE"],
			"PAGER_SHOW_ALL" => $arParams["DETAIL_PAGER_SHOW_ALL"],
			"CHECK_DATES" => $arParams["CHECK_DATES"],
			"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
			"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
			"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
			"SEARCH_PAGE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["search"],
			"USE_SHARE" => $arParams["USE_SHARE"],
			"SHARE_HIDE" => $arParams["SHARE_HIDE"],
			"SHARE_TEMPLATE" => $arParams["SHARE_TEMPLATE"],
			"SHARE_HANDLERS" => $arParams["SHARE_HANDLERS"],
			"SHARE_SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
			"SHARE_SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
			"ADD_ELEMENT_CHAIN" => 'N',//(isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : ''),
			"USE_RATING" => $arParams["USE_RATING"],
			"MAX_VOTE" => $arParams["MAX_VOTE"],
			"VOTE_NAMES" => $arParams["VOTE_NAMES"],
			"MEDIA_PROPERTY" => $arParams["MEDIA_PROPERTY"],
			"SLIDER_PROPERTY" => $arParams["SLIDER_PROPERTY"],
			"TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
		),
		$component
	);?>
</div>
<? echo "4: news/brands/detail.php";?>