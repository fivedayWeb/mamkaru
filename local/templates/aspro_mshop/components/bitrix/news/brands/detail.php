<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if($arParams["USE_RSS"]=="Y"):?>
	<?
		if(method_exists($APPLICATION, 'addheadstring'))
		$APPLICATION->AddHeadString('<link rel="alternate" type="application/rss+xml" title="'.$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["rss"].'" href="'.$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["rss"].'" />');
	?>
	<a href="<?=$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["rss"]?>" title="RSS" class="rss_feed_icon"><?=GetMessage("RSS_TITLE")?></a>
<?endif;?>

<?
if(
	isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
	strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' &&
	isset($_GET['ajax_get_filter']) &&
	$_GET['ajax_get_filter'] === 'Y'
){
	$isAjaxFilter = 'Y';
}

$bAjax = isset($_GET["ajax_get"]) && $_GET["ajax_get"] == 'Y';

if($arResult["VARIABLES"]["ELEMENT_ID"] > 0){
	$arElement = CMshopCache::CIBLockElement_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMshopCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE"=>"Y", "ID" => $arResult["VARIABLES"]["ELEMENT_ID"]), false, false, array("ID", "NAME", "IBLOCK_SECTION_ID", "CODE", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_PICTURE"));
}
elseif(strlen(trim($arResult["VARIABLES"]["ELEMENT_CODE"])) > 0){

	$arElement = CMshopCache::CIBLockElement_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMshopCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE"=>"Y", "=CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"]), false, false, array("ID", "NAME", "IBLOCK_SECTION_ID", "CODE", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_PICTURE"));
}

CMShop::AddMeta(
	array(
		'og:description' => $arElement['PREVIEW_TEXT'],
		'og:image' => (($arElement['PREVIEW_PICTURE'] || $arElement['DETAIL_PICTURE']) ? CFile::GetPath(($arElement['PREVIEW_PICTURE'] ? $arElement['PREVIEW_PICTURE'] : $arElement['DETAIL_PICTURE'])) : false),
	)
);

$bFilter = $arParams['SHOW_LINKED_PRODUCTS'] === 'Y' && $arParams['SHOW_FILTER_LEFT'] === 'Y';
$bSectionsLeft = $arParams['SHOW_LINKED_PRODUCTS'] === 'Y' && $arParams['SHOW_ITEM_SECTION_LEFT'] === 'Y';
?>

<?
global $TEMPLATE_OPTIONS;

$arAllSections = $arSectionsID = $arItems = $arItemsID = array();
$catalogIBlockID = \Bitrix\Main\Config\Option::get('aspro.mshop', 'CATALOG_IBLOCK_ID', '', SITE_ID);

$arParams["AJAX_FILTER_CATALOG"] = $arParams["AJAX_FILTER_CATALOG"] === 'Y' ? 'Y' : 'N';
if(!in_array($arParams["LIST_OFFERS_FIELD_CODE"], "DETAIL_PAGE_URL")){
	$arParams["LIST_OFFERS_FIELD_CODE"][] = "DETAIL_PAGE_URL";
}
if($arParams["FILTER_NAME"] == '' || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"])){
	$arParams["FILTER_NAME"] = "arrFilter";
}

if($arElement["ID"] && $arParams["LINKED_PRODUCTS_PROPERTY"]){
	$arItemsFilter = array("IBLOCK_ID" => $catalogIBlockID, "ACTIVE" => "Y", "PROPERTY_".$arParams["LINKED_PRODUCTS_PROPERTY"] => $arElement["ID"]);
	$arItems = CMshopCache::CIBLockElement_GetList(array('CACHE' => array("MULTI" =>"Y", "TAG" => CMshopCache::GetIBlockCacheTag($catalogIBlockID))), $arItemsFilter, false, false, array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID"));
	if($arItems){
		$arItemsID = array_column($arItems, 'ID');
	}
}

foreach($arItems as $arItem){
	if($arItem["IBLOCK_SECTION_ID"]){
		if(is_array($arItem["IBLOCK_SECTION_ID"])){
			foreach($arItem["IBLOCK_SECTION_ID"] as $id){
				$arAllSections[$id]["COUNT"]++;
				$arAllSections[$id]["ITEMS"][$arItem["ID"]] = $arItem["ID"];
			}
		}
		else{
			$arAllSections[$arItem["IBLOCK_SECTION_ID"]]["COUNT"]++;
			$arAllSections[$arItem["IBLOCK_SECTION_ID"]]["ITEMS"][$arItem["ID"]] = $arItem["ID"];
		}
	}
}

if($arAllSections){
	$arParams["DEPTH_LEVEL_BRAND"] = ($arParams["DEPTH_LEVEL_BRAND"] ? $arParams["DEPTH_LEVEL_BRAND"] : 3);
	$arSectionsID = array_keys($arAllSections);
	$arSectionsFilter = array(
		"ID" => $arSectionsID,
		"IBLOCK_ID" => $catalogIBlockID,
		"<=DEPTH_LEVEL" => $arParams["DEPTH_LEVEL_BRAND"],
	);
	$arSections = CMshopCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "GROUP" => "ID", "TAG" => CMshopCache::GetIBlockCacheTag($catalogIBlockID))), $arSectionsFilter, false, array("ID", "IBLOCK_ID", "NAME"));

	foreach($arAllSections as $key => $arTmpSection){
		if(!isset($arSections[$key])){
			unset($arAllSections[$key]);
		}
	}
}

