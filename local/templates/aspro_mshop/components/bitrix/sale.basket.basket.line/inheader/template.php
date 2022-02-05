<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php
use \Bitrix\Main\Localization\Loc;
function getMyCartTwo(){
	$basket = \Bitrix\Sale\Basket::loadItemsForFUser(
	    \Bitrix\Sale\Fuser::getId(), 
	    \Bitrix\Main\Context::getCurrent()->getSite()
	);
	$basketItems = $basket->getBasketItems();
	$items = 0;
	$sum = 0;
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
		foreach ($item->getPropertyCollection() as $property) {
			$itemdata['PROPS'][$property->getField('CODE')] = [
				'NAME'=>$property->getField('NAME'),
				'CODE'=>$property->getField('CODE'),
				'VALUE'=>$property->getField('VALUE'),
				'SORT'=>$property->getField('SORT'),
				'XML_ID'=>$property->getField('XML_ID')
			];
		}
		$items++;
		$sum += $itemdata['PRICE']*$itemdata['QUANTITY'];
	}
	return [$items,$sum];
}
$crt = getMyCartTwo();
Loc::loadMessages(__FILE__);
?>

<div class="header-basket">
	<a class="header-basket__link" href="<?=$arParams['PATH_TO_BASKET']?>">
		<svg class="svg-icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-cart-3"></use></svg>
		<div class="header-basket__info">
			<div class="header-basket__title opensansbold" style="padding-top: 10px;"><?=Loc::getMessage('RSGOPRO.SMALLBASKET_TITLE')?></div>
			<div id="basketinfo" class="header-basket__descr">
				<?if(empty($crt[0])||empty($crt[1])){?>
					<span>Корзина пуста</span>
				<?}else{?>
					<span><?=$crt[0]?> на <?=$crt[1]?> руб.</span>
				<?}?>
			</div>
		</div>
	</a>
</div>
