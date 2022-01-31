<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
use \Bitrix\Main\Localization\Loc;
function getMyCart(){
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
$crt = getMyCart();
Loc::loadMessages(__FILE__);
?>
<?if(empty($crt[0])||empty($crt[1])){?>
<span>Корзина пуста</span>
<?}else{?>
<span><?=$crt[0]?> на <?=$crt[1]?> руб.</span>
<?}?>