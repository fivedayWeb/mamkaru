<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<div class="landing">
	<?$APPLICATION->IncludeComponent(
		"bitrix:news.list",
		"main_template",
		Array(
			"IMAGE_POSITION" => $arParams["IMAGE_POSITION"],
			"SHOW_CHILD_SECTIONS" => $arParams["SHOW_CHILD_SECTIONS"],
			"DEPTH_LEVEL" => 1,
			"BIG_IMG" => "Y",
			"IS_AJAX" => (isset($_GET["AJAX_REQUEST"]) && $_GET["AJAX_REQUEST"] == "Y"),
			"IMAGE_WIDE" => $arParams["IMAGE_WIDE"],
			"SHOW_SECTION_PREVIEW_DESCRIPTION" => $arParams["SHOW_SECTION_PREVIEW_DESCRIPTION"],
			"IBLOCK_TYPE"	=>	$arParams["IBLOCK_TYPE"],
			"IBLOCK_ID"	=>	($arParams["LANDING_IBLOCK_ID"] ? $arParams["LANDING_IBLOCK_ID"] : CMshopCache::$arIBlocks[SITE_ID]["aspro_mshop_catalog"]["aspro_mshop_landing"][0]),
			"NEWS_COUNT"	=>	$arParams["PAGE_ELEMENT_COUNT"],
			"SORT_BY1"	=>	$arParams["ELEMENT_SORT_FIELD_LANDING"],
			"SORT_ORDER1"	=>	$arParams["ELEMENT_SORT_ORDER_LANDING"],
			"SORT_BY2"	=>	$arParams["ELEMENT_SORT_FIELD2"],
			"SORT_ORDER2"	=>	$arParams["ELEMENT_SORT_ORDER2"],
			"FIELD_CODE"	=>	$arParams["LIST_FIELD_CODE"],
			"PROPERTY_CODE"	=>	$arParams["LIST_PROPERTY_CODE"],
			"DISPLAY_PANEL"	=>	$arParams["DISPLAY_PANEL"],
			"SET_TITLE"	=>	"N",
			"SET_STATUS_404" => $arParams["SET_STATUS_404"],
			"INCLUDE_IBLOCK_INTO_CHAIN"	=>	"N",
			"CACHE_TYPE"	=>	$arParams["CACHE_TYPE"],
			"CACHE_TIME"	=>	$arParams["CACHE_TIME"],
			"CACHE_FILTER"	=>	$arParams["CACHE_FILTER"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"DISPLAY_TOP_PAGER"	=>	$arParams["DISPLAY_TOP_PAGER"],
			"DISPLAY_BOTTOM_PAGER"	=>	$arParams["DISPLAY_BOTTOM_PAGER"],
			"PAGER_TITLE"	=>	$arParams["PAGER_TITLE"],
			"PAGER_TEMPLATE"	=>	$arParams["PAGER_TEMPLATE"],
			"PAGER_SHOW_ALWAYS"	=>	$arParams["PAGER_SHOW_ALWAYS"],
			"PAGER_DESC_NUMBERING"	=>	$arParams["PAGER_DESC_NUMBERING"],
			"PAGER_DESC_NUMBERING_CACHE_TIME"	=>	$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
			"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
			"DISPLAY_DATE"	=>	$arParams["DISPLAY_DATE"],
			"DISPLAY_NAME"	=>	$arParams["DISPLAY_NAME"],
			"DISPLAY_PICTURE"	=>	$arParams["DISPLAY_PICTURE"],
			"DISPLAY_PREVIEW_TEXT"	=>	$arParams["DISPLAY_PREVIEW_TEXT"],
			"PREVIEW_TRUNCATE_LEN"	=>	$arParams["PREVIEW_TRUNCATE_LEN"],
			"ACTIVE_DATE_FORMAT"	=>	$arParams["LIST_ACTIVE_DATE_FORMAT"],
			"USE_PERMISSIONS"	=>	$arParams["USE_PERMISSIONS"],
			"GROUP_PERMISSIONS"	=>	$arParams["GROUP_PERMISSIONS"],
			"SHOW_DETAIL_LINK"	=>	$arParams["SHOW_DETAIL_LINK"],
			"FILTER_NAME"	=>	$arParams["FILTER_NAME"],
			"HIDE_LINK_WHEN_NO_DETAIL"	=>	$arParams["HIDE_LINK_WHEN_NO_DETAIL"],
			"CHECK_DATES"	=>	$arParams["CHECK_DATES"],
			"IS_VERTICAL"	=>	$arParams["IS_VERTICAL"],
			"SHOW_PREVIEW_TEXT"	=>	(!isset($arParams["SHOW_PREVIEW_TEXT"]) ? "N" : $arParams["SHOW_PREVIEW_TEXT"]),
			"PARENT_SECTION"	=>	$arResult["VARIABLES"]["SECTION_ID"],
			"PARENT_SECTION_CODE"	=>	$arResult["VARIABLES"]["SECTION_CODE"],
			"DETAIL_URL"	=>	$arResult["SEF_FOLDER"].$arResult["SEF_URL_TEMPLATES"]["element"],
			"SECTION_URL"	=>	$arResult["SEF_FOLDER"].$arResult["SEF_URL_TEMPLATES"]["section"],
			"IBLOCK_URL"	=>	$arResult["SEF_FOLDER"].$arResult["SEF_URL_TEMPLATES"]["sections"],
			"INCLUDE_SUBSECTIONS" => "N",
		),
		$component
	);?>
</div>