?>

<?if($bSectionsLeft):?>

	<?
	$sectionIDRequest = $bSectionsLeft && isset($_GET["section_id"]) && $_GET["section_id"] ? $_GET["section_id"] : 0;

	if($sectionIDRequest){
		$GLOBALS[$arParams['CATALOG_FILTER_NAME']]['SECTION_ID'] = $sectionIDRequest;
	}
	?>
	<?ob_start();?>
	<?if($bSectionsLeft && $arAllSections):?>
		<div class="top_block_filter_section">
			<div class="title"><a class="dark_link" title="<?=GetMessage("FILTER_ALL_SECTON");?>" href="<?=$APPLICATION->GetCurPage(false)?>"><?=GetMessage("FILTER_SECTON");?></a></div>
			<div class="items">
				<?foreach($arAllSections as $key => $arTmpSection):?>
					<div class="item <?=($sectionIDRequest ? ($key == $sectionIDRequest ? 'current' : '') : '');?>"><a href="<?=$APPLICATION->GetCurPage(false).'?section_id='.$key?>" class="dark_link"><span><?=$arSections[$key]["NAME"];?></span><span><?=$arTmpSection["COUNT"];?></span></a></div>
				<?endforeach;?>
			</div>
		</div>
	<?endif;?>
	<?$GLOBALS['sectionsContent'] = ob_get_clean();?>

<?endif;?>

<?if($bFilter || $bSectionsLeft):?>
	<?$bFilter &= $arItems;?>
	<?$this->SetViewTarget('detail_filter');?>
		<?if($bFilter || ($bSectionsLeft && $arAllSections)):?>
			<div class="left_block detail<?=($bFilter ? ' filter_ajax' : '')?>">
				<?=$GLOBALS['sectionsContent']?>
				<?if($bFilter):?>
					<div class="visible_mobile_filter swipeignore filter_wrapper_left">
						<?$APPLICATION->IncludeComponent(
							"aspro:catalog.smart.filter.mshop",
							($arParams["AJAX_FILTER_CATALOG"]=="Y" ? "main_ajax" : "main"),
							Array(
								"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
								"IBLOCK_ID" => $catalogIBlockID,
								"AJAX_FILTER_FLAG" => $isAjaxFilter,
								"SECTION_ID" => '',
								"FILTER_NAME" => $arParams["CATALOG_FILTER_NAME"],
								"PRICE_CODE" => ($arParams["USE_FILTER_PRICE"] == 'Y' ? $arParams["FILTER_PRICE_CODE"] : $arParams["PRICE_CODE"]),
								"CACHE_TYPE" => $arParams["CACHE_TYPE"],
								"CACHE_TIME" => $arParams["CACHE_TIME"],
								"CACHE_NOTES" => "",
								"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
								"SECTION_IDS" => ($sectionIDRequest ? array($sectionIDRequest) : $arSectionsID),
								"ELEMENT_IDS" => ($sectionIDRequest ? $arAllSections[$sectionIDRequest]["ITEMS"] : $arItemsID),
								"SAVE_IN_SESSION" => "N",
								"XML_EXPORT" => "Y",
								"SECTION_TITLE" => "NAME",
								"HIDDEN_PROP" => array("BRAND"),
								"SECTION_DESCRIPTION" => "DESCRIPTION",
								"SHOW_HINTS" => $arParams["SHOW_HINTS"],
								'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
								'CURRENCY_ID' => $arParams['CURRENCY_ID'],
								'DISPLAY_ELEMENT_COUNT' => $arParams['DISPLAY_ELEMENT_COUNT'],
								"INSTANT_RELOAD" => "Y",
								"VIEW_MODE" => strtolower($TEMPLATE_OPTIONS["TYPE_VIEW_FILTER"]["CURRENT_VALUE"]),
								"SEF_MODE" => (strlen($arResult["URL_TEMPLATES"]["smart_filter"]) ? "Y" : "N"),
								"SEF_RULE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["smart_filter"],
								"SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
								"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
							),
							$component);
						?>
					</div>
				<?endif;?>
			</div>
		<?endif;?>
	<?$this->EndViewTarget();?>
	
