<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
use Bitrix\Sale\DiscountCouponsManager;

if (!empty($arResult["ERROR_MESSAGE"]))
	ShowError($arResult["ERROR_MESSAGE"]);

$bDelayColumn  = false;
$bDeleteColumn = false;
$bWeightColumn = false;
$bPropsColumn  = false;
$bPriceType    = false;

if ($normalCount > 0):
?>
<div id="basket_items_list">
	<div class="bx_ordercart_order_table_container">
		<table id="cart-table">
			<thead>
				<tr class="empty">
					<th colspan="3"></th>
					<th>Цена</th>
					<th></th>
				</tr>
			</thead>

			<tbody>
				<?foreach ($arResult["GRID"]["ROWS"] as $k => $arItem):?>
					<?//print_r($arItem);?>
					<tr id="<?=$arItem["ID"]?>">
						<td class="cart-image">
							<div class="bx_ordercart_photo_container">
								<?if (strlen($arItem["PREVIEW_PICTURE_SRC"]) > 0):
									$url = $arItem["PREVIEW_PICTURE_SRC"];
								elseif (strlen($arItem["DETAIL_PICTURE_SRC"]) > 0):
									$url = $arItem["DETAIL_PICTURE_SRC"];
								else:
									$url = $templateFolder."/images/no_photo.png";
								endif;?>
								<img src="<?=$url?>">
							</div>
						</td>
						<td class="cart-title">
							<a href="<?=$arResult["PRODUCT_DETAIL_PAGE_URL"][$arItem['PRODUCT_ID']]?>">
								<?=$arItem["NAME"]?>
							</a>
							<? if($arItem["PRODUCT_ID"] == 602503) { ?>
								<p>+ Пустышка Bibs - 0 ₽</p>
							<?} ?>
						</td>
						<td class="cart-count">
							<div class="count-picker">
								<?
								$ratio = isset($arItem["MEASURE_RATIO"]) ? $arItem["MEASURE_RATIO"] : 0;
								$max = isset($arItem["AVAILABLE_QUANTITY"]) ? "max=\"".$arItem["AVAILABLE_QUANTITY"]."\"" : "";
								$useFloatQuantity = ($arParams["QUANTITY_FLOAT"] == "Y") ? true : false;
								$useFloatQuantityJS = ($useFloatQuantity ? "true" : "false");
								?>
								<input
									type="hidden"
									size="3"
									id="QUANTITY_INPUT_<?=$arItem["ID"]?>"
									name="QUANTITY_INPUT_<?=$arItem["ID"]?>"
									size="2"
									maxlength="18"
									min="0"
									<?=$max?>
									step="1"
									style="max-width: 50px"
									value="<?=$arItem["QUANTITY"]?>"
									onchange="updateQuantity('QUANTITY_INPUT_<?=$arItem["ID"]?>', '<?=$arItem["ID"]?>', 1, <?=$useFloatQuantityJS?>);return false"
								>
								<input type="hidden" id="QUANTITY_<?=$arItem['ID']?>" name="QUANTITY_<?=$arItem['ID']?>" value="<?=$arItem["QUANTITY"]?>" />
								<button class="count-button minus" onclick="setQuantity(<?=$arItem["ID"]?>, 1, 'down', <?=$useFloatQuantityJS?>);return false;">-</button>
								<div class="count-counter"><?=$arItem["QUANTITY"]?></div>
								<button class="count-button plus" onclick="setQuantity(<?=$arItem["ID"]?>, 1, 'up', <?=$useFloatQuantityJS?>);return false;">+</button>
							</div>
						</td>
						<td class="cart-price">
							<?if ($arItem['DISCOUNT_PRICE'] > 0):?>
								<s><?=Price::format($arItem['FULL_PRICE'])?> ₽</s>
							<?endif;?>
							<?=Price::format($arItem['PRICE'])?> ₽
						</td>
						<td class="revove">
							<a href="<?=str_replace("#ID#", $arItem["ID"], $arUrls["delete"])?>" class="revove remove-from-basket" title="Удалить из корзины">X</a>
						</td>
					</tr>
				<?endforeach;?>
				<tr class="cart-footer">
					<td colspan="3" class="empty"></td>
					<td class="sum-title">Итого</td>
					<td class="sum-size" id="total_new_price"><?=Price::format(str_replace(" ", "&nbsp;", $arResult["allSum"]))?> ₽</td>
					<td class="empty"></td>
				</tr>
				<tr class="cart-footer">
					<td colspan="3" class="empty"></td>
					<td class="to-shop">
						<a href="/catalog/">Вернутся к покупкам</a>
					</td>
					<td class="to-order">
						<a onclick="checkOut();" class="checkout">Оформить заказ</a>
					</td>
					<td class="empty"></td>
				</tr>
			</tbody>
		</table>
	</div>

	<input type="hidden" id="column_headers" value="<?=CUtil::JSEscape(implode($arHeaders, ","))?>" />
	<input type="hidden" id="offers_props" value="<?=CUtil::JSEscape(implode($arParams["OFFERS_PROPS"], ","))?>" />
	<input type="hidden" id="action_var" value="<?=CUtil::JSEscape($arParams["ACTION_VARIABLE"])?>" />
	<input type="hidden" id="quantity_float" value="<?=$arParams["QUANTITY_FLOAT"]?>" />
	<input type="hidden" id="count_discount_4_all_quantity" value="<?=($arParams["COUNT_DISCOUNT_4_ALL_QUANTITY"] == "Y") ? "Y" : "N"?>" />
	<input type="hidden" id="price_vat_show_value" value="<?=($arParams["PRICE_VAT_SHOW_VALUE"] == "Y") ? "Y" : "N"?>" />
	<input type="hidden" id="hide_coupon" value="<?=($arParams["HIDE_COUPON"] == "Y") ? "Y" : "N"?>" />
	<input type="hidden" id="use_prepayment" value="<?=($arParams["USE_PREPAYMENT"] == "Y") ? "Y" : "N"?>" />
	<input type="hidden" id="auto_calculation" value="<?=($arParams["AUTO_CALCULATION"] == "N") ? "N" : "Y"?>" />
</div>
<?endif;?>