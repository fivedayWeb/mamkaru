<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>

<?if( count( $arResult["ITEMS"] ) >= 1 ){?>
	<?if($fast_view_text_tmp = \Bitrix\Main\Config\Option::get('aspro.mshop', 'EXPRESSION_FOR_FAST_VIEW', GetMessage('FAST_VIEW')))
		$fast_view_text = $fast_view_text_tmp;
	else
		$fast_view_text = GetMessage('FAST_VIEW');?>
	<?if($arParams["AJAX_REQUEST"]=="N"){?>
		<div class="display_list">
	<?}?>
		<?
		$currencyList = '';
		if (!empty($arResult['CURRENCIES'])){
			$templateLibrary[] = 'currency';
			$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
		}
		$templateData = array(
			'TEMPLATE_LIBRARY' => $templateLibrary,
			'CURRENCIES' => $currencyList
		);
		unset($currencyList, $templateLibrary);

		$arParams["BASKET_ITEMS"] = ($arParams["BASKET_ITEMS"] ? $arParams["BASKET_ITEMS"] : array());

		$arOfferProps = implode(';', $arParams['OFFERS_CART_PROPERTIES']);
		?>
		<?foreach($arResult["ITEMS"] as $arItem){?>

			<?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

			$arItem["strMainID"] = $this->GetEditAreaId($arItem['ID']);
			$arItemIDs=CMShop::GetItemsIDs($arItem);

			$totalCount = CMShop::GetTotalCount($arItem);
			$arQuantityData = CMShop::GetQuantityArray($totalCount, $arItemIDs["ALL_ITEM_IDS"]);

			$item_id = $arItem["ID"];
			$strMeasure = '';

			if($arParams["SHOW_MEASURE"] == "Y"){
				if($arItem["OFFERS"]){
					$strMeasure = $arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
				}
				else{
					if($arItem["CATALOG_MEASURE"]){
						$arMeasure = CCatalogMeasure::getList(array(), array("ID" => $arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
						$strMeasure = $arMeasure["SYMBOL_RUS"];
					}
				}
			}
			?>

			<?$arAddToBasketData = CMShop::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'small', $arParams);?>
			<div class="list_item_wrapp item_wrap">
				<table class="list_item" id="<?=$arItemIDs["strMainID"];?>" cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr class="adaptive_name">
						<td colspan="2">
							<div class="desc_name"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=$arItem["NAME"]?></span></a></div>
						</td>
					</tr>
					<tr>
					<td class="image_block">
						<div class="image_wrapper_block">
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PICT']; ?>">
								<div class="stickers">
									<?if (is_array($arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?>
										<?foreach($arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"] as $key=>$class){?>
											<div class="sticker_<?=strtolower($class);?>" title="<?=$arItem["PROPERTIES"]["HIT"]["VALUE"][$key]?>"></div>
										<?}?>
									<?endif;?>
								</div>
								<?
								$a_alt=($arItem["PREVIEW_PICTURE"]["DESCRIPTION"] ? $arItem["PREVIEW_PICTURE"]["DESCRIPTION"] : ($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arItem["NAME"] ));
								$a_title=($arItem["PREVIEW_PICTURE"]["DESCRIPTION"] ? $arItem["PREVIEW_PICTURE"]["DESCRIPTION"] : ($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] : $arItem["NAME"] ));
								?>
								<?if( !empty($arItem["PREVIEW_PICTURE"]) ):?>
									<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
								<?elseif( !empty($arItem["DETAIL_PICTURE"])):?>
									<?$img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array( "width" => 170, "height" => 170 ), BX_RESIZE_IMAGE_PROPORTIONAL,true );?>
									<img src="<?=$img["src"]?>" alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
								<?else:?>
									<img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
								<?endif;?>
								<?if($arParams["SALE_STIKER"] && $arItem["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"]){?>
									<div class="sticker_sale_text"><?=$arItem["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"];?></div>
								<?}?>
							</a>
						</div>
						<div class="fast_view_block" data-event="jqm" data-param-form_id="fast_view" data-param-iblock_id="<?=$arParams["IBLOCK_ID"];?>" data-param-id="<?=$arItem["ID"];?>" data-param-item_href="<?=urlencode($arItem["DETAIL_PAGE_URL"]);?>" data-name="fast_view"><?=$fast_view_text;?></div>
					</td>

					<td class="description_wrapp">
						<div class="description">
							<div class="item-title">
								<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=$arItem["NAME"]?></span></a>
							</div>
							<div class="wrapp_stockers">
								<?
								/*$frame = $this->createFrame()->begin('');
								$frame->setBrowserStorage(true);*/
								?>
									<?if(($arParams["DISPLAY_WISH_BUTTONS"] != "N" ) || ($arParams["DISPLAY_COMPARE"] == "Y")):?>
										<div class="like_icons">
											<?if($arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
												<?if(!$arItem["OFFERS"]):?>
													<div class="wish_item_button">
														<span title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item to" data-item="<?=$arItem["ID"]?>" data-iblock="<?=$arItem["IBLOCK_ID"]?>"><i></i></span>
														<span title="<?=GetMessage('CATALOG_WISH_OUT')?>" class="wish_item in added" style="display: none;" data-item="<?=$arItem["ID"]?>" data-iblock="<?=$arItem["IBLOCK_ID"]?>"><i></i></span>
													</div>
												<?elseif($arItem["OFFERS"] && !empty($arItem['OFFERS_PROP'])):?>
													<?/*foreach($arItem["OFFERS"] as $arOffer):?>
														<?if($arOffer['CAN_BUY']):*/?>
															<div class="wish_item_button" style="display: none;">
																<span title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item to <?=$arParams["TYPE_SKU"];?>" data-item="" data-iblock="<?=$arItem["IBLOCK_ID"]?>" data-offers="Y" data-props="<?=$arOfferProps?>"><i></i></span>
																<span title="<?=GetMessage('CATALOG_WISH_OUT')?>" class="wish_item in added <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-item="" data-iblock="<?=$arItem["IBLOCK_ID"]?>"><i></i></span>
															</div>
														<?/*endif;?>
													<?endforeach;*/?>
												<?endif;?>
											<?endif;?>
											<?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
												<?if(!$arItem["OFFERS"] || ($arParams["TYPE_SKU"] !== 'TYPE_1' || ($arParams["TYPE_SKU"] == 'TYPE_1' && !$arItem["OFFERS_PROP"]))):?>
													<div class="compare_item_button">
														<span title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item to" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>" ><i></i></span>
														<span title="<?=GetMessage('CATALOG_COMPARE_OUT')?>" class="compare_item in added" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>"><i></i></span>
													</div>
												<?elseif($arItem["OFFERS"]):?>
													<?//foreach($arItem["OFFERS"] as $arOffer):?>
														<div class="compare_item_button">
															<span title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item to <?=$arParams["TYPE_SKU"];?>" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>" ><i></i></span>
															<span title="<?=GetMessage('CATALOG_COMPARE_OUT')?>" class="compare_item in added <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>"><i></i></span>
														</div>
													<?//endforeach;?>
												<?endif;?>
											<?endif;?>
										</div>
									<?endif;?>
								<?//$frame->end();?>
								<?=$arQuantityData["HTML"];?>
							</div>
							<?if ($arItem["PREVIEW_TEXT"]):?> <div class="preview_text"><?=$arItem["PREVIEW_TEXT"]?></div> <?endif;?>
							<?$boolShowOfferProps = ($arItem['OFFERS_PROPS_DISPLAY']);
							$boolShowProductProps = (isset($arItem['DISPLAY_PROPERTIES']) && !empty($arItem['DISPLAY_PROPERTIES']));?>
							<?if($boolShowProductProps || $boolShowOfferProps):?>
								<div class="show_props">
									<a><i class="icon"><b></b></i><span><?=GetMessage('PROPERTIES')?></span></a>
								</div>
								<div class="props_list_wrapp">
									<table class="props_list prod">
										<?if ($boolShowProductProps){
											foreach( $arItem["DISPLAY_PROPERTIES"] as $arProp ){?>
												<?if( !empty( $arProp["VALUE"] ) ){?>
													<tr>
														<td><span><?=$arProp["NAME"]?></span></td>
														<td>
															<span>
															<?
															if(count($arProp["DISPLAY_VALUE"])>1) { foreach($arProp["DISPLAY_VALUE"] as $key => $value) { if ($arProp["DISPLAY_VALUE"][$key+1]) {echo $value.", ";} else {echo $value;} }}
															else { echo $arProp["DISPLAY_VALUE"]; }
															?>
															</span>
														</td>
													</tr>
												<?}?>
											<?}
										}?>
									</table>
									<?if ($boolShowOfferProps){?>
										<table class="props_list offers" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV']; ?>" style="display: none;"></table>
									<?}?>
								</div>
							<?endif;?>
						</div>
					</td>
					<td class="information_wrapp main_item_wrapper">
						<div class="information">
							<div class="cost prices clearfix">
								<?if( count( $arItem["OFFERS"] ) > 0 ){
									$minPrice = false;
									$min_price_id=0;
									if (isset($arItem['MIN_PRICE']) || isset($arItem['RATIO_PRICE'])){
										// $minPrice = (isset($arItem['RATIO_PRICE']) ? $arItem['RATIO_PRICE'] : $arItem['MIN_PRICE']);
										$minPrice = $arItem['MIN_PRICE'];
									}
									$offer_id=0;
									if($arParams["TYPE_SKU"]=="N"){
										$offer_id=$minPrice["MIN_ITEM_ID"];
									}
									$min_price_id=$minPrice["MIN_PRICE_ID"];
									if(!$min_price_id)
										$min_price_id=$minPrice["PRICE_ID"];
									
									$arTmpOffer = current($arItem["OFFERS"]);
									if(!$min_price_id)
										$min_price_id=$arTmpOffer["MIN_PRICE"]["PRICE_ID"];
									$item_id = $arTmpOffer["ID"];

									$prefix='';
									if('N' == $arParams['TYPE_SKU'] || $arParams['DISPLAY_TYPE'] =='table' || empty($arItem['OFFERS_PROP'])){
										$prefix=GetMessage("CATALOG_FROM");
									}?>
									<?$measure_block = \Aspro\Functions\CAsproMShopSku::getMeasureRatio($arParams, $minPrice);?>
									<div class="with_matrix" style="display:none;">
										<div class="price price_value_block"><span class="values_wrapper"><?=$minPrice["PRINT_DISCOUNT_VALUE"];?></span></div>
										<?if($arParams["SHOW_OLD_PRICE"]=="Y"):?>
											<div class="price discount"></div>
										<?endif;?>
										<?if($arParams["SHOW_DISCOUNT_PERCENT"]=="Y"){?>
											<div class="sale_block matrix" <?=(!$minPrice["DISCOUNT_DIFF"] ? 'style="display:none;"' : '')?>>
												<div class="sale_wrapper">
													<div class="value">-<span></span>%</div>
													<div class="text"><span class="title"><?=GetMessage("CATALOG_ECONOMY");?></span>
													<span class="values_wrapper"><?=$minPrice["PRINT_DISCOUNT_DIFF"];?></span></div>
													<div class="clearfix"></div>
												</div>
											</div>
										<?}?>
									</div>
									<?\Aspro\Functions\CAsproMShopSku::showItemPrices($arParams, $arItem, $item_id, $min_price_id, $arItemIDs);?>
								<?}else{?>
									<?
									$item_id = $arItem["ID"];
									if(isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']) // USE_PRICE_COUNT
									{?>
										<?if($arItem['ITEM_PRICE_MODE'] == 'Q' && count($arItem['PRICE_MATRIX']['ROWS']) > 1):?>
											<?=CMShop::showPriceRangeTop($arItem, $arParams, GetMessage("CATALOG_ECONOMY"));?>
										<?endif;?>
										<?=CMShop::showPriceMatrix($arItem, $arParams, $strMeasure, $arAddToBasketData);?>
										<?$arMatrixKey = array_keys($arItem['PRICE_MATRIX']['MATRIX']);
										$min_price_id=current($arMatrixKey);?>
									<?	
									}
									else
									{
										$arCountPricesCanAccess = 0;
										$min_price_id=0;?>
										<?=\Aspro\Functions\CAsproMShopItem::getItemPrices($arParams, $arItem["PRICES"], $strMeasure, $min_price_id);?>
									<?}?>
								<?}?>
							</div>
							<?if($arParams["SHOW_DISCOUNT_TIME"] != "N" && $arParams['SHOW_COUNTER_LIST'] != 'N'){?>
								<?$arUserGroups = $USER->GetUserGroupArray();?>
								<?if($arParams['SHOW_DISCOUNT_TIME_EACH_SKU'] != 'Y' || ($arParams['SHOW_DISCOUNT_TIME_EACH_SKU'] == 'Y' && !$arItem['OFFERS'])):?>
									<?$arDiscounts = CCatalogDiscount::GetDiscountByProduct( $item_id, $arUserGroups, "N", $min_price_id, SITE_ID );
									$arDiscount=array();
									if($arDiscounts)
										$arDiscount=current($arDiscounts);
									if($arDiscount["ACTIVE_TO"]){?>
										<div class="view_sale_block <?=($arQuantityData["HTML"] ? '' : 'wq');?>">
											<div class="count_d_block">
												<span class="active_to_<?=$arItem["ID"]?> hidden"><?=$arDiscount["ACTIVE_TO"];?></span>
												<div class="title"><?=GetMessage("UNTIL_AKC");?></div>
												<span class="countdown countdown_<?=$arItem["ID"]?> values"></span>
												<script>
													$(document).ready(function(){
														if( $('.countdown').size() ){
															var active_to = '<?=$arDiscount["ACTIVE_TO"];?>',
															date_to = new Date(active_to.replace(/(\d+)\.(\d+)\.(\d+)/, '$3/$2/$1'));
															$('.countdown_<?=$arItem["ID"]?>').countdown({until: date_to, format: 'dHMS', padZeroes: true, layout: '{d<}<span class="days item">{dnn}<div class="text">{dl}</div></span>{d>} <span class="hours item">{hnn}<div class="text">{hl}</div></span> <span class="minutes item">{mnn}<div class="text">{ml}</div></span> <span class="sec item">{snn}<div class="text">{sl}</div></span>'}, $.countdown.regionalOptions['ru']);
														}
													})
												</script>
											</div>
											<?if($arQuantityData["HTML"]):?>
												<div class="quantity_block">
													<div class="title"><?=GetMessage("TITLE_QUANTITY_BLOCK");?></div>
													<div class="values">
														<span class="item">
															<span class="value"><?=$totalCount;?></span>
															<span class="text"><?=GetMessage("TITLE_QUANTITY");?></span>
														</span>
													</div>
												</div>
											<?endif;?>
										</div>
									<?}?>
								<?else:?>
									<?if($arItem['JS_OFFERS'])
									{
										foreach($arItem['JS_OFFERS'] as $keyOffer => $arTmpOffer2)
										{
											$active_to = '';
											$arDiscounts = CCatalogDiscount::GetDiscountByProduct( $arTmpOffer2['ID'], $arUserGroups, "N", array(), SITE_ID );
											if($arDiscounts)
											{
												foreach($arDiscounts as $arDiscountOffer)
												{
													if($arDiscountOffer['ACTIVE_TO'])
													{
														$active_to = $arDiscountOffer['ACTIVE_TO'];
														break;
													}
												}
											}
											$arItem['JS_OFFERS'][$keyOffer]['DISCOUNT_ACTIVE'] = $active_to;
										}
									}?>
									<div class="view_sale_block" style="display:none;">
										<div class="count_d_block">
												<span class="active_to_<?=$arItem["ID"]?> hidden"><?=$arDiscount["ACTIVE_TO"];?></span>
												<div class="title"><?=GetMessage("UNTIL_AKC");?></div>
												<span class="countdown countdown_<?=$arItem["ID"]?> values"></span>
										</div>
										<?if($arQuantityData["HTML"]):?>
											<div class="quantity_block">
												<div class="title"><?=GetMessage("TITLE_QUANTITY_BLOCK");?></div>
												<div class="values">
													<span class="item">
														<span class="value"><?=$totalCount;?></span>
														<span class="text"><?=GetMessage("TITLE_QUANTITY");?></span>
													</span>
												</div>
											</div>
										<?endif;?>
									</div>
								<?endif;?>
							<?}?>
							<?if($arItem["OFFERS"]){?>
								<?if(!empty($arItem['OFFERS_PROP'])){?>
									<div class="sku_props">
										<div class="bx_catalog_item_scu wrapper_sku" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PROP_DIV']; ?>">
											<?$arSkuTemplate = array();?>
											<?$arSkuTemplate=CMShop::GetSKUPropsArray($arItem['OFFERS_PROPS_JS'], $arResult["SKU_IBLOCK_ID"], $arParams["DISPLAY_TYPE"], $arParams["OFFER_HIDE_NAME_PROPS"]);?>
											<?foreach ($arSkuTemplate as $code => $strTemplate){
												if (!isset($arItem['OFFERS_PROP'][$code]))
													continue;
												echo '<div>', str_replace('#ITEM#_prop_', $arItemIDs["ALL_ITEM_IDS"]['PROP'], $strTemplate), '</div>';
											}?>
										</div>
										<?$arItemJSParams=CMShop::GetSKUJSParams($arResult, $arParams, $arItem);?>
										<script type="text/javascript">
											var <? echo $arItemIDs["strObName"]; ?> = new JCCatalogSection(<? echo CUtil::PhpToJSObject($arItemJSParams, false, true); ?>);
										</script>
									</div>
								<?}?>
							<?}?>

							<div class="basket_props_block" id="bx_basket_div_<?=$arItem["ID"];?>" style="display: none;">
								<?
										if (!empty($arItem['PRODUCT_PROPERTIES_FILL']))
										{
											foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo)
											{
								?>
									<input type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
								<?
												if (isset($arItem['PRODUCT_PROPERTIES'][$propID]))
													unset($arItem['PRODUCT_PROPERTIES'][$propID]);
											}
										}
										$arItem["EMPTY_PROPS_JS"]="Y";
										$emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
										if (!$emptyProductProperties)
										{
											$arItem["EMPTY_PROPS_JS"]="N";
								?>
								<div class="wrapper">
									<table>
								<?
											foreach ($arItem['PRODUCT_PROPERTIES'] as $propID => $propInfo)
											{
								?>
									<tr><td><? echo $arItem['PROPERTIES'][$propID]['NAME']; ?></td>
									<td>
								<?
												if(
													'L' == $arItem['PROPERTIES'][$propID]['PROPERTY_TYPE']
													&& 'C' == $arItem['PROPERTIES'][$propID]['LIST_TYPE']
												)
												{
													foreach($propInfo['VALUES'] as $valueID => $value)
													{
														?><label><input type="radio" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><? echo $value; ?></label><?
													}
												}
												else
												{
													?><select name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"><?
													foreach($propInfo['VALUES'] as $valueID => $value)
													{
														?><option value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><? echo $value; ?></option><?
													}
													?></select><?
												}
								?>
									</td></tr>
								<?
											}
								?>
									</table>
								</div>
								<?
										}
								?>
							</div>

							<?if(!$arItem["OFFERS"] || $arParams['TYPE_SKU'] !== 'TYPE_1'):?>								
								<div class="counter_wrapp <?=($arItem["OFFERS"] && $arParams["TYPE_SKU"] == "TYPE_1" ? 'woffers' : '')?>">
									<?if(($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] && $arAddToBasketData["ACTION"] == "ADD") && $arItem["CAN_BUY"]):?>
										<div class="counter_block" data-offers="<?=($arItem["OFFERS"] ? "Y" : "N");?>" data-item="<?=$arItem["ID"];?>">
											<span class="minus" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_DOWN']; ?>">-</span>
											<input type="text" class="text" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY']; ?>" name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>" value="<?=$arAddToBasketData["MIN_QUANTITY_BUY"]?>" />
											<span class="plus" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_UP']; ?>" <?=($arAddToBasketData["MAX_QUANTITY_BUY"] ? "data-max='".$arAddToBasketData["MAX_QUANTITY_BUY"]."'" : "")?>>+</span>
										</div>
									<?endif;?>
									<div id="<?=$arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>" class="button_block <?=(($arAddToBasketData["ACTION"] == "ORDER"/*&& !$arItem["CAN_BUY"]*/) || !$arItem["CAN_BUY"] || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] || $arAddToBasketData["ACTION"] == "SUBSCRIBE" ? "wide" : "");?>">
										<!--noindex-->
											<?=$arAddToBasketData["HTML"]?>
										<!--/noindex-->
									</div>
								</div>
								<?
								if(isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']) // USE_PRICE_COUNT
								{?>
									<?if($arItem['ITEM_PRICE_MODE'] == 'Q' && count($arItem['PRICE_MATRIX']['ROWS']) > 1):?>
										<?$arOnlyItemJSParams = array(
											"ITEM_PRICES" => $arItem["ITEM_PRICES"],
											"ITEM_PRICE_MODE" => $arItem["ITEM_PRICE_MODE"],
											"ITEM_QUANTITY_RANGES" => $arItem["ITEM_QUANTITY_RANGES"],
											"MIN_QUANTITY_BUY" => $arAddToBasketData["MIN_QUANTITY_BUY"],
											"ID" => $arItemIDs["strMainID"],
										)?>
										<script type="text/javascript">
											var <? echo $arItemIDs["strObName"]; ?>el = new JCCatalogSectionOnlyElement(<? echo CUtil::PhpToJSObject($arOnlyItemJSParams, false, true); ?>);
										</script>
									<?endif;?>
								<?}?>
							<?elseif($arItem["OFFERS"]):?>
								<?if(empty($arItem['OFFERS_PROP'])){?>
									<div class="offer_buy_block buys_wrapp woffers">
										<div class="counter_wrapp">
										<?
										$arItem["OFFERS_MORE"] = "Y";
										$arAddToBasketDataSku = CMShop::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'small read_more1', $arParams);?>
										<!--noindex-->
											<?=$arAddToBasketDataSku["HTML"]?>
										<!--/noindex-->
										</div>
									</div>
								<?}else{?>
									<div class="offer_buy_block buys_wrapp woffers" style="display:none;">
										<div class="counter_wrapp"></div>
									</div>
								<?}?>
							<?endif;?>
						</div>
					</td></tr>
				</table>
			</div>
		<?}?>
	<?if($arParams["AJAX_REQUEST"]=="N"){?>
		</div>
	<?}?>
	<?if($arParams["AJAX_REQUEST"]=="Y"){?>
		<div class="wrap_nav">
	<?}?>
	<?//if(strlen($arResult["NAV_STRING"])):?>
		<div class="bottom_nav <?=$arParams["DISPLAY_TYPE"];?>" <?=($arParams["AJAX_REQUEST"]=="Y" ? "style='display: none; '" : "");?>>
			<?if( $arParams["DISPLAY_BOTTOM_PAGER"] == "Y" ){?><?=$arResult["NAV_STRING"]?><?}?>
		</div>
	<?//endif;?>
	<?if($arParams["AJAX_REQUEST"]=="Y"){?>
		</div>
	<?}?>
<?}else{?>
	<div class="no_goods">
		<div class="no_products">
			<div class="wrap_text_empty">
				<?if($_REQUEST["set_filter"]){?>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/section_no_products_filter.php", Array(), Array("MODE" => "html",  "NAME" => GetMessage('EMPTY_CATALOG_DESCR')));?>
				<?}else{?>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/section_no_products.php", Array(), Array("MODE" => "html",  "NAME" => GetMessage('EMPTY_CATALOG_DESCR')));?>
				<?}?>
			</div>
		</div>
		<?if($_REQUEST["set_filter"]){?>
			<span class="button wide"><?=GetMessage('RESET_FILTERS');?></span>
		<?}?>
	</div>
<?}?>
<script>
	BX.message({
		QUANTITY_AVAILIABLE: '<? echo COption::GetOptionString("aspro.mshop", "EXPRESSION_FOR_EXISTS", GetMessage("EXPRESSION_FOR_EXISTS_DEFAULT"), SITE_ID); ?>',
		QUANTITY_NOT_AVAILIABLE: '<? echo COption::GetOptionString("aspro.mshop", "EXPRESSION_FOR_NOTEXISTS", GetMessage("EXPRESSION_FOR_NOTEXISTS"), SITE_ID); ?>',
		ADD_ERROR_BASKET: '<? echo GetMessage("ADD_ERROR_BASKET"); ?>',
		ADD_ERROR_COMPARE: '<? echo GetMessage("ADD_ERROR_COMPARE"); ?>',
	})
</script>
