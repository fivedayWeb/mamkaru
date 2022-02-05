<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if( count( $arResult["ITEMS"] ) >= 1 ){?>
	<?if($fast_view_text_tmp = \Bitrix\Main\Config\Option::get('aspro.mshop', 'EXPRESSION_FOR_FAST_VIEW', GetMessage('FAST_VIEW')))
		$fast_view_text = $fast_view_text_tmp;
	else
		$fast_view_text = GetMessage('FAST_VIEW');?>
	<div class="top_wrapper catalog">
		<div class="catalog_block">
			<?foreach($arResult["ITEMS"] as $arItem){?>
				<div class="catalog_item_wrapp">				
					<?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
					
					$totalCount = CMShop::GetTotalCount($arItem);
					$arQuantityData = CMShop::GetQuantityArray($totalCount);
					$arItem["FRONT_CATALOG"]="Y";
					
					$strMeasure='';
					if($arItem["OFFERS"]){
						$strMeasure=$arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
					}else{
						if (($arParams["SHOW_MEASURE"]=="Y")&&($arItem["CATALOG_MEASURE"])){
							$arMeasure = CCatalogMeasure::getList(array(), array("ID"=>$arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
							$strMeasure=$arMeasure["SYMBOL_RUS"];
						}
					}
					?>
					<div class="catalog_item item_wrap <?=(($_GET['q'])) ? 's' : ''?>" id="<?=$this->GetEditAreaId($arItem["ID"]);?>">
						<div>
							<div class="image_wrapper_block">
								<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb">
									<?if($arItem["PROPERTIES"]["HIT"]){?>
										<div class="stickers">
											<?foreach($arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"] as $key=>$class){?>
												<div class="sticker_<?=strtolower($class);?>" title="<?=$arItem["PROPERTIES"]["HIT"]["VALUE"][$key]?>"></div>
											<?}?>
										</div>
									<?}?>
									<?if( ($arParams["DISPLAY_WISH_BUTTONS"] != "N" || $arParams["DISPLAY_COMPARE"] == "Y")):?>
										<div class="like_icons">
											<?if($arItem["CAN_BUY"] && empty($arItem["OFFERS"]) && $arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
												<div class="wish_item_button">
													<span title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item to" data-item="<?=$arItem["ID"]?>"><i></i></span>
													<span title="<?=GetMessage('CATALOG_WISH_OUT')?>" class="wish_item in added" style="display: none;" data-item="<?=$arItem["ID"]?>"><i></i></span>
												</div>
											<?endif;?>
											<?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
												<div class="compare_item_button">
													<span title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item to" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>" ><i></i></span>
													<span title="<?=GetMessage('CATALOG_COMPARE_OUT')?>" class="compare_item in added" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>"><i></i></span>
												</div>
											<?endif;?>
										</div>
									<?endif;?>
									<?if(!empty($arItem["PREVIEW_PICTURE"])):?>
										<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"]?$arItem["PREVIEW_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"]?$arItem["PREVIEW_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
									<?elseif(!empty($arItem["DETAIL_PICTURE"])):?>
										<?$img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array("width" => 170, "height" => 170), BX_RESIZE_IMAGE_PROPORTIONAL, true );?>
										<img src="<?=$img["src"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"]?$arItem["PREVIEW_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"]?$arItem["PREVIEW_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
									<?else:?>
										<img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"]?$arItem["PREVIEW_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"]?$arItem["PREVIEW_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
									<?endif;?>
									<?if($arParams["SALE_STIKER"] && $arItem["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"]){?>
										<div class="sticker_sale_text"><?=$arItem["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"];?></div>
									<?}?>
								</a>
								<div class="fast_view_block" data-event="jqm" data-param-form_id="fast_view" data-param-iblock_id="<?=$arParams["IBLOCK_ID"];?>" data-param-id="<?=$arItem["ID"];?>" data-param-item_href="<?=urlencode($arItem["DETAIL_PAGE_URL"]);?>" data-name="fast_view"><?=$fast_view_text;?></div>
							</div>
							<?$arAddToBasketData = CMShop::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], true);?>
							<div class="item_info">
								<div class="item-title">
									<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=$arItem["NAME"]?></span></a>
								</div>
								<?=$arQuantityData["HTML"];?>
								<div class="cost prices clearfix">
									<?if($arItem["OFFERS"]):?>
										<?\Aspro\Functions\CAsproMShopSku::showItemPrices($arParams, $arItem, $item_id, $min_price_id, $arItemIDs);?>
									<?else:?>
										<?
										if(isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']) // USE_PRICE_COUNT
										{?>
											<?if($arItem['ITEM_PRICE_MODE'] == 'Q' && count($arItem['PRICE_MATRIX']['ROWS']) > 1):?>
												<?=CMShop::showPriceRangeTop($arItem, $arParams, GetMessage("CATALOG_ECONOMY"));?>
											<?endif;?>
											<?=CMShop::showPriceMatrix($arItem, $arParams, $strMeasure, $arAddToBasketData);?>
										<?	
										}
										elseif($arItem["PRICES"])
										{?>
											<?=\Aspro\Functions\CAsproMShopItem::getItemPrices($arParams, $arItem["PRICES"], $strMeasure, $min_price_id);?>
										<?}?>
									<?endif;?>
								</div>
								<div class="hover_block">
									<?=$arAddToBasketData["HTML"]?>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?}?>
		</div>
	</div>
<?}?>
<div class="clearfix"></div>
<script>
	$(document).ready(function(){
		$('.catalog_block .catalog_item_wrapp').sliceHeightExt();
		$('.catalog_block .catalog_item_wrapp .catalog_item .cost').sliceHeightExt({'parent':'.catalog_item_wrapp'});
		$('.catalog_block .catalog_item_wrapp .catalog_item .item-title').sliceHeightExt({'parent':'.catalog_item_wrapp'});
	})
</script>