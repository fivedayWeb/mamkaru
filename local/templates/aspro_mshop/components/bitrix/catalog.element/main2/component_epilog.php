<?
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
	__IncludeLang($_SERVER["DOCUMENT_ROOT"].$templateFolder."/lang/".LANGUAGE_ID."/template.php");

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
?>
<div class="tabs_section">
	<?//block sku?>
	<?$APPLICATION->ShowViewContent('offers_show_block');?>

	<?// block text and props?>
	<?if($templateData["DETAIL_TEXT"] || $templateData["SHOW_PROPS"]):
		$class = 'md-100';
		if($templateData["DETAIL_TEXT"] && $templateData["SHOW_PROPS"])
			$class = 'md-50';?>
		<div class="drag_block">
			<div class="wrap_md wrap_md_row">
				<?if($templateData["DETAIL_TEXT"]):?>
					<div class="iblock <?=$class;?>">
						<h4><?=($arParams["TAB_DESCR_NAME"] ? $arParams["TAB_DESCR_NAME"] : GetMessage("DESCRIPTION_TAB"));?></h4>
						<div class="detail_text">
							<?=$templateData["DETAIL_TEXT"];?>
						</div>
					</div>
				<?endif;?>
				<?if($templateData["SHOW_PROPS"]):?>
				<?$strGrupperType = $templateData["GRUPPER_PROPS"];?>
					<div class="iblock <?=$class;?>">
						<h4><?=($arParams["TAB_CHAR_NAME"] ? $arParams["TAB_CHAR_NAME"] : GetMessage("PROPERTIES_TAB"));?></h4>
						<?if($strGrupperType == "GRUPPER"):?>
							<div class="iblock char_block">
								<?$APPLICATION->IncludeComponent(
									"redsign:grupper.list",
									"",
									Array(
										"CACHE_TIME" => "3600000",
										"CACHE_TYPE" => "A",
										"COMPOSITE_FRAME_MODE" => "A",
										"COMPOSITE_FRAME_TYPE" => "AUTO",
										"DISPLAY_PROPERTIES" => $templateData["GROUPS_PROPS"]
									),
									$component, array('HIDE_ICONS'=>'Y')
								);?>
								<table class="props_list" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV']; ?>"></table>
							</div>
						<?elseif($strGrupperType == "WEBDEBUG"):?>
							<div class="iblock char_block">
								<?$APPLICATION->IncludeComponent(
									"webdebug:propsorter",
									"linear",
									array(
										"IBLOCK_TYPE" => $templateData['IBLOCK_TYPE'],
										"IBLOCK_ID" => $templateData['IBLOCK_ID'],
										"PROPERTIES" => $templateData['GROUPS_PROPS'],
										"EXCLUDE_PROPERTIES" => array(),
										"WARNING_IF_EMPTY" => "N",
										"WARNING_IF_EMPTY_TEXT" => "",
										"NOGROUP_SHOW" => "Y",
										"NOGROUP_NAME" => "",
										"MULTIPLE_SEPARATOR" => ", "
									),
									$component, array('HIDE_ICONS'=>'Y')
								);?>
								<table class="props_list" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV']; ?>"></table>
							</div>
						<?elseif($strGrupperType == "YENISITE_GRUPPER"):?>
							<div class="iblock char_block">
								<?$APPLICATION->IncludeComponent(
									'yenisite:ipep.props_groups',
									'',
									array(
										'DISPLAY_PROPERTIES' => $templateData['GROUPS_PROPS'],
										'IBLOCK_ID' => $arParams['IBLOCK_ID']
									),
									$component, array('HIDE_ICONS'=>'Y')
								)?>
								<table class="props_list colored_char" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV']; ?>"></table>
							</div>
						<?elseif($arParams["PROPERTIES_DISPLAY_TYPE"] != "TABLE"):?>
							<div class="props_block" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV']; ?>">
								<?foreach($templateData["PROPERTIES"] as $propCode => $arProp):?>
									<?if(isset($templateData["DISPLAY_PROPERTIES"][$propCode])):?>
										<?$arProp = $templateData["DISPLAY_PROPERTIES"][$propCode];?>
										<?if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE", "CML2_ARTICLE"))):?>
											<?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):?>
												<div class="char" itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
													<div class="char_name">
														<span <?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"){?>class="whint"<?}?>><?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?><span itemprop="name"><?=($arProp["~NAME"] ? $arProp["~NAME"] : $arProp["NAME"]);?></span></span>
													</div>
													<div class="char_value" itemprop="value">
														<?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
															<?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
														<?else:?>
															<?=$arProp["DISPLAY_VALUE"];?>
														<?endif;?>
													</div>
												</div>
											<?endif;?>
										<?endif;?>
									<?endif;?>
								<?endforeach;?>
							</div>
						<?else:?>
							<div class="char_block">
								<table class="props_list">
									<?foreach($templateData["DISPLAY_PROPERTIES"] as $arProp):?>
										<?if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE", "CML2_ARTICLE"))):?>
											<?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):?>
												<tr  itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
													<td class="char_name">

														<span <?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"){?>class="whint"<?}?>><?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?><span itemprop="name"><?=($arProp["~NAME"] ? $arProp["~NAME"] : $arProp["NAME"]);?></span></span>
													</td>
													<td class="char_value">
														<span itemprop="value">
															<?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
																<?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
															<?else:?>
																<?=$arProp["DISPLAY_VALUE"];?>
															<?endif;?>
														</span>
													</td>
												</tr>
											<?endif;?>
										<?endif;?>
									<?endforeach;?>
								</table>
								<table class="props_list" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV']; ?>"></table>
							</div>
						<?endif;?>
					</div>
				<?endif;?>
			</div>
		</div>
	<?endif;?>

	<?// block services?>
	<?if($templateData["SERVICES"]):?>
		<div class="drag_block">
			<div class="wrap_md wrap_md_row">
				<div class="iblock serv md-100">
					<h4><?=GetMessage("SERVICES_TITLE")?></h4>
					<div class="services_block">
						<?foreach($templateData["SERVICES"] as $arService):?>
							<span class="item">
								<a href="<?=$arService["DETAIL_PAGE_URL"]?>">
									<i class="arrow"><b></b></i>
									<span class="link"><?=$arService["NAME"]?></span>
								</a>
							</span>
						<?endforeach;?>
					</div>
				</div>
			</div>
		</div>
	<?endif;?>

	<?// block files?>
	<?if($templateData['FILES']):?>
		<div class="drag_block">
			<div class="wrap_md wrap_md_row">
				<div class="iblock serv md-100">
					<div class="files_block">
						<h4><?=GetMessage("DOCUMENTS_TITLE")?></h4>
						<div class="wrap_md">
							<div class="wrapp_docs iblock">
							<?
							$i=1;
							foreach($templateData['FILES'] as $arItem):?>
								<?$arFile=CMShop::GetFileInfo($arItem);?>
								<div class="file_type clearfix <?=$arFile["TYPE"];?>">
									<i class="icon"></i>
									<div class="description">
										<a target="_blank" href="<?=$arFile["SRC"];?>"><?=$arFile["DESCRIPTION"];?></a>
										<span class="size"><?=GetMessage('CT_NAME_SIZE')?>:
											<?=$arFile["FILE_SIZE_FORMAT"];?>
										</span>
									</div>
								</div>
								<?if($i%3==0){?>
									</div><div class="wrapp_docs iblock">
								<?}?>
								<?$i++;?>
							<?endforeach;?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?endif;?>

	<?// block video?>
	<?if($templateData['VIDEO']):?>
		<div class="drag_block">
			<div class="wrap_md wrap_md_row">
				<div class="iblock serv md-100">
					<h4><?=GetMessage("VIDEO_TAB")?></h4>
					<div class="video_block">
						<?if(count($templateData['VIDEO']) > 1):?>
							<table class="video_table">
								<tbody>
									<?foreach($templateData['VIDEO'] as $v => $value):?>
										<?if(($v + 1) % 2):?>
											<tr>
										<?endif;?>
										<td width="50%"><?=str_replace('src=', 'width="561" height="314" src=', str_replace(array('width', 'height'), array('data-width', 'data-height'), $value));?></td>
										<?if(!(($v + 1) % 2)):?>
											</tr>
										<?endif;?>
									<?endforeach;?>
									<?if(($v + 1) % 2):?>
										</tr>
									<?endif;?>
								</tbody>
							</table>
						<?else:?>
							<?=$templateData['VIDEO'][0]?>
						<?endif;?>
					</div>
				</div>
			</div>
		</div>
	<?endif;?>

	<?// block review?>
	<?if($arParams["USE_REVIEW"] == "Y" && IsModuleInstalled("forum")):?>
		<div class="drag_block">
			<div class="wrap_md wrap_md_row">
				<div class="iblock serv md-100">
					<div id="product_reviews_tab">
						<h4><?=GetMessage("REVIEW_TAB")?></span><span class="count empty"></h4>
						<div id="reviews_content" style="display:block;padding-top:0px;">
							<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("area");?>
							<?$APPLICATION->IncludeComponent(
								"bitrix:forum.topic.reviews",
								"main",
								Array(
									"CACHE_TYPE" => $arParams["CACHE_TYPE"],
									"CACHE_TIME" => $arParams["CACHE_TIME"],
									"MESSAGES_PER_PAGE" => $arParams["MESSAGES_PER_PAGE"],
									"USE_CAPTCHA" => $arParams["USE_CAPTCHA"],
									"FORUM_ID" => $arParams["FORUM_ID"],
									"ELEMENT_ID" => $arResult["ID"],
									"IBLOCK_ID" => $arParams["IBLOCK_ID"],
									"AJAX_POST" => $arParams["REVIEW_AJAX_POST"],
									"SHOW_RATING" => "N",
									"SHOW_MINIMIZED" => "Y",
									"SECTION_REVIEW" => "Y",
									"POST_FIRST_MESSAGE" => "Y",
									"MINIMIZED_MINIMIZE_TEXT" => GetMessage("HIDE_FORM"),
									"MINIMIZED_EXPAND_TEXT" => GetMessage("ADD_REVIEW"),
									"SHOW_AVATAR" => "N",
									"SHOW_LINK_TO_FORUM" => "N",
									"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
								),	false
							);?>
							<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("area", "");?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?endif;?>

	<?// block ask?>
	<?if(($arParams["SHOW_ASK_BLOCK"] == "Y") && (intVal($arParams["ASK_FORM_ID"]))):?>
		<div class="drag_block">
			<div class="wrap_md wrap_md_row">
				<div class="iblock serv md-100">
					<h4><?=GetMessage("ASK_TAB");?></h4>
					<div class="wrap_md forms">
						<div class="iblock text_block">
							<?$APPLICATION->IncludeFile(SITE_DIR."include/ask_tab_detail_description.php", array(), array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_ASK_DESCRIPTION')));?>
						</div>
						<div class="iblock form_block">
							<?$APPLICATION->IncludeComponent(
								"bitrix:form.result.new",
								"inline",
								Array(
									"WEB_FORM_ID" => $arParams["ASK_FORM_ID"],
									"IGNORE_CUSTOM_TEMPLATE" => "N",
									"USE_EXTENDED_ERRORS" => "N",
									"SEF_MODE" => "N",
									"CACHE_TYPE" => "A",
									"CACHE_TIME" => "3600",
									"LIST_URL" => "",
									"EDIT_URL" => "",
									"SUCCESS_URL" => "?send=ok",
									"CHAIN_ITEM_TEXT" => "",
									"CHAIN_ITEM_LINK" => "",
									"VARIABLE_ALIASES" => Array("WEB_FORM_ID" => "WEB_FORM_ID", "RESULT_ID" => "RESULT_ID"),
									"AJAX_MODE" => "Y",
									"AJAX_OPTION_JUMP" => "N",
									"AJAX_OPTION_STYLE" => "Y",
									"AJAX_OPTION_HISTORY" => "N",
								)
							);?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?endif;?>

	<?// block stores?>
	<?if($templateData["USE_STORE"] && ($templateData["SHOW_CUSTOM_OFFER"] || !$templateData["HAS_OFFERS"])):?>
		<div class="drag_block">
			<div class="wrap_md wrap_md_row">
				<div class="iblock stores md-100">
					<h4><?=GetMessage("STORES_TAB");?></h4>
					<div class="stores_tab">
						<?if(!$templateData["HAS_OFFERS"]){?>
							<?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", "main", array(
									"PER_PAGE" => "10",
									"USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
									"SCHEDULE" => $arParams["SCHEDULE"],
									"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
									"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
									"ELEMENT_ID" => $arResult["ID"],
									"STORE_PATH"  =>  $arParams["STORE_PATH"],
									"MAIN_TITLE"  =>  $arParams["MAIN_TITLE"],
									"MAX_AMOUNT"=>$arParams["MAX_AMOUNT"],
									"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
									"SHOW_EMPTY_STORE" => $arParams['SHOW_EMPTY_STORE'],
									"SHOW_GENERAL_STORE_INFORMATION" => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
									"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
									"USER_FIELDS" => $arParams['USER_FIELDS'],
									"FIELDS" => $arParams['FIELDS'],
									"STORES" => $arParams['STORES'],
								),
								$component
							);?>
						<?}?>
					</div>
				</div>
			</div>
		</div>
	<?endif;?>

	<?// block additional?>
	<?if($arParams["SHOW_ADDITIONAL_TAB"] == "Y"):?>
		<div class="drag_block">
			<div class="wrap_md wrap_md_row">
				<div class="iblock md-100">
					<h4><?=GetMessage("ADDITIONAL_TAB");?></h4>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/additional_products_description.php", array(), array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_ADDITIONAL_DESCRIPTION')));?>
				</div>
			</div>
		</div>
	<?endif;?>

	<?// block gifts?>
	<?if(CMShop::checkVersionModule('sale', '16.5.0')):?>
		<div class="gifts">
			<?if ($templateData['CATALOG'] && $arParams['USE_GIFTS_DETAIL'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled("sale"))
			{
				$APPLICATION->IncludeComponent("bitrix:sale.gift.product", "main", array(
					"SHOW_UNABLE_SKU_PROPS"=>$arParams["SHOW_UNABLE_SKU_PROPS"],
					'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
					'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
					'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
					'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
					'SUBSCRIBE_URL_TEMPLATE' => $arResult['~SUBSCRIBE_URL_TEMPLATE'],
					'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
					"OFFER_HIDE_NAME_PROPS" => $arParams["OFFER_HIDE_NAME_PROPS"],

					"SHOW_DISCOUNT_PERCENT" => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
					"SHOW_OLD_PRICE" => $arParams['GIFTS_SHOW_OLD_PRICE'],
					"PAGE_ELEMENT_COUNT" => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
					"LINE_ELEMENT_COUNT" => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
					"HIDE_BLOCK_TITLE" => $arParams['GIFTS_DETAIL_HIDE_BLOCK_TITLE'],
					"BLOCK_TITLE" => $arParams['GIFTS_DETAIL_BLOCK_TITLE'],
					"TEXT_LABEL_GIFT" => $arParams['GIFTS_DETAIL_TEXT_LABEL_GIFT'],
					"SHOW_NAME" => $arParams['GIFTS_SHOW_NAME'],
					"SHOW_IMAGE" => $arParams['GIFTS_SHOW_IMAGE'],
					"MESS_BTN_BUY" => $arParams['GIFTS_MESS_BTN_BUY'],

					"SALE_STIKER" => ($arParams["SALE_STIKER"] ? $arParams["SALE_STIKER"] : "SALE_TEXT"),

					"SHOW_PRODUCTS_{$arParams['IBLOCK_ID']}" => "Y",
					"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
					"PRODUCT_SUBSCRIPTION" => $arParams["PRODUCT_SUBSCRIPTION"],
					"MESS_BTN_DETAIL" => $arParams["MESS_BTN_DETAIL"],
					"MESS_BTN_SUBSCRIBE" => $arParams["MESS_BTN_SUBSCRIBE"],
					"TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
					"PRICE_CODE" => $arParams["PRICE_CODE"],
					"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
					"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
					"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
					"CURRENCY_ID" => $arParams["CURRENCY_ID"],
					"BASKET_URL" => $arParams["BASKET_URL"],
					"ADD_PROPERTIES_TO_BASKET" => $arParams["ADD_PROPERTIES_TO_BASKET"],
					"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
					"PARTIAL_PRODUCT_PROPERTIES" => $arParams["PARTIAL_PRODUCT_PROPERTIES"],
					"USE_PRODUCT_QUANTITY" => 'N',
					"OFFER_TREE_PROPS_{$templateData['OFFERS_IBLOCK']}" => $arParams['OFFER_TREE_PROPS'],
					"CART_PROPERTIES_{$templateData['OFFERS_IBLOCK']}" => $arParams['OFFERS_CART_PROPERTIES'],
					"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
					"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
					"SHOW_DISCOUNT_TIME" => $arParams["SHOW_DISCOUNT_TIME"],
					"SALE_STIKER" => $arParams["SALE_STIKER"],
					"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
					"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
					"DISPLAY_TYPE" => "block",
					"SHOW_RATING" => $arParams["SHOW_RATING"],
					"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
					"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
					"DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
					"TYPE_SKU" => "Y",
					'SHOW_DISCOUNT_TIME_EACH_SKU' => $arParams['SHOW_DISCOUNT_TIME_EACH_SKU'],

					"POTENTIAL_PRODUCT_TO_BUY" => array(
						'ID' => isset($arResult['ID']) ? $arResult['ID'] : null,
						'MODULE' => isset($templateData['MODULE']) ? $templateData['MODULE'] : 'catalog',
						'PRODUCT_PROVIDER_CLASS' => isset($templateData['PRODUCT_PROVIDER_CLASS']) ? $templateData['PRODUCT_PROVIDER_CLASS'] : 'CCatalogProductProvider',
						'QUANTITY' => isset($templateData['QUANTITY']) ? $templateData['QUANTITY'] : null,
						'IBLOCK_ID' => isset($arResult['IBLOCK_ID']) ? $arResult['IBLOCK_ID'] : null,

						'PRIMARY_OFFER_ID' => isset($templateData['OFFERS'][0]['ID']) ? $templateData['OFFERS'][0]['ID'] : null,
						'SECTION' => array(
							'ID' => isset($arResult['SECTION']['ID']) ? $arResult['SECTION']['ID'] : null,
							'IBLOCK_ID' => isset($arResult['SECTION']['IBLOCK_ID']) ? $arResult['SECTION']['IBLOCK_ID'] : null,
							'LEFT_MARGIN' => isset($arResult['SECTION']['LEFT_MARGIN']) ? $arResult['SECTION']['LEFT_MARGIN'] : null,
							'RIGHT_MARGIN' => isset($arResult['SECTION']['RIGHT_MARGIN']) ? $arResult['SECTION']['RIGHT_MARGIN'] : null,
						),
					)
				), $component, array("HIDE_ICONS" => "Y"));
			}
			if ($templateData['CATALOG'] && $arParams['USE_GIFTS_MAIN_PR_SECTION_LIST'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled("sale"))
			{
				$APPLICATION->IncludeComponent(
					"bitrix:sale.gift.main.products",
					"main",
					array(
						"PAGE_ELEMENT_COUNT" => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
						"BLOCK_TITLE" => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'],

						"OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
						"OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],

						"AJAX_MODE" => $arParams["AJAX_MODE"],
						"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],

						"SALE_STIKER" => ($arParams["SALE_STIKER"] ? $arParams["SALE_STIKER"] : "SALE_TEXT"),

						"ELEMENT_SORT_FIELD" => 'ID',
						"ELEMENT_SORT_ORDER" => 'DESC',
						"FILTER_NAME" => 'searchFilter',
						"SECTION_URL" => $arParams["SECTION_URL"],
						"DETAIL_URL" => $arParams["DETAIL_URL"],
						"BASKET_URL" => $arParams["BASKET_URL"],
						"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
						"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
						"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],

						"CACHE_TYPE" => $arParams["CACHE_TYPE"],
						"CACHE_TIME" => $arParams["CACHE_TIME"],

						"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
						"SET_TITLE" => $arParams["SET_TITLE"],
						"PROPERTY_CODE" => $arParams["PROPERTY_CODE"],
						"PRICE_CODE" => $arParams["PRICE_CODE"],
						"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
						"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
						'SHOW_DISCOUNT_TIME_EACH_SKU' => $arParams['SHOW_DISCOUNT_TIME_EACH_SKU'],

						"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
						"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
						"CURRENCY_ID" => $arParams["CURRENCY_ID"],
						"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
						"TEMPLATE_THEME" => (isset($arParams["TEMPLATE_THEME"]) ? $arParams["TEMPLATE_THEME"] : ""),

						"ADD_PICT_PROP" => (isset($arParams["ADD_PICT_PROP"]) ? $arParams["ADD_PICT_PROP"] : ""),

						"LABEL_PROP" => (isset($arParams["LABEL_PROP"]) ? $arParams["LABEL_PROP"] : ""),
						"OFFER_ADD_PICT_PROP" => (isset($arParams["OFFER_ADD_PICT_PROP"]) ? $arParams["OFFER_ADD_PICT_PROP"] : ""),
						"OFFER_TREE_PROPS" => (isset($arParams["OFFER_TREE_PROPS"]) ? $arParams["OFFER_TREE_PROPS"] : ""),
						"SHOW_DISCOUNT_PERCENT" => (isset($arParams["SHOW_DISCOUNT_PERCENT"]) ? $arParams["SHOW_DISCOUNT_PERCENT"] : ""),
						"SHOW_OLD_PRICE" => (isset($arParams["SHOW_OLD_PRICE"]) ? $arParams["SHOW_OLD_PRICE"] : ""),
						"MESS_BTN_BUY" => (isset($arParams["MESS_BTN_BUY"]) ? $arParams["MESS_BTN_BUY"] : ""),
						"MESS_BTN_ADD_TO_BASKET" => (isset($arParams["MESS_BTN_ADD_TO_BASKET"]) ? $arParams["MESS_BTN_ADD_TO_BASKET"] : ""),
						"MESS_BTN_DETAIL" => (isset($arParams["MESS_BTN_DETAIL"]) ? $arParams["MESS_BTN_DETAIL"] : ""),
						"MESS_NOT_AVAILABLE" => (isset($arParams["MESS_NOT_AVAILABLE"]) ? $arParams["MESS_NOT_AVAILABLE"] : ""),
						'ADD_TO_BASKET_ACTION' => (isset($arParams["ADD_TO_BASKET_ACTION"]) ? $arParams["ADD_TO_BASKET_ACTION"] : ""),
						'SHOW_CLOSE_POPUP' => (isset($arParams["SHOW_CLOSE_POPUP"]) ? $arParams["SHOW_CLOSE_POPUP"] : ""),
						'DISPLAY_COMPARE' => (isset($arParams['DISPLAY_COMPARE']) ? $arParams['DISPLAY_COMPARE'] : ''),
						'COMPARE_PATH' => (isset($arParams['COMPARE_PATH']) ? $arParams['COMPARE_PATH'] : ''),
						"SHOW_DISCOUNT_TIME" => $arParams["SHOW_DISCOUNT_TIME"],
						"SALE_STIKER" => $arParams["SALE_STIKER"],
						"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
						"DISPLAY_TYPE" => "block",
						"SHOW_RATING" => $arParams["SHOW_RATING"],
						"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
						"DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
					)
					+ array(
						'OFFER_ID' => empty($templateData['OFFERS'][$templateData['OFFERS_SELECTED']]['ID']) ? $arResult['ID'] : $templateData['OFFERS'][$templateData['OFFERS_SELECTED']]['ID'],
						'SECTION_ID' => $arResult['SECTION']['ID'],
						'ELEMENT_ID' => $arResult['ID'],
					),
					$component,
					array("HIDE_ICONS" => "Y")
				);
			}
			?>
		</div>
	<?endif;?>
</div>

<?if($arResult["ID"]):?>
	<script type="text/javascript">
		viewItemCounter('<?=$arResult["ID"];?>','<?=current($arParams["PRICE_CODE"]);?>');
	</script>
<?endif;?>
<?if (isset($templateData['TEMPLATE_LIBRARY']) && !empty($templateData['TEMPLATE_LIBRARY'])){
	$loadCurrency = false;
	if (!empty($templateData['CURRENCIES']))
		$loadCurrency = Loader::includeModule('currency');
	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);
	if ($loadCurrency){?>
		<script type="text/javascript">
			BX.Currency.setCurrencies(<? echo $templateData['CURRENCIES']; ?>);
		</script>
	<?}
}?>
<script type="text/javascript">
	var viewedCounter = {
		path: '/bitrix/components/bitrix/catalog.element/ajax.php',
		params: {
			AJAX: 'Y',
			SITE_ID: "<?= SITE_ID ?>",
			PRODUCT_ID: "<?= $arResult['ID'] ?>",
			PARENT_ID: "<?= $arResult['ID'] ?>"
		}
	};
	BX.ready(
		BX.defer(function(){
			$('body').addClass('detail_page');
			<?//if(!isset($templateData['JS_OBJ'])){?>
				BX.ajax.post(
					viewedCounter.path,
					viewedCounter.params
				);
			<?//}?>
			if( $('.stores_tab').length ){
				$.ajax({
					type:"POST",
					url:arMShopOptions['SITE_DIR']+"ajax/productStoreAmount.php",
					data:<?=CUtil::PhpToJSObject($templateData["STORES"], false, true, true)?>,
					success: function(data){
						var arSearch=parseUrlQuery();
						$('.tabs_section .stores_tab').html(data);
						if("oid" in arSearch)
							$('.stores_tab .sku_stores_'+arSearch.oid).show();
						else
							$('.stores_tab > div:first').show();
					}
				});
			}
		})
	);
</script>
<?if(isset($_GET["RID"])){?>
	<?if($_GET["RID"]){?>
		<script>
			$(document).ready(function() {
				$("<div class='rid_item' data-rid='<?=$_GET["RID"];?>'></div>").appendTo($('body'));
			});
		</script>
	<?}?>
<?}?>