<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

$ids = array();
$favorite_ids = array();
foreach ($arResult['ITEMS'] as $item) {
    $ids[] = $item['ID'];
    $favorite_ids[] = $item['FAVORITE_DATA']['ID'];
    $iblockID = $item['IBLOCK_ID'];
}

$obCache = new \CPHPCache();
$cachePath = '/sale/Bitrix_Saybert_Entity_FavoriteProductTable_getFavoriteProducts/result_modifier';
if ($obCache->InitCache(36000, implode(',', $favorite_ids), $cachePath)) {
    $arResult['CONVERTED_ITEMS'] = $obCache->GetVars();
} elseif ($obCache->StartDataCache()) {
    $offers = array();
    CModule::IncludeModule('catalog');
    $arInfo = CCatalogSKU::GetInfoByProductIBlock($iblockID);
    if (is_array($arInfo)) {
        $rsOffers = CIBlockElement::GetList(
            array(),
            array(
                'IBLOCK_ID' => $arInfo['IBLOCK_ID'],
                'PROPERTY_' . $arInfo['SKU_PROPERTY_ID'] => $ids,
                'ACTIVE' => 'Y'
            ),
            false,
            false,
            array(
                'ID',
                'NAME',
                'ACTIVE',
                'PREVIEW_PICTURE',
                'CATALOG_QUANTITY',
                'DETAIL_PAGE_URL',
                'PROPERTY_' . $arInfo['SKU_PROPERTY_ID']
            )
        );
        while ($arOffer = $rsOffers->GetNext()) {
            $arOffer['PRICES'] = CCatalogProduct::GetOptimalPrice($arOffer['ID'], 1);
            if (!is_array($arOffer["PREVIEW_PICTURE"])) {
                $arOffer['PREVIEW_PICTURE'] = CFile::GetPath($arOffer["PREVIEW_PICTURE"]);
            } else {
                $arOffer['PREVIEW_PICTURE'] = $arOffer["PREVIEW_PICTURE"]['SRC'];
            }

            $offers[$arOffer['PROPERTY_' . $arInfo['SKU_PROPERTY_ID'] . '_VALUE']][] = $arOffer;
        }
    }

    $arResult['CONVERTED_ITEMS'] = $arResult['ITEMS'];

    foreach ($arResult['CONVERTED_ITEMS'] as &$item) {
        if (!is_array($item["PREVIEW_PICTURE"])) {
            $item['PREVIEW_PICTURE'] = CFile::GetPath($item["PREVIEW_PICTURE"]);
        } else {
            $item['PREVIEW_PICTURE'] = $item["PREVIEW_PICTURE"]['SRC'];
        }
        if (count($offers[$item['ID']])) {
            $item['OFFERS'] = $offers[$item['ID']];
        } else {
            $item['PRICES'] = CCatalogProduct::GetOptimalPrice($item['ID'], 1);
        }
    }

    usort($arResult['CONVERTED_ITEMS'], function ($a, $b) {
        if ($a['FAVORITE_DATA']['ID'] == $b['FAVORITE_DATA']['ID']) {
            return 0;
        }
        return ($a['FAVORITE_DATA']['ID'] > $b['FAVORITE_DATA']['ID']) ? -1 : 1;
    });


    $arResult['CONVERTED_ITEMS'] = array_map(
        function ($item) {
            return \Custom\CatalogItem::convertFromFavouriteProducts($item);
        },
        $arResult['CONVERTED_ITEMS']
    );

    $obCache->EndDataCache($arResult['CONVERTED_ITEMS']);
}