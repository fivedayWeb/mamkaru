<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

$APPLICATION->SetTitle("Корзина");
define("SHOP_ID", "622");
$kasses = ['4'=>0, '1'=>0, '2'=>0, '3'=>0];
function getToCart(){
	$basket = \Bitrix\Sale\Basket::loadItemsForFUser(
		\Bitrix\Sale\Fuser::getId(),
		\Bitrix\Main\Context::getCurrent()->getSite()
	);
	$basketItems = $basket->getBasketItems();
	$items = $pids = [];
	foreach ($basketItems as $item)	{
		$itemdata = [];
		foreach ($item->getAvailableFields() as $fieldcode){
			$itemdata[$fieldcode] = $item->getField($fieldcode);
		}
		$productInfoBySKUId = \CCatalogSku::GetProductInfo($itemdata['PRODUCT_ID']);
		if (is_array($productInfoBySKUId)){
			$itemdata['PRODUCT_ID'] = $productInfoBySKUId['ID'];
		}
		$itemdata['discprice'] = $item->getDiscountPrice();
		$pids[$itemdata['PRODUCT_ID']] = 1;
		foreach ($item->getPropertyCollection() as $property) {
			$itemdata['PROPS'][$property->getField('CODE')] = [
				'NAME'=>$property->getField('NAME'),
				'CODE'=>$property->getField('CODE'),
				'VALUE'=>$property->getField('VALUE'),
				'SORT'=>$property->getField('SORT'),
				'XML_ID'=>$property->getField('XML_ID')
			];
		}
		$items[$itemdata['PRODUCT_ID']] = $itemdata;
	}
	if(!empty($items)){
		global $kasses;
		$pics = $sects = [];
		$res = CIblockElement::GetList([],['IBLOCK_ID'=>SHOP_ID,'ID'=>array_keys($pids)],false,false,['IBLOCK_ID','ID','DETAIL_PICTURE','DETAIL_PAGE_URL','IBLOCK_SECTION_ID']);

		while($tob=$res->GetNextElement()){
			$ob = $tob->GetFields();
			$ob['PROPS'] = $tob->GetProperties();

			$mxResult = \CCatalogSku::GetProductInfo($ob['ID']);
			if (is_array($mxResult)){
				//echo 'ID товара = '.$mxResult['ID'];
				$ob['ID'] = $mxResult['ID'];
			}
			if(!empty($ob['DETAIL_PICTURE'])){
				$pics[$ob['ID']]['PICTURE'] = CFile::ResizeImageGet($ob['DETAIL_PICTURE'], array('width'=>120, 'height'=>'120'), BX_RESIZE_IMAGE_EXACT, true)['src'];
			}
			$pics[$ob['ID']]['URL'] = $ob['DETAIL_PAGE_URL'];
			if(!empty($ob['IBLOCK_SECTION_ID'])){
				$sects[$ob['IBLOCK_SECTION_ID']] = $ob['IBLOCK_SECTION_ID'];
				$items[$ob['ID']]['IBLOCK_SECTION_ID'] = $ob['IBLOCK_SECTION_ID'];
			}
			$items[(int)$ob['ID']]['KASSA'] = '4';
		}



		foreach ($items as $key => $value) {
			$items[$key]['PRICE'] = floatval($items[$key]['PRICE']);
			$items[$key]['QUANTITY'] = floatval($items[$key]['QUANTITY']);
			if(!empty($pics[$items[$key]['PRODUCT_ID']]['NAME'])){
				$items[$key]['NAME'] = $pics[$items[$key]['PRODUCT_ID']]['NAME'];
			}
			if(!empty($pics[$items[$key]['PRODUCT_ID']]['URL'])){
				$items[$key]['URL'] = $pics[$items[$key]['PRODUCT_ID']]['URL'];
			}
			if(!empty($pics[$items[$key]['PRODUCT_ID']]['PICTURE'])){
				$items[$key]['PICTURE'] = $pics[$items[$key]['PRODUCT_ID']]['PICTURE'];
			}
		}
		if(!empty($sects)){
			$res = CIblockSection::GetList([],['IBLOCK_ID'=>SHOP_ID,'ID'=>$sects],false,['IBLOCK_ID','ID','NAME','UF_2IP']);
			while($ob = $res->GetNext()){
				if(!empty($ob['UF_2IP'])){
					$sects[$ob['ID']] = '2';
				}
				else {
					$sects[$ob['ID']] = '4';
				}
			}
		}
		foreach ($items as $id => $item) {
			if(!empty($sects[$item['IBLOCK_SECTION_ID']])){
				$items[(int)$id]['KASSA'] = $sects[$item['IBLOCK_SECTION_ID']];
			}
			else {
				$items[(int)$id]['KASSA'] = '4';
			}
			$kasses[$items[(int)$id]['KASSA']]++;
		}
	}
	return $items;
}

