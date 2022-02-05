<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
CModule::IncludeModule("iblock");
global $TEMPLATE_OPTIONS, $MShopSectionID;
$arParams["ADD_SECTIONS_CHAIN"] = (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : "Y");
$arParams["SHOW_DISCOUNT_TIME"] = (isset($arParams["SHOW_DISCOUNT_TIME"]) ? $arParams["SHOW_DISCOUNT_TIME"] : "Y");
$arParams["DISPLAY_WISH_BUTTONS"] = $TEMPLATE_OPTIONS['SHOW_DELAY_BUTTON']['CURRENT_VALUE'];
$bMobileCompactFilter = $TEMPLATE_OPTIONS['MOBILE_FILTER_COMPACT']['CURRENT_VALUE'] == 'Y';

// get current section ID
$arPageParams = $arSection = $section = array();
if($arResult["VARIABLES"]["SECTION_ID"] > 0){
	$section=CMshopCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMshopCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arResult["VARIABLES"]["SECTION_ID"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "NAME", "DESCRIPTION", "UF_SECTION_DESCR", $arParams["SECTION_DISPLAY_PROPERTY"], $arParams["LIST_BROWSER_TITLE"], $arParams["LIST_META_KEYWORDS"], $arParams["LIST_META_DESCRIPTION"], "IBLOCK_SECTION_ID", "UF_OFFERS_TYPE", "DEPTH_LEVEL", "LEFT_MARGIN", "RIGHT_MARGIN", "PICTURE"));
}
elseif(strlen(trim($arResult["VARIABLES"]["SECTION_CODE"])) > 0){
	$section=CMshopCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMshopCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "=CODE" => $arResult["VARIABLES"]["SECTION_CODE"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "NAME", "DESCRIPTION", "UF_SECTION_DESCR", $arParams["SECTION_DISPLAY_PROPERTY"], $arParams["LIST_BROWSER_TITLE"], $arParams["LIST_META_KEYWORDS"], $arParams["LIST_META_DESCRIPTION"], "IBLOCK_SECTION_ID", "UF_OFFERS_TYPE", "DEPTH_LEVEL", "LEFT_MARGIN", "RIGHT_MARGIN", "PICTURE"));
}

global $dopBodyClass;
if ($bTopLanding = $arParams['LANDING_POSITION'] === 'TOP') {
	$dopBodyClass = 'catalog-landing-top';
}

