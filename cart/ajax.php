<?require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
Bitrix\Main\Loader::includeModule("catalog");
CModule::IncludeModule("sale");
CModule::IncludeModule("iblock");
use Bitrix\Main\Loader;
Loader::includeModule("highloadblock");
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
use Bitrix\Sale\Discount;
define("SHOP_ID", "119");

function clearToCart(){
	CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());
	return ['status'=>true,'cart'=>[]];
}

function addToCart($id,$q=1){
	$r = Bitrix\Catalog\Product\Basket::addProduct(['PRODUCT_ID' => $id,'QUANTITY' => $q]);
	return setToCart($id,$q);
}
//clearToCart();
// function setToCart($id,$q){
// 	$cart = getToCart();
// 	$basket = \Bitrix\Sale\Basket::loadItemsForFUser(
// 	    \Bitrix\Sale\Fuser::getId(),
// 	    \Bitrix\Main\Context::getCurrent()->getSite()
// 	);
// 	$discounts = Discount::loadByBasket($basket);
// 	$basket->refreshData(['PRICE', 'COUPONS']);
// 	$discounts->calculate();
// 	$discountResult = $discounts->getApplyResult();
// 	$discountResult = $discountResult['PRICES']['BASKET'];
// 	$basketItems = $basket->getBasketItems();
// 	$no = true;
// 	if(!empty($basketItems)){
// 		foreach ($basketItems as $item)	{
// 			$itemdata = [];
// 			foreach ($item->getAvailableFields() as $fieldcode){
// 				$itemdata[$fieldcode] = $item->getField($fieldcode);
// 			}
// 			foreach ($item->getPropertyCollection() as $property) {
// 				$itemdata['BASKET_ID'] = $property->getField('BASKET_ID');
// 				$itemdata['PROPS'][$property->getField('CODE')] = [
// 					'NAME'=>$property->getField('NAME'),
// 					'CODE'=>$property->getField('CODE'),
// 					'VALUE'=>$property->getField('VALUE'),
// 					'SORT'=>$property->getField('SORT'),
// 					'XML_ID'=>$property->getField('XML_ID')
// 				];
// 			}
// 			if($itemdata['PRODUCT_ID']==$id) {
// 				echo '<pre>';
// 				print_r($q);
// 				echo '</pre>';
// 				echo '<pre>';
// 				print_r($itemdata);
// 				echo '</pre>';
// 				if((int)$q>0){
// 					$item->setField('QUANTITY', (int)$q);
// 				}
// 				else{
// 					$item->delete();
// 				}
// 				$no = false;
// 			}
// 		}
// 		$refreshStrategy = \Bitrix\Sale\Basket\RefreshFactory::create(\Bitrix\Sale\Basket\RefreshFactory::TYPE_FULL);
// 		$basket->refresh($refreshStrategy);
// 		$basket->save();
// 	}
// 	if($no && empty($customPrice)){
// 		return addToCart($id,$q);
// 	}
// 	return ['status'=>true,'cart'=>getToCart()];
// }

function setToCart($id,$q){
	$basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());
	$basketItems = $basket->getBasketItems();
	$no = true;
	global $DB;
	if(!empty($basketItems)){
		foreach ($basketItems as $item)	{
			$itemdata = [];
			foreach ($item->getAvailableFields() as $fieldcode){
				$itemdata[$fieldcode] = $item->getField($fieldcode);
			}
			foreach ($item->getPropertyCollection() as $property) {
				$itemdata['BASKET_ID'] = $property->getField('BASKET_ID');
			}
			if($itemdata['PRODUCT_ID']==$id) {
				if((int)$q>0){
					$DB->query('UPDATE b_sale_basket SET QUANTITY = '.(int)$q.' WHERE ID = '.(int)$itemdata['BASKET_ID']);
				}
				else{
					$DB->query('DELETE FROM b_sale_basket WHERE ID = '.(int)$itemdata['BASKET_ID']);
				}
				$no = false;
			}
		}
	}
	if($no){
		return addToCart($id,$q);
	}
	return ['status'=>'ok','cart'=>getToCart()];
}

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
		$items[] = $itemdata;
	}

	if(!empty($items)){
		$pics = [];
		$res = CIblockElement::GetList([],['IBLOCK_ID'=>SHOP_ID,'ID'=>array_keys($pids)],false,false,['IBLOCK_ID','ID','DETAIL_PICTURE','DETAIL_PAGE_URL']);
		while($tob=$res->GetNextElement()){
			$ob = $tob->GetFields();
			$ob['PROPS'] = $tob->GetProperties();

			if(!empty($ob['DETAIL_PICTURE'])){
				$pics[$ob['ID']]['PICTURE'] = CFile::ResizeImageGet($ob['DETAIL_PICTURE'], array('width'=>120, 'height'=>'120'), BX_RESIZE_IMAGE_EXACT, true)['src'];
			}
			$pics[$ob['ID']]['URL'] = $ob['DETAIL_PAGE_URL'];
		}
		foreach ($items as $key => $value) {
			$items[$key]['PRICE'] = floatval($items[$key]['PRICE']);
			$items[$key]['QUANTITY'] = floatval($items[$key]['QUANTITY']);
			if(!empty($pics[$items[$key]['PRODUCT_ID']]['NAME']))
				$items[$key]['NAME'] = $pics[$items[$key]['PRODUCT_ID']]['NAME'];
			if(!empty($pics[$items[$key]['PRODUCT_ID']]['URL']))
				$items[$key]['URL'] = $pics[$items[$key]['PRODUCT_ID']]['URL'];
			if(!empty($pics[$items[$key]['PRODUCT_ID']]['PICTURE']))
				$items[$key]['PICTURE'] = $pics[$items[$key]['PRODUCT_ID']]['PICTURE'];
		}
	}
	return $items;
}
Header('Content-type: application/json');

if($_REQUEST['method']=='add'){
	echo json_encode(addToCart($_REQUEST['id'],$_REQUEST['quantity']));
}
elseif($_REQUEST['method']=='set'){
	echo json_encode(setToCart($_REQUEST['id'],$_REQUEST['quantity']));
}
elseif($_REQUEST['method']=='delete'){
	echo json_encode(setToCart($_REQUEST['id'],0));
}
elseif($_REQUEST['method']=='get'){
	echo json_encode(['status'=>'ok','cart'=>getToCart()]);
}
elseif($_REQUEST['method']=='clear'){
	echo json_encode(clearToCart());
}
?>