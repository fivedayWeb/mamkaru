<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
</div>
<?
$bDefaultColumns = $arResult["GRID"]["DEFAULT_COLUMNS"];
$colspan = ($bDefaultColumns) ? count($arResult["GRID"]["HEADERS"]) : count($arResult["GRID"]["HEADERS"]) - 1;
$bPropsColumn = false;
$bUseDiscount = false;
$bPriceType = false;
$bDiscountColumn = false;
$bShowNameWithPicture = ($bDefaultColumns) ? true : false; // flat to show name and picture column in one column
?>
<div class="bx_ordercart basket_wrapp">
	<h4><?=GetMessage("SALE_PRODUCTS_SUMMARY");?></h4>
	<div class="bx_ordercart_order_table_container module-cart">
		<?
		// new
		define("SHOP_ID", "622");
		global $kasses;
		$kasses = ['4' => 0, '1' => 0, '2' => 0, '3' => 0];

		function getToCartTwo() {
			$basket = \Bitrix\Sale\Basket ::loadItemsForFUser(
				\Bitrix\Sale\Fuser ::getId(),
				\Bitrix\Main\Context ::getCurrent() -> getSite()
			);
			$basketItems = $basket -> getBasketItems();
			$items = $pids = [];
			foreach ($basketItems as $item) {
				$itemdata = [];
				foreach ($item -> getAvailableFields() as $fieldcode)
				{
					$itemdata[$fieldcode] = $item -> getField($fieldcode);
				}
				$productInfoBySKUId = \CCatalogSku::GetProductInfo($itemdata['PRODUCT_ID']);
				if (is_array($productInfoBySKUId)){
					//echo ' - ID товара = '.$mxResult2['ID'] .'<br>';
					$itemdata['PRODUCT_ID'] = $productInfoBySKUId['ID'];
				}
				$itemdata['discprice'] = $item -> getDiscountPrice();
				$pids[$itemdata['PRODUCT_ID']] = 1;
				foreach ($item -> getPropertyCollection() as $property) {
					$itemdata['PROPS'][$property -> getField('CODE')] = [
						'NAME' => $property -> getField('NAME'),
						'CODE' => $property -> getField('CODE'),
						'VALUE' => $property -> getField('VALUE'),
						'SORT' => $property -> getField('SORT'),
						'XML_ID' => $property -> getField('XML_ID')
					];
				}
				$items[$itemdata['PRODUCT_ID']] = $itemdata;
			}
			if (!empty($items)) {
				global $kasses;
				$pics = $sects = [];
				$res = CIblockElement ::GetList([], ['IBLOCK_ID' => SHOP_ID, 'ID' => array_keys($pids)], false, false, ['IBLOCK_ID', 'ID', 'DETAIL_PICTURE', 'DETAIL_PAGE_URL', 'IBLOCK_SECTION_ID']);
				while ($tob = $res -> GetNextElement()) {
					$ob = $tob -> GetFields();
					$ob['PROPS'] = $tob -> GetProperties();
					if (!empty($ob['DETAIL_PICTURE']))
					{
						$pics[$ob['ID']]['PICTURE'] = CFile ::ResizeImageGet($ob['DETAIL_PICTURE'], array('width' => 120, 'height' => '120'), BX_RESIZE_IMAGE_EXACT, true)['src'];
					}
					$pics[$ob['ID']]['URL'] = $ob['DETAIL_PAGE_URL'];
					if (!empty($ob['IBLOCK_SECTION_ID']))
					{
						$sects[$ob['IBLOCK_SECTION_ID']] = $ob['IBLOCK_SECTION_ID'];
						$items[$ob['ID']]['IBLOCK_SECTION_ID'] = $ob['IBLOCK_SECTION_ID'];
					}
					$items[(int)$ob['ID']]['KASSA'] = '4';
				}
				foreach ($items as $key => $value) {
					$items[$key]['PRICE'] = floatval($items[$key]['PRICE']);
					$items[$key]['QUANTITY'] = floatval($items[$key]['QUANTITY']);
					if (!empty($pics[$items[$key]['PRODUCT_ID']]['NAME']))
					{
						$items[$key]['NAME'] = $pics[$items[$key]['PRODUCT_ID']]['NAME'];
					}
					if (!empty($pics[$items[$key]['PRODUCT_ID']]['URL']))
					{
						$items[$key]['URL'] = $pics[$items[$key]['PRODUCT_ID']]['URL'];
					}
					if (!empty($pics[$items[$key]['PRODUCT_ID']]['PICTURE']))
					{
						$items[$key]['PICTURE'] = $pics[$items[$key]['PRODUCT_ID']]['PICTURE'];
					}
				}
				if (!empty($sects)) {
					$res = CIblockSection ::GetList([], ['IBLOCK_ID' => SHOP_ID, 'ID' => $sects], false, ['IBLOCK_ID', 'ID', 'NAME', 'UF_2IP']);
					while ($ob = $res -> GetNext()) {
						if (!empty($ob['UF_2IP'])) {
							$sects[$ob['ID']] = '2';
						} else {
							$sects[$ob['ID']] = '4';
						}
					}
				}
				foreach ($items as $id => $item) {
					if (!empty($sects[$item['IBLOCK_SECTION_ID']])) {
						$items[(int)$id]['KASSA'] = $sects[$item['IBLOCK_SECTION_ID']];
					} else {
						$items[(int)$id]['KASSA'] = '4';
					}
					$kasses[$items[(int)$id]['KASSA']]++;
				}
			}
			return $items;
		}

		$cart = getToCartTwo();
		if (!empty($kasses)) {
			$i = 0;
			foreach ($kasses as $kassa => $kassa_q) {
				if (empty($kassa_q)) {
					continue;
				}
				$i++;
				if($i == 1) {
					?>

					<div class="col-12">
						<h1 style="padding-top: 30px;">В вашей корзине остались товары</h1>
					</div>

					<?
				}
			}
		}
		?>
		<style>
			.body .ves-input {
				height: 30px;
				width: 30px;
				text-align: center;
				float: left;
			}

			.body .ves-input input {
				height: 30px;
				width: 30px;
				text-align: center;
				font-size: 18px;
				border: none;
				color: #333;
				font-family: 'Bebas Neue';
			}

			.body .ves-minus {
				width: 25px;
				height: 30px;
				cursor: pointer;
				position: relative;
				float: left;
			}

			.body .ves-plus {
				width: 25px;
				height: 30px;
				cursor: pointer;
				position: relative;
				float: left;
			}

			.body .product-ves {
				border: solid 1px #ccc;
				height: 32px;
				float: left;
				border-radius: 2px;
				display: inline-block;
			}

			.body .delete-cart {
				height: 25px;
				cursor: pointer;
				opacity: 0.7;
			}

			.ves-minus::before {
				content: '';
				position: absolute;
				display: block;
				width: 9px;
				height: 3px;
				top: 14px;
				background-color: #ccc;
				left: 10px;

			}

			.ves-plus::before {
				content: '';
				position: absolute;
				display: block;
				width: 9px;
				height: 3px;
				top: 14px;
				background-color: #ccc;
				left: 10px;

			}

			.ves-plus::after {
				content: '';
				position: absolute;
				display: block;
				width: 3px;
				height: 9px;
				top: 11px;
				background-color: #ccc;
				left: 13px;

			}
		</style>
		<script>
            function ADJS_CartUpdate() {
                $.post('/cart/ajax.php', {"method": "update"}, function (data) {
                    if (data.status != 'ok') {
                        alertMess('Ошибка');
                        return false;
                    }
                    $('.JSAD_CartArea').html(data.html);
                });
            }

            function ADJS_CartAdd(id, quantity, mess) {
                $.post('/cart/ajax.php', {"method": "add", "id": id, "quantity": quantity}, function (data) {
                    if (data.status != 'ok') {
                        alertMess('Ошибка');
                        return false;
                    }
                    try {
                        $('.JSAD_CartInput[data-id=' + id + ']').val(data.cart['items'][id]['IN_CART']);
                        $('.ADJS_CartSum').html(data.cart['sum']);
                        if (data.cart['sum'] < 1000) {
                            $('.cartbtn').hide();
                            $('.cartalert').show();
                        } else {
                            $('.cartbtn').show();
                            $('.cartalert').hide();
                        }
                    } catch (e) {
                        console.log('Что то не так: ' + e);
                    }
                    if (mess === true) {
                        ADJS_CartAddMess(
                            $('.ADJS_ToCartButton[data-id=' + id + ']').attr('data-name'),
                            $('.ADJS_ToCartButton[data-id=' + id + ']').attr('data-img')
                        );
                    }
                });
            }

            function ADJS_CartSet(id, quantity) {
                $.post('/cart/ajax.php', {"method": "set", "id": id, "quantity": quantity}, function (data) {
                    if (data.status != 'ok') {
                        alertMess('Ошибка');
                        return false;
                    }
                    $('.JSAD_CartInput[data-id=' + id + ']').val(data.cart['items'][id]['IN_CART']);
                    $('.ADJS_CartSum').html(data.cart['sum']);
                    if (data.cart['sum'] < 1000) {
                        $('.cartbtn').hide();
                        $('.cartalert').show();
                    } else {
                        $('.cartbtn').show();
                        $('.cartalert').hide();
                    }
                });
                $('.cartrow').each(function () {
                    var inputs = $(this).find('.JSAD_CartInput');
                    if (inputs.length < 1) {
                        $(this).next().remove();
                        $(this).prev().remove();
                        $(this).remove();
                    } else {
                        var sum = 0;
                        inputs.each(function () {
                            sum += $(this).val() * $(this).attr('data-price');
                            $(this).parent().parent().parent().next().find('b').text(($(this).val() * $(this).attr('data-price')) + ' руб.');
                        });
                        $(this).next().find('.cartsum').text(sum + ' руб.');
                    }
                });
            }

            function ADJS_CartRemove(id, quantity) {
                $.post('/cart/ajax.php', {"method": "remove", "id": id, "quantity": quantity}, function (data) {
                    if (data.status != 'ok') {
                        alertMess('Ошибка');
                        return false;
                    }
                    $('.JSAD_CartInput[data-id=' + id + ']').val(data.cart['items'][id]['IN_CART']);
                    $('.ADJS_CartSum').html(data.cart['sum']);
                    if (data.cart['sum'] < 1000) {
                        $('.cartbtn').hide();
                        $('.cartalert').show();
                    } else {
                        $('.cartbtn').show();
                        $('.cartalert').hide();
                    }
                });
            }

            function ADJS_CartDelete(id) {
                $.post('/cart/ajax.php', {"method": "delete", "id": id}, function (data) {
                    if (data.status != 'ok') {
                        alertMess('Ошибка');
                        return false;
                    }
                    if (data.cart['sum'] < 1000) {
                        $('.cartbtn').hide();
                        $('.cartalert').show();
                    } else {
                        $('.cartbtn').show();
                        $('.cartalert').hide();
                    }
                    $('.cartrow').each(function () {
                        var inputs = $(this).find('.JSAD_CartInput');
                        if (inputs.length < 1) {
                            $(this).next().remove();
                            $(this).prev().remove();
                            $(this).remove();
                        } else {
                            var sum = 0;
                            inputs.each(function () {
                                sum += $(this).val() * $(this).attr('data-price');
                                $(this).parent().parent().parent().next().find('b').text(($(this).val() * $(this).attr('data-price')) + ' руб.');
                            });
                            $(this).next().find('.cartsum').text(sum + ' руб.');
                        }
                    });
                });
            }

            $(document).on('click', '.JSAD_CartAdd', function (e) {
                var id = parseInt($(this).attr('data-id')),
                    max = parseInt($(this).attr('data-max')),
                    step = parseInt($(this).attr('data-step')),
                    val = parseInt($('.JSAD_CartInput[data-id=' + $(this).attr('data-id') + ']').val());
                $('.JSAD_CartInput[data-id=' + $(this).attr('data-id') + ']').val(val + 1);
                ADJS_CartSet(id, val + 1);
            });
            $(document).on('click', '.JSAD_CartRemove', function (e) {
                var id = parseInt($(this).attr('data-id')),
                    min = parseInt($(this).attr('data-min')),
                    step = parseInt($(this).attr('data-step')),
                    val = parseInt($('.JSAD_CartInput[data-id=' + $(this).attr('data-id') + ']').val());
                if (val < 2) val = 2;
                $('.JSAD_CartInput[data-id=' + $(this).attr('data-id') + ']').val(val - 1);
                ADJS_CartSet(id, val - 1);
            });
            $(document).on('click', '.JSAD_CartDelete', function (e) {
                ADJS_CartDelete(parseInt($(this).attr('data-id')));
                $(this).parent().parent().parent().remove();
            });
            $(document).on('change', '.JSAD_CartInput', function (e) {
                $(this).val(Math.ceil($(this).val() / parseInt($(this).attr('data-step'))) * parseInt($(this).attr('data-step')));
                var id = parseInt($(this).attr('data-id')),
                    min = parseInt($(this).attr('data-min')),
                    max = parseInt($(this).attr('data-max')),
                    step = parseInt($(this).attr('data-step')),
                    val = parseInt($(this).val());
                if (max < val + step)
                    ADJS_CartSet(id, (Math.floor(max / step) * step));
                else if (min >= val - step)
                    ADJS_CartSet(id, min);
                else
                    ADJS_CartSet(id, val);
            });

            function alertMess(mess) {
                $('body').append('<div style="position: fixed;left: 0;top: 0;right: 0;bottom: 0;z-index: 10;"><div style="position: absolute;left: 0;top: 0;right: 0;bottom: 0;background-color: #000;opacity: 0.5;" onclick="this.parentNode.parentNode.removeChild(this.parentNode);"></div><div id="modler" style="width: 500px;max-width:100%;position: absolute;left: 50%;top: 50%;transform:translate(-50%,-50%);background-color: #fff;border: solid 1px #ccc;box-shadow: 0 0 40px -6px #555;text-align: center;"><div onclick="this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);" style="position: absolute;right: 0;top: 0;z-index: 2;font-size: 4em;width: 1em;height: 1em;line-height: 33px;color: #333;transform: rotate(45deg);cursor: pointer;">+</div><br><br><br><br><p style="text-align: center;left: 0;right: 0;font-size: 24px;font-family: \'Bebas Neue\';">' + mess + '</p><br><br><br><br></div></div>');
            }
		</script>
		<table class="colored">
			<thead>
				<tr>
					<?
					$bPreviewPicture = false;
					$bDetailPicture = false;
					$imgCount = 0;

					// prelimenary column handling
					foreach ($arResult["GRID"]["HEADERS"] as $id => $arColumn){
						if ($arColumn["id"] == "PROPS")
							$bPropsColumn = true;
						if ($arColumn["id"] == "NOTES")
							$bPriceType = true;
						if ($arColumn["id"] == "PREVIEW_PICTURE")
							$bPreviewPicture = true;
						if ($arColumn["id"] == "DETAIL_PICTURE")
							$bDetailPicture = true;
						if ($arColumn["id"] == "DISCOUNT_PRICE_PERCENT_FORMATED")
							$bDiscountColumn = true;
					}

					if ($bPreviewPicture || $bDetailPicture)
						$bShowNameWithPicture = true;
					


					foreach ($arResult["GRID"]["HEADERS"] as $id => $arColumn):

						if (in_array($arColumn["id"], array("PROPS", "TYPE", "NOTES", "DISCOUNT_PRICE_PERCENT_FORMATED"))) // some values are not shown in columns in this template
							continue;

						if ($arColumn["id"] == "PREVIEW_PICTURE" && $bShowNameWithPicture || $arColumn["id"] == "DETAIL_PICTURE")
							continue;

						if ($arColumn["id"] == "NAME" && $bShowNameWithPicture):
						?>
							<td class="thumb-cell"></td><td class="item name-th">
						<?
							echo GetMessage("SALE_PRODUCTS");
						elseif ($arColumn["id"] == "NAME" && !$bShowNameWithPicture):
						?>
							<td class="item name-th">
						<?
							echo $arColumn["name"];
						elseif ($arColumn["id"] == "PRICE"):
						?>
							<td class="price">
						<?
							echo $arColumn["name"];
						else:
						?>
							<td class="custom">
						<?
							echo $arColumn["name"];
						endif;
						?>
							</td>
					<?endforeach;?>
				</tr>
			</thead>

			<tbody>
				<?foreach ($arResult["GRID"]["ROWS"] as $k => $arData):
					$arItem = (isset($arData["columns"][$arColumn["id"]])) ? $arData["columns"] : $arData["data"];
					//print_r($arData);
					$class_td='';
					if(!$bShowNameWithPicture){
						$class_td="no_img";
					}?>

				<tr>
					<?
					if ($bShowNameWithPicture):
					?>
						<td class="itemphoto thumb-cell">
							<?//print_r($arItem)?>
							<?if( strlen($arItem["PREVIEW_PICTURE_SRC"])>0 ){?>
								<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb"><?endif;?>
									<img src="<?=$arItem["PREVIEW_PICTURE_SRC"]?>" alt="<?=$arItem["NAME"];?>" title="<?=$arItem["NAME"];?>" />
								<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
							<?}elseif( strlen($arItem["DETAIL_PICTURE_SRC"])>0 ){?>
								<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb"><?endif;?>
									<img src="<?=$arItem["DETAIL_PICTURE_SRC"]?>" alt="<?=$arItem["NAME"];?>" title="<?=$arItem["NAME"];?>" />
								<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
							<?}else{?>
								<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb"><?endif;?>
									<img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" width="80" height="80" />
								<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
							<?}?>
							<?if (!empty($arData["data"]["BRAND"])):?>
								<div class="bx_ordercart_brand">
									<img alt="" src="<?=$arData["data"]["BRAND"]?>" />
								</div>
							<?endif;?>
						</td>
					<?
					endif;

					// prelimenary check for images to count column width
					foreach ($arResult["GRID"]["HEADERS"] as $id => $arColumn){
						$arItem = (isset($arData["columns"][$arColumn["id"]])) ? $arData["columns"] : $arData["data"];
						if (is_array($arItem[$arColumn["id"]])){
							foreach ($arItem[$arColumn["id"]] as $arValues){
								if ($arValues["type"] == "image")
									$imgCount++;
							}
						}
					}

					foreach ($arResult["GRID"]["HEADERS"] as $id => $arColumn):
						$class = ($arColumn["id"] == "PRICE_FORMATED") ? "price" : "";
						if (in_array($arColumn["id"], array("PROPS", "TYPE", "NOTES", "DISCOUNT_PRICE_PERCENT_FORMATED"))) // some values are not shown in columns in this template
							continue;

						if ($arColumn["id"] == "PREVIEW_PICTURE" && $bShowNameWithPicture || $arColumn["id"] == "DETAIL_PICTURE")
							continue;

						$arItem = (isset($arData["columns"][$arColumn["id"]])) ? $arData["columns"] : $arData["data"];

						if ($arColumn["id"] == "NAME"):
							$width = 50 - ($imgCount * 20);
						?>
							<td class="item name-cell <?=$class_td;?>" style1="width:<?=$width?>%">

								<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"] ?>"><?endif;?>
									<?=$arItem["NAME"]?>
								<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>

								<div class="bx_ordercart_itemart">
									<?
									if ($bPropsColumn):
										foreach ($arItem["PROPS"] as $val):?>
											<div class="bx_item_detail_size">
												<?echo "<span class='titles'>".$val["NAME"].":</span><div class='values'>".$val["VALUE"]."</div>";?>
											</div>
										<?endforeach;
									endif;
									?>
								</div>
								<?
								if (is_array($arItem["SKU_DATA"])):
									foreach ($arItem["SKU_DATA"] as $propId => $arProp):

										// is image property
										$isImgProperty = false;
										foreach ($arProp["VALUES"] as $id => $arVal)
										{
											if (isset($arVal["PICT"]) && !empty($arVal["PICT"]))
											{
												$isImgProperty = true;
												break;
											}
										}

										$full = (count($arProp["VALUES"]) > 5) ? "full" : "";

										if ($isImgProperty): // iblock element relation property
										?>
											<div class="bx_item_detail_scu_small_noadaptive <?=$full?>">

												<span class="bx_item_section_name_gray">
													<?=$arProp["NAME"]?>:
												</span>

												<div class="bx_scu_scroller_container">

													<div class="bx_scu">
														<ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>" style="width: 200%;margin-left:0%;">
														<?
														foreach ($arProp["VALUES"] as $valueId => $arSkuValue):

															$selected = "";
															foreach ($arItem["PROPS"] as $arItemProp):
																if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"])
																{
																	if ($arItemProp["VALUE"] == $arSkuValue["NAME"])
																		$selected = "class=\"bx_active\"";
																}
															endforeach;
														?>
															<li style="width:10%;" <?=$selected?>>
																<a href="javascript:void(0);">
																	<span style="background-image:url(<?=$arSkuValue["PICT"]["SRC"]?>)"></span>
																</a>
															</li>
														<?
														endforeach;
														?>
														</ul>
													</div>

													<div class="bx_slide_left" onclick="leftScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
													<div class="bx_slide_right" onclick="rightScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
												</div>

											</div>
										<?
										else:
										?>
											<div class="bx_item_detail_size_small_noadaptive <?=$full?>">

												<span class="bx_item_section_name_gray">
													<?=$arProp["NAME"]?>:
												</span>

												<div class="bx_size_scroller_container">
													<div class="bx_size">
														<ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>" style="width: 200%; margin-left:0%;">
															<?
															foreach ($arProp["VALUES"] as $valueId => $arSkuValue):

																$selected = "";
																foreach ($arItem["PROPS"] as $arItemProp):
																	if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"])
																	{
																		if ($arItemProp["VALUE"] == $arSkuValue["NAME"])
																			$selected = "class=\"bx_active\"";
																	}
																endforeach;
															?>
																<li style="width:10%;" <?=$selected?>>
																	<a href="javascript:void(0);"><?=$arSkuValue["NAME"]?></a>
																</li>
															<?
															endforeach;
															?>
														</ul>
													</div>
													<div class="bx_slide_left" onclick="leftScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
													<div class="bx_slide_right" onclick="rightScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
												</div>

											</div>
										<?
										endif;
									endforeach;
								endif;
								?>
							</td>
						<?elseif ($arColumn["id"] == "PRICE_FORMATED"):?>
							<td class="price cost-cell <?=( $bPriceType ? 'notes' : '' );?> <?=$class_td;?>">
								<?//print_r($arItem)?>
								<div class="cost prices clearfix">
									<?if (strlen($arItem["NOTES"]) > 0 && $bPriceType):?>
										<div class="price_name"><?=$arItem["NOTES"]?></div>
									<?endif;?>
									<?if( doubleval($arItem["DISCOUNT_PRICE_PERCENT"]) > 0 && $bDiscountColumn ){?>
										<div class="price"><?=$arItem["PRICE_FORMATED"]?></div>
										<div class="price discount"><strike><?=SaleFormatCurrency(($arItem["DISCOUNT_PRICE"]+$arItem["PRICE"]), $arItem["CURRENCY"])?></strike></div>
										<div class="sale_block">
											<?if($arItem["DISCOUNT_PRICE_PERCENT"] && $arItem["DISCOUNT_PRICE_PERCENT"]<100){?>
												<div class="value">-<?=$arItem["DISCOUNT_PRICE_PERCENT_FORMATED"];?></div>
											<?}?>
											<div class="text"><?=GetMessage("ECONOMY")?> <?=SaleFormatCurrency(round($arItem["DISCOUNT_PRICE"]), $arItem["CURRENCY"]);?></div>
											<div class="clearfix"></div>
										</div>
										<?$bUseDiscount = true;?>
									<?}else{?>
										<div class="price"><?=$arItem["PRICE_FORMATED"];?></div>
									<?}?>
								</div>
							</td>
						<?elseif ($arColumn["id"] == "DISCOUNT"):?>
							<td class="custom <?=$class_td;?>">
								<?=$arItem["DISCOUNT_PRICE_PERCENT_FORMATED"]?>
							</td>
						<?elseif ($arColumn["id"] == "DETAIL_PICTURE" && $bPreviewPicture):?>
							<td class="itemphoto <?=$class_td;?>">
								<div class="bx_ordercart_photo_container">
									<?
									$url = "";
									if ($arColumn["id"] == "DETAIL_PICTURE" && strlen($arData["data"]["DETAIL_PICTURE_SRC"]) > 0)
										$url = $arData["data"]["DETAIL_PICTURE_SRC"];

									if ($url == "")
										$url = SITE_TEMPLATE_PATH."/images/no_photo_medium.png";

									if (strlen($arData["data"]["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arData["data"]["DETAIL_PAGE_URL"] ?>"><?endif;?>
										<div class="bx_ordercart_photo" style="background-image:url('<?=$url?>')"></div>
									<?if (strlen($arData["data"]["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
								</div>
							</td>
						<?elseif (in_array($arColumn["id"], array("QUANTITY", "WEIGHT_FORMATED", "SUM"))):?>
							<td class="custom <?=strtolower($arColumn["id"]);?> <?=$class_td;?>">
								<?if($arColumn["id"]=="SUM"){?>
									<div class="cost prices"><div class="price"><?=$arItem[$arColumn["id"]]?></div></div>
								<?}else{?>
									<?=$arItem[$arColumn["id"]]?>
								<?}?>
							</td>
						<?else: // some property value

							if (is_array($arItem[$arColumn["id"]])):

								foreach ($arItem[$arColumn["id"]] as $arValues)
									if ($arValues["type"] == "image")
										$columnStyle = "width:20%";
							?>
							<td class="custom <?=$class_td;?>" style="<?=$columnStyle?>">
								<?
								foreach ($arItem[$arColumn["id"]] as $arValues):
									if ($arValues["type"] == "image"):
									?>
										<div class="bx_ordercart_photo_container">
											<div class="bx_ordercart_photo" style="background-image:url('<?=$arValues["value"]?>')"></div>
										</div>
									<?
									else: // not image
										echo $arValues["value"]."<br/>";
									endif;
								endforeach;
								?>
							</td>
							<?
							else: // not array, but simple value
							?>
							<td class="custom <?=$class_td;?>" style="<?=$columnStyle?>">
								<?
									echo $arItem[$arColumn["id"]];
								?>
							</td>
							<?
							endif;
						endif;
					endforeach;
					?>
				</tr>
				<?endforeach;?>
			</tbody>
		</table>
	</div>

	<div class="bx_ordercart_order_pay">
		<div class="bx_ordercart_order_pay_right">
			<table class="bx_ordercart_order_sum">
				<tbody>
					<tr>
						<td class="custom_t1" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_WEIGHT_SUM")?></td>
						<td class="custom_t2" class="price"><?=$arResult["ORDER_WEIGHT_FORMATED"]?></td>
					</tr>
					<tr>
						<td class="custom_t1" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_SUMMARY")?></td>
						<td class="custom_t2" class="price"><?=$arResult["ORDER_PRICE_FORMATED"]?></td>
					</tr>
					<?
					if (doubleval($arResult["DISCOUNT_PRICE"]) > 0)
					{
						?>
						<tr>
							<td class="custom_t1" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_DISCOUNT")?><?if (strLen($arResult["DISCOUNT_PERCENT_FORMATED"])>0):?> (<?echo $arResult["DISCOUNT_PERCENT_FORMATED"];?>)<?endif;?>:</td>
							<td class="custom_t2" class="price"><?echo $arResult["DISCOUNT_PRICE_FORMATED"]?></td>
						</tr>
						<?
					}
					if(!empty($arResult["TAX_LIST"]))
					{
						foreach($arResult["TAX_LIST"] as $val)
						{
							?>
							<tr>
								<td class="custom_t1" colspan="<?=$colspan?>" class="itog"><?=$val["NAME"]?> <?=$val["VALUE_FORMATED"]?>:</td>
								<td class="custom_t2" class="price"><?=$val["VALUE_MONEY_FORMATED"]?></td>
							</tr>
							<?
						}
					}
					if (doubleval($arResult["DELIVERY_PRICE"]) > 0)
					{
						?>
						<tr>
							<td class="custom_t1" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_DELIVERY")?></td>
							<td class="custom_t2" class="price"><?=$arResult["DELIVERY_PRICE_FORMATED"]?></td>
						</tr>
						<?
					}
					if (strlen($arResult["PAYED_FROM_ACCOUNT_FORMATED"]) > 0)
					{
						?>
						<tr>
							<td class="custom_t1" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_PAYED")?></td>
							<td class="custom_t2" class="price"><?=$arResult["PAYED_FROM_ACCOUNT_FORMATED"]?></td>
						</tr>
						<?
					}

					if ($bUseDiscount):?>
						<tr>
							<td class="custom_t1 fwb" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_IT")?></td>
							<td class="custom_t2 fwb" class="price">
								<div class="price"><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></div>
								<strike><?=$arResult["PRICE_WITHOUT_DISCOUNT"]?></strike>
							</td>
						</tr>
					<?else:?>
						<tr>
							<td class="custom_t1 fwb" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_IT")?></td>
							<td class="custom_t2 fwb" class="price"><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></td>
						</tr>
					<?
					endif;
					?>
				</tbody>
			</table>
			<div style="clear:both;"></div>
		</div>
		<div style="clear:both;"></div>
		<div class="bx_section_bottom">
			<h3><?=GetMessage("SOA_TEMPL_SUM_COMMENTS")?></h3>
			<div class="bx_block w100"><textarea name="ORDER_DESCRIPTION" id="ORDER_DESCRIPTION" style="max-width:100%;min-height:120px"><?=$arResult["USER_VALS"]["ORDER_DESCRIPTION"]?></textarea></div>
			<input type="hidden" name="" value="">
			<div style="clear: both;"></div><br />
		</div>
	</div>
</div>