if($section)
{
	if(strlen($section["PICTURE"])){
		$sectionMeta = array(
			'og:image' => CFile::GetPath($section["PICTURE"]),
		);
		CMShop::AddMeta($sectionMeta);
	}

	$arSection["ID"] = $section["ID"];
	$arSection["NAME"] = $section["NAME"];
	$arSection["IBLOCK_SECTION_ID"] = $section["IBLOCK_SECTION_ID"];
	if($section[$arParams["SECTION_DISPLAY_PROPERTY"]]){
		$arDisplayRes = CUserFieldEnum::GetList(array(), array("ID" => $section[$arParams["SECTION_DISPLAY_PROPERTY"]]));
		if($arDisplay = $arDisplayRes->GetNext()){
			$arSection["DISPLAY"] = $arDisplay["XML_ID"];
		}
	}
	$arSection["SEO_DESCRIPTION"] = $section[$arParams["SECTION_PREVIEW_PROPERTY"]];
	if(strlen($section["DESCRIPTION"]))
		$arSection["DESCRIPTION"] = $section["~DESCRIPTION"];
	if(strlen($section["UF_SECTION_DESCR"]))
		$arSection["UF_SECTION_DESCR"] = $section["UF_SECTION_DESCR"];
	$APPLICATION->SetPageProperty("title", $section[$arParams["LIST_BROWSER_TITLE"]]);
	$APPLICATION->SetPageProperty("keywords", $section[$arParams["LIST_META_KEYWORDS"]]);
	$APPLICATION->SetPageProperty("description", $section[$arParams["LIST_META_DESCRIPTION"]]);

	$iSectionsCount = CMshopCache::CIBlockSection_GetCount(array('CACHE' => array("TAG" => CMshopCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array("SECTION_ID" => $arSection["ID"], "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y"));
	$posSectionDescr = COption::GetOptionString("aspro.mshop", "SHOW_SECTION_DESCRIPTION", "BOTTOM", SITE_ID);

	$MShopSectionID = $arSection["ID"];

	$landingIBlockID = ($arParams["LANDING_IBLOCK_ID"] ? $arParams["LANDING_IBLOCK_ID"] : CMshopCache::$arIBlocks[SITE_ID]["aspro_mshop_catalog"]["aspro_mshop_catalog_info"][0]);
	//seo
	$arSeoItems = CMshopCache::CIBLockElement_GetList(array('CACHE' => array("MULTI" =>"Y", "TAG" => CMshopCache::GetIBlockCacheTag($landingIBlockID))), array("IBLOCK_ID" => $landingIBlockID, "ACTIVE"=>"Y"), false, false, array("ID", "IBLOCK_ID", "NAME", "PREVIEW_TEXT", "DETAIL_PICTURE", "PROPERTY_FILTER_URL", "PROPERTY_FORM_QUESTION", "PROPERTY_TIZERS", "PROPERTY_SECTION", "DETAIL_TEXT", "ElementValues"));
	$arSeoItem = array();
	if($arSeoItems)
	{
		$current_url =  $APPLICATION->GetCurDir();
		$url = urldecode(str_replace(' ', '+', $current_url));
		foreach($arSeoItems as $arItem)
		{
			if(urldecode($arItem["PROPERTY_FILTER_URL_VALUE"]) == $url)
			{
				$arSeoItem = $arItem;
				break;
			}
		}
	}


	//set offer view type
	$typeSKU = '';
	$typeTmpSKU = 0;
	if($section['UF_OFFERS_TYPE'])
		$typeTmpSKU = $section['UF_OFFERS_TYPE'];
	else
	{
		if($section["DEPTH_LEVEL"] > 2)
		{
			$sectionParent = CMshopCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMshopCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "ID" => $section["IBLOCK_SECTION_ID"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "NAME", "UF_OFFERS_TYPE"));
			if($sectionParent['UF_OFFERS_TYPE'] && !$typeTmpSKU)
				$typeTmpSKU = $sectionParent['UF_OFFERS_TYPE'];

			if(!$typeTmpSKU)
			{
				$sectionRoot = CMshopCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMshopCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "<=LEFT_BORDER" => $section["LEFT_MARGIN"], ">=RIGHT_BORDER" => $section["RIGHT_MARGIN"], "DEPTH_LEVEL" => 1, "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "NAME", "UF_OFFERS_TYPE"));
				if($sectionRoot['UF_OFFERS_TYPE'] && !$typeTmpSKU)
					$typeTmpSKU = $sectionRoot['UF_OFFERS_TYPE'];
			}
		}
		else
		{
			$sectionRoot = CMshopCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMshopCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "<=LEFT_BORDER" => $section["LEFT_MARGIN"], ">=RIGHT_BORDER" => $section["RIGHT_MARGIN"], "DEPTH_LEVEL" => 1, "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "NAME", "UF_OFFERS_TYPE"));
			if($sectionRoot['UF_OFFERS_TYPE'] && !$typeTmpSKU)
				$typeTmpSKU = $sectionRoot['UF_OFFERS_TYPE'];
		}
	}
	if($typeTmpSKU)
	{
		$rsTypes = CUserFieldEnum::GetList(array(), array("ID" => $typeTmpSKU));
		if($arType = $rsTypes->GetNext())
		{
			$typeSKU = $arType['XML_ID'];
			$TEMPLATE_OPTIONS["TYPE_SKU"]["CURRENT_VALUE"] = $typeSKU;
		}

	}

	?>

	<?//if($iSectionsCount > 0):?>
		<?$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arParams["IBLOCK_ID"], IntVal($arSection["ID"]));
		$values = $ipropValues->getValues();
		$ishop_page_title = $values['SECTION_META_TITLE'] ? $values['SECTION_META_TITLE'] : $arSection["NAME"];
		$ishop_page_h1 = $values['SECTION_PAGE_TITLE'] ? $values['SECTION_PAGE_TITLE'] : $arSection["NAME"];
		if($ishop_page_h1){
			$APPLICATION->SetTitle($ishop_page_h1);
		}
		else{
			$APPLICATION->SetTitle($arSection["NAME"]);
		}
		if($ishop_page_title){
			$APPLICATION->SetPageProperty("title", $ishop_page_title);
		}
		if($values['SECTION_META_DESCRIPTION']){
			$APPLICATION->SetPageProperty("description", $values['SECTION_META_DESCRIPTION']);
		}
		if($values['SECTION_META_KEYWORDS']){
			$APPLICATION->SetPageProperty("keywords", $values['SECTION_META_KEYWORDS']);
		}?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:catalog.section.list",
			"subsections_list",
			Array(
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
				"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
				"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
				"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
				"ADD_SECTIONS_CHAIN" => ((!$iSectionsCount || $arParams['INCLUDE_SUBSECTIONS'] !== "N") ? 'N' : 'Y'),
				"SHOW_SECTION_LIST_PICTURES" => $arParams["SHOW_SECTION_LIST_PICTURES"],
				"SHOW_SECTION_DESC" => $arParams["SHOW_SECTION_DESC"],
				"SECTIONS_LIST_PREVIEW_PROPERTY" => $arParams["SECTIONS_LIST_PREVIEW_PROPERTY"],
				"TOP_DEPTH" => (($arParams["SECTION_TOP_DEPTH"] && $arParams["SECTION_TOP_DEPTH"]<=2) ? $arParams["SECTION_TOP_DEPTH"] : 2),
			),
			$component
		);?>
	<?//endif?>
	<?$isAjax="N";?>
	<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest"  && isset($_GET["ajax_get"]) && $_GET["ajax_get"] == "Y" || (isset($_GET["ajax_basket"]) && $_GET["ajax_basket"]=="Y")){
		$isAjax="Y";
	}?>
	<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest" && isset($_GET["ajax_get_filter"]) && $_GET["ajax_get_filter"] == "Y" ){
		$isAjaxFilter="Y";
	}
	if($isAjaxFilter == "Y")
		$isAjax="N";
	?>
	<?if($arParams['INCLUDE_SUBSECTIONS'] !== "N" || !$iSectionsCount):?>
		<?if($isAjaxFilter != "Y"):?>
		<div class="left_block catalog <?=strtolower($TEMPLATE_OPTIONS["TYPE_VIEW_FILTER"]["CURRENT_VALUE"])?>">

			<?if($TEMPLATE_OPTIONS["TYPE_VIEW_FILTER"]["CURRENT_VALUE"]=="VERTICAL"){?>
				<?include_once("filter.php")?>
			<?}?>

			<?if($arParams["SHOW_SECTION_SIBLINGS"] == "Y"):?>
				<?$APPLICATION->IncludeComponent(
					"bitrix:catalog.section.list",
					"internal_sections_list",
					Array(
						"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],
						"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
						"CACHE_TYPE" => $arParams["CACHE_TYPE"],
						"CACHE_TIME" => $arParams["CACHE_TIME"],
						"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
						"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
						"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
						"ADD_SECTIONS_CHAIN" => "N",
						"TOP_DEPTH" => "3",
					),$component
				);?>
			<?endif;?>

		</div>
		<div class="right_block clearfix catalog" id="right_block_ajax">
		<?else:?>
			<?if($TEMPLATE_OPTIONS["TYPE_VIEW_FILTER"]["CURRENT_VALUE"]=="VERTICAL"){?>
				<?include_once("filter.php")?>
			<?}?>
			<?$APPLICATION->RestartBuffer();?>
		<?endif;?>

			<?if($TEMPLATE_OPTIONS["TYPE_VIEW_FILTER"]["CURRENT_VALUE"]=="HORIZONTAL"){?>
				<div class="filter_horizontal">
					<?include_once("filter.php")?>
				</div>
			<?}?>
			<div class="inner_wrapper">
				<?if($arSeoItem):?>
					<div class="seo_block">
						<?if($arSeoItem["DETAIL_PICTURE"]):?>
							<img src="<?=CFile::GetPath($arSeoItem["DETAIL_PICTURE"]);?>" alt="<?=$arSeoItem["NAME"]?>" title="<?=$arSeoItem["NAME"]?>" class="img-responsive"/>
						<?endif;?>
						<?if($arSeoItem["PREVIEW_TEXT"]):?>
							<?=$arSeoItem["PREVIEW_TEXT"]?>
						<?endif;?>
						<?if($arSeoItem["PROPERTY_FORM_QUESTION_VALUE"]):?>
							<table class="order-block noicons">
								<tbody>
									<tr>
										<td class="text-block">
											<div class="text">
												<?$APPLICATION->IncludeComponent(
													 'bitrix:main.include',
													 '',
													 Array(
														  'AREA_FILE_SHOW' => 'page',
														  'AREA_FILE_SUFFIX' => 'ask',
														  'EDIT_TEMPLATE' => ''
													 )
												);?>
											</div>
										</td>
										<td class="btns-block">
											<div class="btns">
												<span><span class="button transparent animate-load ask_btn" data-event="jqm" data-param-form_id="ASK" data-name="question"><span><?=(strlen($arParams['S_ASK_QUESTION']) ? $arParams['S_ASK_QUESTION'] : GetMessage('S_ASK_QUESTION'))?></span></span></span>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						<?endif;?>
						<?if($arSeoItem["PROPERTY_TIZERS_VALUE"]):?>
							<?$GLOBALS["arLandingTizers"] = array("ID" => $arSeoItem["PROPERTY_TIZERS_VALUE"]);?>
							<?$APPLICATION->IncludeComponent(
								"bitrix:news.list",
								"mshop",
								array(
									"IBLOCK_TYPE" => "aspro_mshop_content",
									"IBLOCK_ID" => CMshopCache::$arIBlocks[SITE_ID]["aspro_mshop_content"]["aspro_mshop_tizers"][0],
									"NEWS_COUNT" => "4",
									"SORT_BY1" => "SORT",
									"SORT_ORDER1" => "ASC",
									"SORT_BY2" => "ID",
									"SORT_ORDER2" => "DESC",
									"FILTER_NAME" => "arLandingTizers",
									"FIELD_CODE" => array(
										0 => "",
										1 => "",
									),
									"PROPERTY_CODE" => array(
										0 => "LINK",
										1 => "",
									),
									"CHECK_DATES" => "Y",
									"DETAIL_URL" => "",
									"AJAX_MODE" => "N",
									"AJAX_OPTION_JUMP" => "N",
									"AJAX_OPTION_STYLE" => "Y",
									"AJAX_OPTION_HISTORY" => "N",
									"CACHE_TYPE" =>$arParams["CACHE_TYPE"],
									"CACHE_TIME" => $arParams["CACHE_TIME"],
									"CACHE_FILTER" => "Y",
									"CACHE_GROUPS" => "N",
									"PREVIEW_TRUNCATE_LEN" => "",
									"ACTIVE_DATE_FORMAT" => "j F Y",
									"SET_TITLE" => "N",
									"SET_STATUS_404" => "N",
									"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
									"ADD_SECTIONS_CHAIN" => "N",
									"HIDE_LINK_WHEN_NO_DETAIL" => "N",
									"PARENT_SECTION" => "",
									"PARENT_SECTION_CODE" => "",
									"INCLUDE_SUBSECTIONS" => "Y",
									"PAGER_TEMPLATE" => "",
									"DISPLAY_TOP_PAGER" => "N",
									"DISPLAY_BOTTOM_PAGER" => "N",
									"PAGER_TITLE" => "",
									"PAGER_SHOW_ALWAYS" => "N",
									"PAGER_DESC_NUMBERING" => "N",
									"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
									"PAGER_SHOW_ALL" => "N",
									"AJAX_OPTION_ADDITIONAL" => "",
									"COMPONENT_TEMPLATE" => "next",
									"SET_BROWSER_TITLE" => "N",
									"SET_META_KEYWORDS" => "N",
									"SET_META_DESCRIPTION" => "N",
									"SET_LAST_MODIFIED" => "N",
									"PAGER_BASE_LINK_ENABLE" => "N",
									"SHOW_404" => "N",
									"MESSAGE_404" => ""
								),
								false, array("HIDE_ICONS" => "Y")
							);?>
						<?endif;?>
					</div>
				<?endif;?>

				<?if(strpos($_SERVER['REQUEST_URI'], 'PAGEN') === false):?>
					<?if(!$arSeoItem):?>
						<?if($posSectionDescr=="TOP"){?>
							<?if ($arSection["SEO_DESCRIPTION"]):?>
								<div class="group_description_block top">
									<div><?=$arSection["SEO_DESCRIPTION"]?></div>
								</div>
							<?elseif($arSection["DESCRIPTION"]):?>
								<div class="group_description_block top">
									<div><?=$arSection["DESCRIPTION"]?></div>
								</div>
							<?elseif($arSection["UF_SECTION_DESCR"]):?>
								<div class="group_description_block top">
									<div><?=$arSection["UF_SECTION_DESCR"]?></div>
								</div>
							<?endif;?>
						<?}?>
					<?endif;?>
				<?endif;?>

				<?ob_start()?>
					<?if($arSeoItems):?>
						<?$arLandingFilter = array();
						if($arSeoItem)
						{
							$arLandingFilter = array("PROPERTY_SECTION" => $arSeoItem["PROPERTY_SECTION_VALUE"], "!ID" => $arSeoItem["ID"]);
						}
						else
						{
							$arLandingFilter = array("PROPERTY_SECTION" => $arSection["ID"]);
						}
						?>
						<?$GLOBALS["arLandingSections"] = $arLandingFilter;?>
						<?$APPLICATION->IncludeComponent(
							"bitrix:news.list",
							"landings_list",
							array(
								"IBLOCK_TYPE" => "aspro_optimus_catalog",
								"IBLOCK_ID" => $landingIBlockID,
								"NEWS_COUNT" => ($arParams["LANDING_PAGE_ELEMENT_COUNT"] ? $arParams["LANDING_PAGE_ELEMENT_COUNT"] : "20"),
								"SHOW_COUNT" => ($arParams["LANDING_SECTION_COUNT"] ? $arParams["LANDING_SECTION_COUNT"] : 8),
								"COMPARE_FIELD" => "FILTER_URL",
								"COMPARE_PROP" => "Y",
								"SORT_BY1" => "SORT",
								"SORT_ORDER1" => "ASC",
								"SORT_BY2" => "ID",
								"SORT_ORDER2" => "DESC",
								"FILTER_NAME" => "arLandingSections",
								"FIELD_CODE" => array(
									0 => "",
									1 => "",
								),
								"PROPERTY_CODE" => array(
									0 => "LINK",
									1 => "",
								),
								"CHECK_DATES" => "Y",
								"DETAIL_URL" => "",
								"AJAX_MODE" => "N",
								"AJAX_OPTION_JUMP" => "N",
								"AJAX_OPTION_STYLE" => "Y",
								"AJAX_OPTION_HISTORY" => "N",
								"CACHE_TYPE" =>$arParams["CACHE_TYPE"],
								"CACHE_TIME" => $arParams["CACHE_TIME"],
								"CACHE_FILTER" => "Y",
								"CACHE_GROUPS" => "N",
								"PREVIEW_TRUNCATE_LEN" => "",
								"ACTIVE_DATE_FORMAT" => "j F Y",
								"SET_TITLE" => "N",
								"SET_STATUS_404" => "N",
								"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
								"ADD_SECTIONS_CHAIN" => "N",
								"HIDE_LINK_WHEN_NO_DETAIL" => "N",
								"PARENT_SECTION" => "",
								"PARENT_SECTION_CODE" => "",
								"INCLUDE_SUBSECTIONS" => "Y",
								"PAGER_TEMPLATE" => "",
								"DISPLAY_TOP_PAGER" => "N",
								"DISPLAY_BOTTOM_PAGER" => "N",
								"PAGER_TITLE" => "",
								"PAGER_SHOW_ALWAYS" => "N",
								"PAGER_DESC_NUMBERING" => "N",
								"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
								"PAGER_SHOW_ALL" => "N",
								"AJAX_OPTION_ADDITIONAL" => "",
								"COMPONENT_TEMPLATE" => "next",
								"SET_BROWSER_TITLE" => "N",
								"SET_META_KEYWORDS" => "N",
								"SET_META_DESCRIPTION" => "N",
								"SET_LAST_MODIFIED" => "N",
								"PAGER_BASE_LINK_ENABLE" => "N",
								"TITLE_BLOCK" => ($arParams["LANDING_TITLE"] ? $arParams["LANDING_TITLE"] : GetMessage("POPULAR_CATEGORYS")),
								"SHOW_404" => "N",
								"MESSAGE_404" => ""
							),
							false, array("HIDE_ICONS" => "Y")
						);?>
					<?endif;?>
				<?$html = ob_get_clean()?>
				
				<?if ($bTopLanding):?>
					<div class="group_description_block top landings-block">
						<?=$html;?>
					</div>
				<?endif;?>

				<?if('Y' == $arParams['USE_FILTER']):?>
					<div class="adaptive_filter <?=$bMobileCompactFilter ? 'compact_view' : ''?>">
						<a class="filter_opener<?=($_REQUEST["set_filter"] == "y" ? " active" : "")?>"><i></i><span><?=GetMessage("CATALOG_SMART_FILTER_TITLE")?></span></a>
					</div>
				<?endif;?>

				<?if($isAjax=="N"){
					$frame = new \Bitrix\Main\Page\FrameHelper("viewtype-block");
					$frame->begin();
					//$frame->SetAnimation(true);?>
				<?}
				$arDisplays = array("block", "list", "table");
				if(array_key_exists("display", $_REQUEST) || (array_key_exists("display", $_SESSION)) || $arParams["DEFAULT_LIST_TEMPLATE"]){
					if($_REQUEST["display"] && (in_array(trim($_REQUEST["display"]), $arDisplays))){
						$display = trim($_REQUEST["display"]);
						$_SESSION["display"]=trim($_REQUEST["display"]);
					}
					elseif($_SESSION["display"] && (in_array(trim($_SESSION["display"]), $arDisplays))){
						$display = $_SESSION["display"];
					}
					elseif($arSection["DISPLAY"]){
						$display = $arSection["DISPLAY"];
					}
					else{
						$display = $arParams["DEFAULT_LIST_TEMPLATE"];
					}
				}
				else{
					$display = "block";
				}
				$template = "catalog_".$display;
				?>

				<div class="sort_header view_<?=$display?>">
					<!--noindex-->
						<div class="sort_filter <?=$bMobileCompactFilter ? 'compact_mobile_filter' : ''?>">
							<?
							$arAvailableSort = array();
							$arSorts = $arParams["SORT_BUTTONS"];
							if(in_array("POPULARITY", $arSorts)){
								$arAvailableSort["SHOWS"] = array("SHOWS", "desc");
							}
							if(in_array("NAME", $arSorts)){
								$arAvailableSort["NAME"] = array("NAME", "asc");
							}
							if(in_array("PRICE", $arSorts)){
								$arSortPrices = $arParams["SORT_PRICES"];
								if($arSortPrices == "MINIMUM_PRICE" || $arSortPrices == "MAXIMUM_PRICE"){
									$arAvailableSort["PRICE"] = array("PROPERTY_".$arSortPrices, "desc");
								}
								else{
									$price = CCatalogGroup::GetList(array(), array("NAME" => $arParams["SORT_PRICES"]), false, false, array("ID", "NAME"))->GetNext();
									$arAvailableSort["PRICE"] = array("CATALOG_PRICE_".$price["ID"], "desc");
								}
							}
							if(in_array("QUANTITY", $arSorts)){
								$arAvailableSort["CATALOG_AVAILABLE"] = array("QUANTITY", "desc");
							}
							$sort = "SHOWS";
							if((array_key_exists("sort", $_REQUEST) && array_key_exists(ToUpper($_REQUEST["sort"]), $arAvailableSort)) || (array_key_exists("sort", $_SESSION) && array_key_exists(ToUpper($_SESSION["sort"]), $arAvailableSort)) || $arParams["ELEMENT_SORT_FIELD"]){
								if($_REQUEST["sort"]){
									$sort = ToUpper($_REQUEST["sort"]);
									$_SESSION["sort"] = ToUpper($_REQUEST["sort"]);
								}
								elseif($_SESSION["sort"]){
									$sort = ToUpper($_SESSION["sort"]);
								}
								else{
									$sort = ToUpper($arParams["ELEMENT_SORT_FIELD"]);
								}
							}

							$sort_order=$arAvailableSort[$sort][1];
							if((array_key_exists("order", $_REQUEST) && in_array(ToLower($_REQUEST["order"]), Array("asc", "desc"))) || (array_key_exists("order", $_REQUEST) && in_array(ToLower($_REQUEST["order"]), Array("asc", "desc")) ) || $arParams["ELEMENT_SORT_ORDER"]){
								if($_REQUEST["order"]){
									$sort_order = htmlspecialcharsbx($_REQUEST["order"]);
									$_SESSION["order"] = htmlspecialcharsbx($_REQUEST["order"]);
								}
								elseif($_SESSION["order"]){
									$sort_order = $_SESSION["order"];
								}
								else{
									$sort_order = ToLower($arParams["ELEMENT_SORT_ORDER"]);
								}
							}
							?>
							<?foreach($arAvailableSort as $key => $val):?>
								<?$newSort = $sort_order == 'desc' ? 'asc' : 'desc';
								$current_url = $APPLICATION->GetCurPageParam('sort='.$key.'&order='.$newSort, 	array('sort', 'order'));
								$url = str_replace('+', '%2B', $current_url);?>
								<a rel="nofollow" href="<?=$url;?>" class="sort_btn <?=($sort == $key ? 'current' : '')?> <?=$sort_order?> <?=$key?>">
									<i class="icon" title="<?=GetMessage('SECT_SORT_'.$key)?>"></i><span><?=GetMessage('SECT_SORT_'.$key)?></span><i class="arr"></i>
								</a>
							<?endforeach;?>
							<?
							if($sort == "PRICE"){
								$sort = $arAvailableSort["PRICE"][0];
							}
							if($sort == "CATALOG_AVAILABLE"){
								$sort = "CATALOG_QUANTITY";
							}
							?>
						</div>
						<div class="sort_display">
							<?foreach($arDisplays as $displayType):?>
								<?
								$current_url = '';
								$current_url = $APPLICATION->GetCurPageParam('display='.$displayType, 	array('display'));
								$url = str_replace('+', '%2B', $current_url);
								?>
								<a rel="nofollow" href="<?=$url;?>" class="sort_btn <?=$displayType?> <?=($display == $displayType ? 'current' : '')?>"><i title="<?=GetMessage("SECT_DISPLAY_".strtoupper($displayType))?>"></i></a>
							<?endforeach;?>
						</div>
					<!--/noindex-->
				</div>

				<?if($TEMPLATE_OPTIONS["TYPE_VIEW_FILTER"]["CURRENT_VALUE"]=="COMPACT"){?>
					<div class="filter-compact-block">
						<?include_once("filter.php")?>
					</div>
				<?}?>

				<?if($isAjax=="Y"){
					$APPLICATION->RestartBuffer();
				}?>
				<?
				$show = $arParams["PAGE_ELEMENT_COUNT"];
				/*if(array_key_exists("show", $_REQUEST)){
					if(intVal($_REQUEST["show"]) && in_array(intVal($_REQUEST["show"]), array(20, 40, 60, 80, 100))){
						$show = intVal($_REQUEST["show"]); $_SESSION["show"] = $show;
					}
					elseif($_SESSION["show"]){
						$show=intVal($_SESSION["show"]);
					}
				}*/
				?>
				<?/*$frame = new \Bitrix\Main\Page\FrameHelper("banner-block");
				$frame->begin('');
					global $arBasketItems;
				$frame->end();*/?>
				<?if($isAjax=="N"){?>
					<div class="ajax_load <?=$display;?>">
				<?}?>
					<?$APPLICATION->IncludeComponent(
						"bitrix:catalog.section",
						$template,
						Array(
							"SEF_URL_TEMPLATES" => $arParams["SEF_URL_TEMPLATES"],
							"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
							"IBLOCK_ID" => $arParams["IBLOCK_ID"],
							"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
							"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
							// "BASKET_ITEMS" => $arBasketItems,
							"ELEMENT_SORT_FIELD" => $sort,
							"AJAX_REQUEST" => $isAjax,
							// "AJAX_REQUEST_FILTER" => $isAjaxFilter,
							"SHOW_COUNTER_LIST" => $arParams["SHOW_COUNTER_LIST"],
							"SHOW_DISCOUNT_TIME" => $arParams["SHOW_DISCOUNT_TIME"],
							"SHOW_DISCOUNT_TIME_EACH_SKU" => $arParams["SHOW_DISCOUNT_TIME_EACH_SKU"],
							"ELEMENT_SORT_ORDER" => $sort_order,
							"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
							"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
							"FILTER_NAME" => $arParams["FILTER_NAME"],
							"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
							"SALE_STIKER" => ($arParams["SALE_STIKER"] ? $arParams["SALE_STIKER"] : "SALE_TEXT"),
							"PAGE_ELEMENT_COUNT" => $show,
							"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
							"DISPLAY_TYPE" => $display,
							"TYPE_SKU" => $TEMPLATE_OPTIONS["TYPE_SKU"]["CURRENT_VALUE"],
							"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
							"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
							"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
							"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
							"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
							"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
							"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
							'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
							"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
							"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
							"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
							"BASKET_URL" => $arParams["BASKET_URL"],
							"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
							"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
							"PRODUCT_QUANTITY_VARIABLE" => "quantity",
							"PRODUCT_PROPS_VARIABLE" => "prop",
							"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
							"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
							"AJAX_MODE" => $arParams["AJAX_MODE"],
							"AJAX_OPTION_JUMP" => $arParams["AJAX_OPTION_JUMP"],
							"AJAX_OPTION_STYLE" => $arParams["AJAX_OPTION_STYLE"],
							"AJAX_OPTION_HISTORY" => $arParams["AJAX_OPTION_HISTORY"],
							"CACHE_TYPE" =>$arParams["CACHE_TYPE"],
							"CACHE_TIME" => $arParams["CACHE_TIME"],
							"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
							"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
							"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
							"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
							"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
							"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
							'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
							"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
							"SET_TITLE" => $arParams["SET_TITLE"],
							"SET_STATUS_404" => $arParams["SET_STATUS_404"],
							"SHOW_404" => $arParams["SHOW_404"],
							"MESSAGE_404" => $arParams["MESSAGE_404"],
							"FILE_404" => $arParams["FILE_404"],
							"CACHE_FILTER" => $arParams["CACHE_FILTER"],
							"PRICE_CODE" => $arParams["PRICE_CODE"],
							"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
							"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
							"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
							"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
							"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
							"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
							"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
							"PAGER_TITLE" => $arParams["PAGER_TITLE"],
							"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
							"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
							"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
							"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
							"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
							"AJAX_OPTION_ADDITIONAL" => "",
							"ADD_CHAIN_ITEM" => "N",
							"SHOW_QUANTITY" => $arParams["SHOW_QUANTITY"],
							"SHOW_QUANTITY_COUNT" => $arParams["SHOW_QUANTITY_COUNT"],
							"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
							"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
							"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
							"CURRENCY_ID" => $arParams["CURRENCY_ID"],
							"USE_STORE" => $arParams["USE_STORE"],
							"MAX_AMOUNT" => $arParams["MAX_AMOUNT"],
							"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
							"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
							"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
							"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
							"LIST_DISPLAY_POPUP_IMAGE" => $arParams["LIST_DISPLAY_POPUP_IMAGE"],
							"DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
							"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
							"SHOW_HINTS" => $arParams["SHOW_HINTS"],
							"OFFER_HIDE_NAME_PROPS" => $arParams["OFFER_HIDE_NAME_PROPS"],
							"SHOW_SECTIONS_LIST_PREVIEW" => $arParams["SHOW_SECTIONS_LIST_PREVIEW"],
							"SECTIONS_LIST_PREVIEW_PROPERTY" => $arParams["SECTIONS_LIST_PREVIEW_PROPERTY"],
							"SHOW_SECTION_LIST_PICTURES" => $arParams["SHOW_SECTION_LIST_PICTURES"],
							"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
							"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
							"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
							"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
						), $component, array("HIDE_ICONS" => $isAjax)
					);?>
				<?if($isAjax=="N"){?>
					<?if(strpos($_SERVER['REQUEST_URI'], 'PAGEN') === false):?>
						<?if(!$arSeoItem):?>
							<?if($posSectionDescr=="BOTTOM"){?>
								<?if ($arSection["SEO_DESCRIPTION"]):?>
									<div class="group_description_block bottom">
										<div><?=$arSection["SEO_DESCRIPTION"]?></div>
									</div>
								<?elseif($arSection["DESCRIPTION"]):?>
									<div class="group_description_block bottom">
										<div><?=$arSection["DESCRIPTION"]?></div>
									</div>
								<?elseif($arSection["UF_SECTION_DESCR"]):?>
									<div class="group_description_block bottom">
										<div><?=$arSection["UF_SECTION_DESCR"]?></div>
									</div>
								<?endif;?>
							<?}?>
						<?else:?>
							<?if($arSeoItem["DETAIL_TEXT"]):?>
								<div class="group_description_block bottom">
									<?=$arSeoItem["DETAIL_TEXT"];?>
								</div>
							<?endif;?>
						<?endif;?>
					<?endif;?>
					<?if (!$bTopLanding):?>
						<?=$html;?>
					<?endif;?>
					<?/*if($arSeoItems):?>
						<?$arLandingFilter = array();
						if($arSeoItem)
						{
							$arLandingFilter = array("PROPERTY_SECTION" => $arSeoItem["PROPERTY_SECTION_VALUE"], "!ID" => $arSeoItem["ID"]);
						}
						else
						{
							$arLandingFilter = array("PROPERTY_SECTION" => $arSection["ID"]);
						}
						?>
						<?$GLOBALS["arLandingSections"] = $arLandingFilter;?>
						<?$APPLICATION->IncludeComponent(
							"bitrix:news.list",
							"landings_list",
							array(
								"IBLOCK_TYPE" => "aspro_optimus_catalog",
								"IBLOCK_ID" => $landingIBlockID,
								"NEWS_COUNT" => ($arParams["LANDING_PAGE_ELEMENT_COUNT"] ? $arParams["LANDING_PAGE_ELEMENT_COUNT"] : "20"),
								"SHOW_COUNT" => ($arParams["LANDING_SECTION_COUNT"] ? $arParams["LANDING_SECTION_COUNT"] : 8),
								"COMPARE_FIELD" => "FILTER_URL",
								"COMPARE_PROP" => "Y",
								"SORT_BY1" => "SORT",
								"SORT_ORDER1" => "ASC",
								"SORT_BY2" => "ID",
								"SORT_ORDER2" => "DESC",
								"FILTER_NAME" => "arLandingSections",
								"FIELD_CODE" => array(
									0 => "",
									1 => "",
								),
								"PROPERTY_CODE" => array(
									0 => "LINK",
									1 => "",
								),
								"CHECK_DATES" => "Y",
								"DETAIL_URL" => "",
								"AJAX_MODE" => "N",
								"AJAX_OPTION_JUMP" => "N",
								"AJAX_OPTION_STYLE" => "Y",
								"AJAX_OPTION_HISTORY" => "N",
								"CACHE_TYPE" =>$arParams["CACHE_TYPE"],
								"CACHE_TIME" => $arParams["CACHE_TIME"],
								"CACHE_FILTER" => "Y",
								"CACHE_GROUPS" => "N",
								"PREVIEW_TRUNCATE_LEN" => "",
								"ACTIVE_DATE_FORMAT" => "j F Y",
								"SET_TITLE" => "N",
								"SET_STATUS_404" => "N",
								"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
								"ADD_SECTIONS_CHAIN" => "N",
								"HIDE_LINK_WHEN_NO_DETAIL" => "N",
								"PARENT_SECTION" => "",
								"PARENT_SECTION_CODE" => "",
								"INCLUDE_SUBSECTIONS" => "Y",
								"PAGER_TEMPLATE" => "",
								"DISPLAY_TOP_PAGER" => "N",
								"DISPLAY_BOTTOM_PAGER" => "N",
								"PAGER_TITLE" => "",
								"PAGER_SHOW_ALWAYS" => "N",
								"PAGER_DESC_NUMBERING" => "N",
								"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
								"PAGER_SHOW_ALL" => "N",
								"AJAX_OPTION_ADDITIONAL" => "",
								"COMPONENT_TEMPLATE" => "next",
								"SET_BROWSER_TITLE" => "N",
								"SET_META_KEYWORDS" => "N",
								"SET_META_DESCRIPTION" => "N",
								"SET_LAST_MODIFIED" => "N",
								"PAGER_BASE_LINK_ENABLE" => "N",
								"TITLE_BLOCK" => ($arParams["LANDING_TITLE"] ? $arParams["LANDING_TITLE"] : GetMessage("POPULAR_CATEGORYS")),
								"SHOW_404" => "N",
								"MESSAGE_404" => ""
							),
							false, array("HIDE_ICONS" => "Y")
						);?>
					<?endif;*/?>
					<div class="clear"></div>
					</div>
				<?}?>
				<?if($isAjax=="Y") {
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.plugin.min.js',true);
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.countdown.min.js',true);
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.countdown-ru.js',true);
					//die();
				}?>
				<?if($isAjax!="Y"){?>
					<?$frame->end();?>
				<?}?>
				<?if($isAjaxFilter):?>
					<?
					if($arSeoItem)
					{
						$langing_seo_h1 = ($arSeoItem["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != "" ? $arSeoItem["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] : $arSeoItem["NAME"]);

						$APPLICATION->SetTitle($langing_seo_h1);

						if($arSeoItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])
							$APPLICATION->SetPageProperty("title", $arSeoItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]);
						else
							$APPLICATION->SetPageProperty("title", $arSeoItem["NAME"].$postfix);

						if($arSeoItem["IPROPERTY_VALUES"]["ELEMENT_META_DESCRIPTION"])
							$APPLICATION->SetPageProperty("description", $arSeoItem["IPROPERTY_VALUES"]["ELEMENT_META_DESCRIPTION"]);

						if($arSeoItem["IPROPERTY_VALUES"]['ELEMENT_META_KEYWORDS'])
							$APPLICATION->SetPageProperty("keywords", $arSeoItem["IPROPERTY_VALUES"]['ELEMENT_META_KEYWORDS']);
						?>
					<?}?>
					<?global $APPLICATION;?>
					<?global $arSite;
					$postfix = "";

					$bBitrixAjax = (strpos($_SERVER["QUERY_STRING"], "bxajaxid") !== false);
					if(\Bitrix\Main\Config\Option::get("aspro.mshop", "HIDE_SITE_NAME_TITLE", "N") == "N" && ($bBitrixAjax || $isAjaxFilter))
					{
						$postfix = " - ".$arSite["NAME"];
					}?>
					<?$arAdditionalData['TITLE'] = htmlspecialcharsback($APPLICATION->GetTitle());
					$arAdditionalData['WINDOW_TITLE'] = htmlspecialcharsback($APPLICATION->GetTitle('title').$postfix);?>
					<script type="text/javascript">
						BX.removeCustomEvent("onAjaxSuccessFilter", function tt(e){});
						BX.addCustomEvent("onAjaxSuccessFilter", function tt(e){
							var arAjaxPageData = <?=CUtil::PhpToJSObject($arAdditionalData);?>;
							if (arAjaxPageData.TITLE)
								BX.ajax.UpdatePageTitle(arAjaxPageData.TITLE);
							if (arAjaxPageData.WINDOW_TITLE || arAjaxPageData.TITLE)
								BX.ajax.UpdateWindowTitle(arAjaxPageData.WINDOW_TITLE || arAjaxPageData.TITLE);
						});
					</script>
				<?endif;?>
				<?if($isAjax=="Y" || ($isAjaxFilter == "Y")){
					die();
				}?>
			</div>
		<?if($isAjaxFilter != "Y"):?>
		</div>
		<?endif;?>
	<?endif;?>
	<?
	$basketAction='';
	if($arParams["INCLUDE_SUBSECTIONS"]!="N"){
		if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] == 'Y'){
			$basketAction = (isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? $arParams['COMMON_ADD_TO_BASKET_ACTION'] : '');
		}else{
			$basketAction = (isset($arParams['TOP_ADD_TO_BASKET_ACTION']) ? $arParams['TOP_ADD_TO_BASKET_ACTION'] : '');
		}
	}

	$arTab = array();
	$IsViewedTypeLocal = $TEMPLATE_OPTIONS['VIEWED_TYPE']['CURRENT_VALUE'] === 'LOCAL';
	$arViewedIDs = CMShop::getViewedProducts();
	if($arViewedIDs){
		$arTab["VIEWED"] = GetMessage('VIEWED_TITLE');
	}
	if($arParams['INCLUDE_SUBSECTIONS'] !="N"){
		$arTab["BEST"]=GetMessage('BEST_TITLE');
	}
	if($arTab){
		$class_block="s_".$this->randString();?>
		<div class="tab_slider_wrapp <?=$class_block;?> best_block">
			<div class="top_blocks">
				<ul class="tabs">
					<?$i=1;
					foreach($arTab as $code=>$title):?>
						<li data-code="<?=$code?>" <?=($i==1 ? "class='cur'" : "")?>><span><?=$title;?></span></li>
						<?$i++;?>
					<?endforeach;?>
					<li class="stretch"></li>
				</ul>
				<ul class="slider_navigation top">
					<?$i=1;
					foreach($arTab as $code=>$title):?>
						<li class="tabs_slider_navigation <?=$code?>_nav <?=($i==1 ? "cur" : "")?>" data-code="<?=$code?>"></li>
						<?$i++;?>
					<?endforeach;?>
				</ul>
			</div>
			<ul class="tabs_content">
				<?foreach($arTab as $code=>$title){?>
					<li class="tab <?=$code?>_wrapp" data-code="<?=$code?>">
						<ul class="tabs_slider <?=$code?>_slides wr">
							<?
							if($code == 'BEST'){
								$GLOBALS[$arParams['FILTER_NAME']] = array('!PROPERTY_HIT' => false);
								if($arParams['TOP_SECTION_ID']){
									$GLOBALS[$arParams['FILTER_NAME']]['SECTION_ID'] = $arParams['TOP_SECTION_ID'];
									$GLOBALS[$arParams['FILTER_NAME']]['INCLUDE_SUBSECTIONS'] = 'Y';
								}
							}
							else{
								if(!$IsViewedTypeLocal){
									$GLOBALS[$arParams['FILTER_NAME']] = array('ID' => $arViewedIDs);
								}
							}
							?>
							<?if($code == 'BEST' || !$IsViewedTypeLocal):?>
								<?$APPLICATION->IncludeComponent(
									"bitrix:catalog.top",
									"main",
									array(
										"TITLE_BLOCK" => $arParams["SECTION_TOP_BLOCK_TITLE"],
										"SHOW_COUNTER_LIST" => $arParams["SHOW_COUNTER_LIST"],
										"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
										"IBLOCK_ID" => $arParams["IBLOCK_ID"],
										"SHOW_DISCOUNT_TIME" => $arParams["SHOW_DISCOUNT_TIME"],
										"FILTER_NAME" => $arParams["FILTER_NAME"],
										"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
										"ELEMENT_SORT_FIELD" => $arParams["TOP_ELEMENT_SORT_FIELD"],
										"ELEMENT_SORT_ORDER" => $arParams["TOP_ELEMENT_SORT_ORDER"],
										"ELEMENT_SORT_FIELD2" => $arParams["TOP_ELEMENT_SORT_FIELD2"],
										"ELEMENT_SORT_ORDER2" => $arParams["TOP_ELEMENT_SORT_ORDER2"],
										"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
										"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
										"BASKET_URL" => $arParams["BASKET_URL"],
										"SALE_STIKER" => ($arParams["SALE_STIKER"] ? $arParams["SALE_STIKER"] : "SALE_TEXT"),
										"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
										"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
										"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
										"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
										"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
										"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
										"ELEMENT_COUNT" => $arParams["ELEMENT_COUNT"],
										"LINE_ELEMENT_COUNT" => $arParams["TOP_LINE_ELEMENT_COUNT"],
										"PROPERTY_CODE" => $arParams["TOP_PROPERTY_CODE"],
										"PRICE_CODE" => $arParams["PRICE_CODE"],
										"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
										"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
										"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
										"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
										"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
										"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
										"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
										"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
										"CACHE_TYPE" => $arParams["CACHE_TYPE"],
										"CACHE_TIME" => $arParams["CACHE_TIME"],
										"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
										"CACHE_FILTER" => "Y",
										"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
										"OFFERS_FIELD_CODE" => $arParams["TOP_OFFERS_FIELD_CODE"],
										"OFFERS_PROPERTY_CODE" => $arParams["TOP_OFFERS_PROPERTY_CODE"],
										"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
										"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
										"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
										"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
										"OFFERS_LIMIT" => $arParams["TOP_OFFERS_LIMIT"],
										'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
										'CURRENCY_ID' => $arParams['CURRENCY_ID'],
										'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
										'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
										'VIEW_MODE' => (isset($arParams['TOP_VIEW_MODE']) ? $arParams['TOP_VIEW_MODE'] : ''),
										'ROTATE_TIMER' => (isset($arParams['TOP_ROTATE_TIMER']) ? $arParams['TOP_ROTATE_TIMER'] : ''),
										'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
										'LABEL_PROP' => $arParams['LABEL_PROP'],
										'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
										'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
										'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
										'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
										'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
										'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
										'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
										"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
										'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
										'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
										'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
										'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
										'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
										'ADD_TO_BASKET_ACTION' => $basketAction,
										'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
										'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
										'IS_VIEWED' => ($code == "VIEWED" ? "Y" : "N"),
									),
									false, array("HIDE_ICONS"=>"Y")
								);?>
							<?else:?>
								<?$APPLICATION->IncludeComponent(
									"aspro:catalog.viewed.market",
									"slider",
									array(
										"TITLE_BLOCK" => GetMessage('VIEWED_TITLE'),
										"VIEW_TYPE_IN_TAB" => "Y",
										"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
										"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
										"SALE_STIKER" => ($arParams["SALE_STIKER"] ? $arParams["SALE_STIKER"] : "SALE_TEXT"),
										"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
										"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
										"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
									),
									false, array("HIDE_ICONS" => "Y")
								);?>
							<?endif;?>
						</ul>
					</li>
				<?}?>
			</ul>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){

				$('.tab_slider_wrapp.<?=$class_block;?> .tabs > li').first().addClass('cur');
				$('.tab_slider_wrapp.<?=$class_block;?> .slider_navigation > li').first().addClass('cur');
				$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li').first().addClass('cur');

				var flexsliderItemWidth = 210;
				var flexsliderItemMargin = 20;

				var sliderWidth = $('.tab_slider_wrapp.<?=$class_block;?>').outerWidth();
				var flexsliderMinItems = Math.floor(sliderWidth / (flexsliderItemWidth + flexsliderItemMargin));
				$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur').flexslider({
					animation: 'slide',
					selector: '.tabs_slider .catalog_item',
					slideshow: false,
					animationSpeed: 600,
					directionNav: true,
					controlNav: false,
					pauseOnHover: true,
					animationLoop: true,
					itemWidth: flexsliderItemWidth,
					itemMargin: flexsliderItemMargin,
					minItems: flexsliderMinItems,
					controlsContainer: '.tabs_slider_navigation.cur',
					start: function(slider){
						slider.find('li').css('opacity', 1);
					}
				});

				$('.tab_slider_wrapp.<?=$class_block;?> .tabs > li').on('click', function(){
					if(!$(this).hasClass('active')){
						var sliderIndex = $(this).index();
						$(this).addClass('active').addClass('cur').siblings().removeClass('active').removeClass('cur');
						$('.tab_slider_wrapp.<?=$class_block;?> .slider_navigation > li:eq(' + sliderIndex + ')').addClass('cur').show().siblings().removeClass('cur');
						$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li:eq(' + sliderIndex + ')').addClass('cur').siblings().removeClass('cur');
						if(!$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur .flex-viewport').length){
							$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur').flexslider({
								animation: 'slide',
								selector: '.tabs_slider .catalog_item',
								slideshow: false,
								animationSpeed: 600,
								directionNav: true,
								controlNav: false,
								pauseOnHover: true,
								animationLoop: true,
								itemWidth: flexsliderItemWidth,
								itemMargin: flexsliderItemMargin,
								minItems: flexsliderMinItems,
								controlsContainer: '.tabs_slider_navigation.cur',
							});
						}
						$(window).resize();
					}
				});

				$(window).resize(function(){
					var sliderWidth = $('.tab_slider_wrapp.<?=$class_block;?>').outerWidth();
					$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur').css('height', '');
					$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content .tab.cur .tabs_slider .buttons_block').hide();
					$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur').equalize({children: '.item-title'});
					$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur').equalize({children: '.item_info'});
					$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur').equalize({children: '.catalog_item'});
					var itemsButtonsHeight = $('.tab_slider_wrapp.<?=$class_block;?> .tabs_content .tab.cur .tabs_slider li .buttons_block').height();
					var tabsContentUnhover = $('.tab_slider_wrapp.<?=$class_block;?> .tabs_content .tab.cur').height() * 1;
					var tabsContentHover = tabsContentUnhover + itemsButtonsHeight+50;
					$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content .tab.cur').attr('data-unhover', tabsContentUnhover);
					$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content .tab.cur').attr('data-hover', tabsContentHover);
					$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content').height(tabsContentUnhover);
				});

				$(window).resize();
				$(document).on({
					mouseover: function(e){
						var tabsContentHover = $(this).closest('.tab').attr('data-hover') * 1;
						$(this).closest('.tab').fadeTo(100, 1);
						$(this).closest('.tab').stop().css({'height': tabsContentHover});
						$(this).find('.buttons_block').fadeIn(450, 'easeOutCirc');
					},
					mouseleave: function(e){
						var tabsContentUnhoverHover = $(this).closest('.tab').attr('data-unhover') * 1;
						$(this).closest('.tab').stop().animate({'height': tabsContentUnhoverHover}, 100);
						$(this).find('.buttons_block').stop().fadeOut(233);
					}
				}, '.<?=$class_block;?> .tabs_slider li');
			})
		</script>
	<?}?>

	<script type="text/javascript">
	/*$(".sort_filter a").on("click", function(){
		if($(this).is(".current")){
			$(this).toggleClass("desc").toggleClass("asc");
		}
		else{
			$(this).toggleClass("desc").toggleClass("asc");
			$(this).addClass("current").siblings().removeClass("current");
		}
	});*/

	$(".sort_display a:not(.current)").on("click", function() {
		$(this).addClass("current").siblings().removeClass("current");
	});

	$(".number_list a:not(.current)").on("click", function() {
		$(this).addClass("current").siblings().removeClass("current");
	});
	</script>