<?endif;?>

<?//element?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.detail",
	"brand_detail",
	Array(
		"IBLOCK_CATALOG_TYPE" => $arParams["IBLOCK_CATALOG_TYPE"],
		"IBLOCK_CATALOG_ID" => $arParams["IBLOCK_CATALOG_ID"],
		"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
		"DISPLAY_NAME" => $arParams["DISPLAY_NAME"],
		"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
		"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"FIELD_CODE" => $arParams["DETAIL_FIELD_CODE"],
		"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
		"DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
		"SECTION_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"META_KEYWORDS" => $arParams["META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["META_DESCRIPTION"],
		"BROWSER_TITLE" => $arParams["BROWSER_TITLE"],
		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"USE_FILTER" => $arParams['USE_FILTER'],
		"FILTER_NAME" => $arParams['FILTER_NAME'],
		"SHOW_404" => $arParams["SHOW_404"],
		"MESSAGE_404" => $arParams["MESSAGE_404"],
		"FILE_404" => $arParams["FILE_404"],
		"VIEW_LINKED_PRODUCTS" => ($arParams["VIEW_LINKED_PRODUCTS"] ? $arParams["VIEW_LINKED_PRODUCTS"] : "slider" ),
		"LINKED_ELEMENST_PAGE_COUNT" => ($arParams["LINKED_ELEMENST_PAGE_COUNT"] ? $arParams["LINKED_ELEMENST_PAGE_COUNT"] : 20),
		"SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
		"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
		"INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
		"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
		"ADD_ELEMENT_CHAIN" => $arParams["ADD_ELEMENT_CHAIN"],
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
		"USE_SHARE" 			=> $arParams["USE_SHARE"],
		"SHARE_HIDE" 			=> $arParams["SHARE_HIDE"],
		"SHARE_TEMPLATE" 		=> $arParams["SHARE_TEMPLATE"],
		"SHARE_HANDLERS" 		=> $arParams["SHARE_HANDLERS"],
		"SHARE_SHORTEN_URL_LOGIN"	=> $arParams["SHARE_SHORTEN_URL_LOGIN"],
		"SHARE_SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
		"CATALOG_FILTER_NAME" => $arParams["CATALOG_FILTER_NAME"],
		"IBLOCK_CATALOG_TYPE" => $arParams["IBLOCK_CATALOG_TYPE"],
		"CATALOG_IBLOCK_ID1" => $arParams["CATALOG_IBLOCK_ID1"],
		"CATALOG_IBLOCK_ID2" => $arParams["CATALOG_IBLOCK_ID2"],
		"CATALOG_IBLOCK_ID3"  => $arParams["CATALOG_IBLOCK_ID3"],
		"SHOW_BACK_LINK"  => $arParams["SHOW_BACK_LINK"],
		"GALLERY_PROPERTY" => $arParams["GALLERY_PROPERTY"],
		"SHOW_GALLERY"  => $arParams["SHOW_GALLERY"],
		"LINKED_PRODUCTS_PROPERTY"  => $arParams["LINKED_PRODUCTS_PROPERTY"],
		"SHOW_LINKED_PRODUCTS" => $arParams["SHOW_LINKED_PRODUCTS"],
		"SHOW_ITEM_SECTION" => $arParams["SHOW_ITEM_SECTION"],
		"DEPTH_LEVEL_BRAND" => $arParams["DEPTH_LEVEL_BRAND"],
		"SHOW_FILTER_LEFT" => $arParams["SHOW_FILTER_LEFT"],
		'STRICT_SECTION_CHECK' => (isset($arParams['STRICT_SECTION_CHECK']) ? $arParams['STRICT_SECTION_CHECK'] : ''),
	),
	$component
);?>

