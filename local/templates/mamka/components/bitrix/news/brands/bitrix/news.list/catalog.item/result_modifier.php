<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

$ids = array();
foreach ($arResult['ITEMS'] as $item) {
    $ids[] = $item['ID'];
    $iblockID = $item['IBLOCK_ID'];
}


$offers = array();
CModule::IncludeModule('catalog');
$arInfo = CCatalogSKU::GetInfoByProductIBlock($iblockID);
print_r($arInfo);
if (is_array($arInfo)) {
    $rsOffers = CIBlockElement::GetList(
        array(),
        array(
            'IBLOCK_ID' => $arInfo['IBLOCK_ID'],
            'PROPERTY_' . $arInfo['SKU_PROPERTY_ID'] => $ids
        ),
        false,
        false,
        array(
            'ID',
            'NAME',
            'PREVIEW_PICTURE',
            'CATALOG_QUANTITY',
            'DETAIL_PAGE_URL',
            'PROPERTY_' . $arInfo['SKU_PROPERTY_ID']
        )
    );
    while ($arOffer = $rsOffers->GetNext()) {
        $arOffer['PRICES'] = CCatalogProduct::GetOptimalPrice($arOffer['ID'], 1);
        if(!is_array($arOffer["PREVIEW_PICTURE"])) {
            $arOffer['PREVIEW_PICTURE'] = CFile::GetPath($arOffer["PREVIEW_PICTURE"]);
        } else {
            $arOffer['PREVIEW_PICTURE'] = $arOffer["PREVIEW_PICTURE"]['SRC'];
        }
        $offers[$arOffer['PROPERTY_' . $arInfo['SKU_PROPERTY_ID'] . '_VALUE']][] = $arOffer;
    }
}

$arResult['CONVERTED_ITEMS'] = $arResult['ITEMS'];



foreach ($arResult['CONVERTED_ITEMS'] as &$item) {

	echo "news/brands/bitrix/news.list/catalog.item/result_modifier.php<br>";

	if(!is_array($item["DETAIL_PAGE_URL"])) {
		$item['URL'] = $item["DETAIL_PAGE_URL"];
	} else {
		$item['URL'] = $item["DETAIL_PAGE_URL"];
	}
    if(!is_array($item["PREVIEW_PICTURE"])) {
        $item['PREVIEW_PICTURE'] = CFile::GetPath($item["PREVIEW_PICTURE"]);
    } else {
        $item['PREVIEW_PICTURE'] = $item["PREVIEW_PICTURE"]['SRC'];
    }
    if (count($offers[$item['ID']])) {
        $item['OFFERS'] = $offers[$item['ID']];
		//print_r($item['OFFERS']);
    } else {
        $item['PRICES'] = CCatalogProduct::GetOptimalPrice($item['ID'], 1);
		//print_r($item['PRICE']);
    }
}