<?}else{?>
	<?\Bitrix\Iblock\Component\Tools::process404(
		trim($arParams["MESSAGE_404"]) ?: GetMessage("T_SECTION_NOT_FOUND")
		,true
		,$arParams["SET_STATUS_404"] === "Y"
		,$arParams["SHOW_404"] === "Y"
		,$arParams["FILE_404"]
	);?>
<?}?>

<?
if($arSeoItem)
{
	$langing_seo_h1 = ($arSeoItem["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != "" ? $arSeoItem["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] : $arSeoItem["NAME"]);

	$APPLICATION->SetTitle($langing_seo_h1);

	if($arSeoItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])
		$APPLICATION->SetPageProperty("title", $arSeoItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]);
	else
		$APPLICATION->SetPageProperty("title", $arSeoItem["NAME"].$postfix);

	if($arSeoItem["IPROPERTY_VALUES"]["ELEMENT_META_DESCRIPTION"])
		$APPLICATION->SetPageProperty("description", $arSeoItem["IPROPERTY_VALUES"]["ELEMENT_META_DESCRIPTION"]);

	if($arSeoItem["IPROPERTY_VALUES"]['ELEMENT_META_KEYWORDS'])
		$APPLICATION->SetPageProperty("keywords", $arSeoItem["IPROPERTY_VALUES"]['ELEMENT_META_KEYWORDS']);
	?>
<?}?>