$cart = getToCart();
var_dump($kasses);
if (!empty($kasses) && !empty($cart)) {
	$i=0;
	foreach ($kasses as $kassa => $kassa_q) {
		if(empty($kassa_q)){
			continue;
		}
		$i++;?>
		<h1>Заказ #<?=$i?><?/*=($kassa!=4?'. Товары партнера':'')*/?></h1><div class="cartrow"><?
			$sum = 0;
			foreach ($cart as $id => $item) {
				if($item['KASSA']!=$kassa){
					continue;
				}
				$sum += $item['PRICE']*$item['QUANTITY'];
				?>
				<div class="col-12" style="padding: 10px 0;">
					<div class="row align-items-center" style="border-bottom: solid 1px #ccc;">
						<div class="col-3 col-md-1">
							<img src="<?=$item['PICTURE']?>" alt="">
						</div>
						<div class="col-9 col-md-4">
							<a href="<?=$item['URL']?>" style="font-size: 16px;"><?=$item['NAME']?></a>
						</div>
						<div class="col-4 col-md-2" style="text-align: center;font-size: 18px;">
							<b><?=$item['PRICE']?> руб.</b><br>
							<span style="font-size: 14px;">Цена за 1 <?=$item['MEASURE_NAME']?></span>
						</div>
						<div class="col-4 col-md-2">
							<div class="product-ves fln">
								<div class="ves-minus intovar JSAD_CartRemove" data-min="1" data-id="<?=$id?>" data-step="1"></div>
								<div class="ves-input">
									<input data-id="<?=$id?>" data-max="1000" data-price="<?=$item['PRICE']?>" data-step="1" data-min="1" type="text" class="ves-input intovar JSAD_CartInput active" value="<?=$item['QUANTITY']?>">
								</div>
								<div class="ves-plus intovar JSAD_CartAdd" data-id="<?=$id?>" data-step="1" data-max="1000000"></div>
							</div>
						</div>
						<div class="col-4 hidden-xs col-md-2" style="text-align: center;font-size: 18px;">
							<b><?=$item['PRICE']*$item['QUANTITY']?> руб.</b>
						</div>
						<div class="col-4 col-md-1" style="text-align: center;">
							<img class="delete-cart JSAD_CartDelete" data-id="<?=$id?>" src="https://tvoisadrus.ru/img/del.png" alt="">
						</div>
					</div>
				</div>
			<?}?>
		</div>
		<div class="col-12" style="padding-bottom: 40px;padding-top: 20px">
			<div class="row align-items-center" style="text-align: right;">
				<div class="col-6 col-sm-9 col-md-10">
					<b class="cartsum" style="font-size: 16px"><?=$sum?> руб.</b>
				</div>
				<div class="col-6 col-sm-3 col-md-2 bx-basket bx-blue">
					<a href="/order/?SITE_ID=<?=$kassa?>" class="btn btn-lg btn-default waves-effect ">Оформить заказ</a>
				</div>
			</div>
		</div>
	<?}?>
	<div class="bx-sbb-empty-cart-container" style="text-align: center;display: none;">
		<div class="bx-sbb-empty-cart-image">
			<img style="width: 250px" src="/bitrix/components/bitrix/sale.basket.basket/templates/.default/images/empty_cart.svg" alt="">
		</div>
		<div style="font-size: 18px;padding-top: 20px;" class="bx-sbb-empty-cart-text">Ваша корзина пуста</div>
		<div class="bx-sbb-empty-cart-desc" style="padding-top: 20px;">
			<a style="font-size: 18px;" href="/">Нажмите здесь</a>, чтобы продолжить покупки		</div>
	</div>
<?}
else {?>
	<div class="bx-sbb-empty-cart-container" style="text-align: center;">
		<div class="bx-sbb-empty-cart-image">
			<img style="width: 250px" src="/bitrix/components/bitrix/sale.basket.basket/templates/.default/images/empty_cart.svg" alt="">
		</div>
		<div style="font-size: 18px;padding-top: 20px;" class="bx-sbb-empty-cart-text">Ваша корзина пуста</div>
		<div class="bx-sbb-empty-cart-desc" style="padding-top: 20px;">
			<a style="font-size: 18px;" href="/">Нажмите здесь</a>, чтобы продолжить покупки		</div>
	</div>
<?}
use Bitrix\Main\UI\Extension;
Extension::load('ui.bootstrap4');
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.bigdata.products",
	CMShop::checkVersionExt("mshop"),
	array(
		"LINE_ELEMENT_COUNT" => "5",
		"TEMPLATE_THEME" => "blue",
		"DETAIL_URL" => "",
		"BASKET_URL" => SITE_DIR."cart/",
		"ACTION_VARIABLE" => "ACTION",
		"PRODUCT_ID_VARIABLE" => "ID",
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"ADD_PROPERTIES_TO_BASKET" => "N",
		"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"SHOW_PRICE_COUNT" => "1",
		"PRODUCT_SUBSCRIPTION" => "N",
		"PRICE_VAT_INCLUDE" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"SHOW_NAME" => "Y",
		"SHOW_IMAGE" => "Y",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_NOT_AVAILABLE" => $arParams["MESS_NOT_AVAILABLE"],
		"PAGE_ELEMENT_COUNT" => "20",
		"SHOW_FROM_SECTION" => "N",
		"IBLOCK_TYPE" => "aspro_mshop_catalog",
		"IBLOCK_ID" => "622",
		"DEPTH" => "2",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CACHE_GROUPS" => "N",
		"HIDE_NOT_AVAILABLE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"CURRENCY_ID" => $arParams["CURRENCY_ID"],
		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"SECTION_ELEMENT_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		"SECTION_ELEMENT_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"ID" => "",
		"={\"PROPERTY_CODE_\".\$arParams[\"IBLOCK_ID\"]}" => $arParams["LIST_PROPERTY_CODE"],
		"={\"CART_PROPERTIES_\".\$arParams[\"IBLOCK_ID\"]}" => $arParams["PRODUCT_PROPERTIES"],
		"RCM_TYPE" => "bestsell",
		"={\"OFFER_TREE_PROPS_\".\$ElementOfferIblockID}" => $arParams["OFFER_TREE_PROPS"],
		"={\"ADDITIONAL_PICT_PROP_\".\$ElementOfferIblockID}" => $arParams["OFFER_ADD_PICT_PROP"],
		"COMPONENT_TEMPLATE" => "basket",
		"SHOW_PRODUCTS_622" => "Y",
		"PROPERTY_CODE_622" => array(
			0 => "",
			1 => "",
		),
		"CART_PROPERTIES_622" => array(
		),
		"ADDITIONAL_PICT_PROP_622" => "MORE_PHOTO",
		"LABEL_PROP_622" => "-",
		"PROPERTY_CODE_623" => array(
			0 => "",
			1 => "",
		),
		"CART_PROPERTIES_623" => array(
			0 => "undefined",
		),
		"ADDITIONAL_PICT_PROP_623" => "MORE_PHOTO",
		"OFFER_TREE_PROPS_623" => array(
			0 => "-",
		),
		"DISPLAY_COMPARE" => "Y"
	),
	false
);
?>
<? require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php'); ?>