<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

require_once('api/init.php');

COption::SetOptionString("catalog", "DEFAULT_SKIP_SOURCE_CHECK", "Y");

// \Bitrix\Main\EventManager::getInstance()->addEventHandler(
// 	'sale',
// 	'onSaleDeliveryServiceCalculate',
// 	function($event) {
// 		$result = $event->getParameter('RESULT');

// 		$result->setDeliveryPrice(0);

// 		return new \Bitrix\Main\EventResult(
// 			\Bitrix\Main\EventResult::SUCCESS,
// 			['RESULT' => $result]
// 		);
// 	}
// );


// local/php_interface/init.php
// Добавляем информацию внутри заказа
\Bitrix\Main\EventManager::getInstance()->addEventHandler('sale', 'onSaleAdminOrderInfoBlockShow', ['DivasoftFixSyncOrderInfo', 'onSaleAdminOrderInfoBlockShow']);
// Заполняем колонки в списке заказов
\Bitrix\Main\EventManager::getInstance()->addEventHandler("main",  "OnAdminListDisplay", ['DivasoftFixSyncOrderInfo', 'onAdminListDisplay']);
\Bitrix\Main\EventManager::getInstance()->addEventHandler("main",  "OnAdminSubListDisplay", ['DivasoftFixSyncOrderInfo', 'onAdminListDisplay']);
class DivasoftFixSyncOrderInfo {
	static function getSystemDeliveryNameByOrderD7($order) {
		$shipmentCollection = $order->getShipmentCollection();
		$shipmenName = "Не выбрана";
		$systemShipmentItemCollection = $shipmentCollection->getSystemShipment()->getShipmentItemCollection();
		foreach ($shipmentCollection as $obShipment) {
			if ($obShipment->isSystem()) {
				$arShipment = $obShipment->getFields()->getValues();
				$shipmenName = $arShipment['DELIVERY_NAME'];
			}
		}
		return $shipmenName;
	}
	function onSaleAdminOrderInfoBlockShow(\Bitrix\Main\Event $event) {
		$order = $event->getParameter("ORDER");
		$shipmenName = self::getSystemDeliveryNameByOrderD7($order);
		return new \Bitrix\Main\EventResult(
			\Bitrix\Main\EventResult::SUCCESS, array(
				array('TITLE' => 'Доставка:', 'VALUE' => $shipmenName, 'ID' => 'dvs_system_shipment'),
			), 'sale'
		);
	}
	function onAdminListDisplay(&$list) {
		if ($list->table_id == "tbl_sale_order") {
			foreach ($list->aRows as &$row) {
				foreach ($row->aFields as $key => &$val) {
					$order = false;
					if ($key == "DELIVERY") {
						if (!$val['view']['value']) {
							if (!$order) {
								$order = \Bitrix\Sale\Order::load($row->arRes['ID']);
							}
							$val['view']['value'] = self::getSystemDeliveryNameByOrderD7($order);
						}
					}
				}
			}
		}
	}
}

AddEventHandler( "iblock", "OnAfterIBlockElementAdd", array( "aspro_import", "FillTheBrands" ) );
AddEventHandler( "iblock", "OnAfterIBlockElementUpdate", array( "aspro_import", "FillTheBrands" ) );
	 class aspro_import {
	 	function FillTheBrands( &$arFields ){

	 		$arCatalogID=array(622);
			/*$arFields = Array(
			  "PROPERTY_TYPE" => "S", //ставим L если нужна Список
			  "IBLOCK_ID" => $arCatalogID //номер инфоблока
  			);

			$ibp = new CIBlockProperty;
			if(!$ibp->Update(33749, $arFields)) // где 33749 это номер свойства (ID) в инфоблоке
    			echo $ibp->LAST_ERROR; //выведем ошибку если ничего не получилось
*/
	 		if( in_array($arFields['IBLOCK_ID'], $arCatalogID) ){
				if($arFields["ID"]>0){
					 AddMessage2Log("Запись с кодом ".$arFields["ID"]." добавлена.");
				}else{
					 AddMessage2Log("Ошибка добавления записи (".$arFields["RESULT_MESSAGE"].").");
				}
	 			$arItem = CIBlockElement::GetList( false, array( 'IBLOCK_ID' => $arFields['IBLOCK_ID'], 'ID' => $arFields['ID'] ), false, false, array( 'ID', 'PROPERTY_CML2_MANUFACTURER' ) )->fetch();

				if( $arItem['PROPERTY_CML2_MANUFACTURER_VALUE'] ){
	 				$arBrand = CIBlockElement::GetList( false, array( 'IBLOCK_ID' => 617, 'NAME' => $arItem['PROPERTY_CML2_MANUFACTURER_VALUE'] ) )->fetch();
	 				if( $arBrand ){
	 					CIBlockElement::SetPropertyValuesEx( $arFields['ID'], false, array( 'BRAND' => $arBrand['ID'] ) );
	 				}else{
	 					$el = new CIBlockElement;
	 					$arParams = array( "replace_space" => "-", "replace_other" => "-" );
	 					$id = $el->Add( array(
	 						'ACTIVE' => 'Y',
	 						'NAME' => $arItem['PROPERTY_CML2_MANUFACTURER_VALUE'],
	 						'IBLOCK_ID' => 617,
	 						'CODE' => Cutil::translit( $arItem['PROPERTY_CML2_MANUFACTURER_VALUE'], "ru", $arParams )
	 					) ); 			    
	 					if( $id ){
	 						CIBlockElement::SetPropertyValuesEx( $arFields['ID'], false, array( 'BRAND' => $id ) );
	 					}else{
	 						echo $el->LAST_ERROR;
	 					}
	 				}
	 			}
	 		}
	 	}
}

\Bitrix\Main\EventManager::getInstance()->addEventHandler(
	'sale',
	'OnBasketAdd',
	'multisitebasket'
);

function multisitebasket($ID,$arFields){
	CModule::IncludeModule('iblock');
	$res = CIblockElement::GetList([],['IBLOCK_ID'=>622,'ID'=>$arFields['PRODUCT_ID']],false,false,['IBLOCK_ID','ID','DETAIL_PICTURE','DETAIL_PAGE_URL','IBLOCK_SECTION_ID']);
	if($tob = $res->GetNext()){
		if(empty($tob['IBLOCK_SECTION_ID'])){
			$sid = '4';
		}
		else {
			$res = CIblockSection::GetList([],['IBLOCK_ID'=>622,'ID'=>$tob['IBLOCK_SECTION_ID']],false,['IBLOCK_ID','ID','NAME','UF_2IP']);
			if($ob = $res->GetNext()){
				if(!empty($ob['UF_2IP'])){
					$sid = '2';
				}
				else {
					$sid = '4';
				}
			}
		}
		global $DB;
		$DB->query('UPDATE b_sale_basket SET ORDER_ID = "'.$sid.'" WHERE ID = '.$ID);
	}
}



\Bitrix\Main\EventManager::getInstance()->addEventHandler(
	'sale',
	'OnOrderAdd',
	'multisiteorder'
);

function multisiteorder($ID,$arFields){
	global $DB;
	foreach ($arFields['BASKET_ITEMS'] as $i => $item) {
		$arFields['BASKET_ITEMS'][$i]['ORDER_ID'] = $ID;
		$DB->query('UPDATE b_sale_basket SET ORDER_ID = '.$ID.' WHERE ID = '.$item['ID']);
	}
}