<?if ($arParams["SHOW_LINKED_PRODUCTS_SECTIONS"] == "Y" && strlen($arParams["LINKED_PRODUCTS_PROPERTY"])):?>

	<?
		$GLOBALS['arBrandSectionsFilter'] = array("ID" => $arSectionsID,);
		$brandSectionsTitle = GetMessage("BRAND_PRODUCTS_SECTION", Array ("#BRAND_NAME#" => $arElement["NAME"]));
	?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.section.list",
		"subsections_list_brand_detail",
		Array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $catalogIBlockID,
			"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"COUNT_ELEMENTS" => 'N',
			"SHOW_SECTION_LIST_PICTURES" => $arParams["SHOW_SECTION_LIST_PICTURES"],
			"TOP_DEPTH" => ($arParams["DEPTH_LEVEL_BRAND"] ? $arParams["DEPTH_LEVEL_BRAND"] : 2),
			"FILTER_NAME" => 'arBrandSectionsFilter',
			"TITLE" => $brandSectionsTitle,
			"BRAND_CODE" => $arElement['CODE'],
		),
		$component
	);?>
<?endif;?>

<?if ($arParams["SHOW_LINKED_PRODUCTS"] == "Y" && strlen($arParams["LINKED_PRODUCTS_PROPERTY"])):?>
	<?=$GLOBALS['sectionsContent']?>
	<hr class="long"/>
	<div class="similar_products_wrapp">
		<h3><?=GetMessage("BRAND_PRODUCTS", Array ("#BRAND_NAME#" => $arElement["NAME"]));?></h3>
		<?
		if($GLOBALS[$arParams["CATALOG_FILTER_NAME"]]){
			$GLOBALS[$arParams["CATALOG_FILTER_NAME"]] = array_merge($GLOBALS[$arParams["CATALOG_FILTER_NAME"]], array( "PROPERTY_".$arParams["LINKED_PRODUCTS_PROPERTY"] => $arElement["ID"] ));
		}
		else{
			$GLOBALS[$arParams["CATALOG_FILTER_NAME"]] = array( "PROPERTY_".$arParams["LINKED_PRODUCTS_PROPERTY"] => $arElement["ID"] );
		}
		$template=($arParams["VIEW_LINKED_PRODUCTS"]=="slider" ? "products_slider" : "products_block" );

		$arParams["DEPTH_LEVEL_BRAND"] = ($arParams["DEPTH_LEVEL_BRAND"] ? $arParams["DEPTH_LEVEL_BRAND"] : 3);
		$bFilter = $arParams['SHOW_LINKED_PRODUCTS'] === 'Y' && $arParams['SHOW_FILTER_LEFT'] === 'Y';
		$bAjaxFilter = ((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") && (isset($_GET["ajax_get"]) && $_GET["ajax_get"] === "Y"));
		?>
		<div class="wraps goods-block with-padding block catalog vertical">
			<div class="js_filter filter_horizontal">
				<div class="bx_filter bx_filter_vertical"></div>
			</div>

			<?if($bFilter):?>
				<div class="adaptive_filter">
					<a class="filter_opener"><i></i><span><?=GetMessage("CATALOG_SMART_FILTER_TITLE")?></span></a>
				</div>
			<?endif;?>

			<?if($bAjaxFilter):?>
				<?$APPLICATION->RestartBuffer();?>
			<?else:?>
				<div id="right_block_ajax_wrapper" class="ajax_load">
			<?endif;?>
			
			<?include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/news.detail.'.$template.'.php');?>

			<?if($bAjaxFilter):?>
				<?die();?>
			<?else:?>
				</div>
			<?endif;?>
		</div>
	</div>
<?endif;?>



<?if ($arParams["SHOW_BACK_LINK"]=="Y"):?>
	<?$refer=$_SERVER['HTTP_REFERER'];
	if (strpos($refer, $arResult["LIST_PAGE_URL"])!==false) {?>
		<div class="back"><a class="back" href="javascript:history.back();"><span><?=GetMessage("BACK");?></span></a></div>
	<?}else{?>
		<div class="back"><a class="back" href="<?=$arResult["LIST_PAGE_URL"]?>"><span><?=GetMessage("BACK");?></span></a></div>
	<?}?>
<?endif;?>
