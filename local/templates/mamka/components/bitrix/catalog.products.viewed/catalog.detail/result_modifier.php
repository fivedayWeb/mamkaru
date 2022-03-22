<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}


foreach ($arResult['ITEMS'] as &$converted_item) {
    if(count($converted_item['OFFERS'])) {
        foreach ($converted_item['OFFERS'] as &$offer) {
            $arPrice = CCatalogProduct::GetOptimalPrice($offer['ID'], 1);
            $offer['PRICES']['DISCOUNT_PRICE'] = $arPrice['DISCOUNT_PRICE'];
            $offer['PRICES']['PRICE'] = $arPrice['DISCOUNT_PRICE'];
            $offer['PRICES']['PRICE_PRINT'] = Price::format($arPrice['DISCOUNT_PRICE']) . ' ₽';
            $offer['PRICES']['BASE_PRICE'] = $arPrice['RESULT_PRICE']['BASE_PRICE'];
            $offer['PRICES']['BASE_PRICE_PRINT'] = Price::format($arPrice['RESULT_PRICE']['BASE_PRICE']) . ' ₽';
            $offer['PRICES']['DISCOUNT'] = $arPrice['RESULT_PRICE']['DISCOUNT'];
            $offer['PRICES']['DISCOUNT_PRINT'] = Price::format($arPrice['RESULT_PRICE']['DISCOUNT']) . ' ₽';
        }
    } else {
        $arPrice = CCatalogProduct::GetOptimalPrice($converted_item['ID'], 1);
        $converted_item['PRICES']['DISCOUNT_PRICE'] = $arPrice['DISCOUNT_PRICE'];
        $converted_item['PRICES']['PRICE'] = $arPrice['DISCOUNT_PRICE'];
        $converted_item['PRICES']['PRICE_PRINT'] = Price::format($arPrice['DISCOUNT_PRICE']) . ' ₽';
        $converted_item['PRICES']['BASE_PRICE'] = $arPrice['RESULT_PRICE']['BASE_PRICE'];
        $converted_item['PRICES']['BASE_PRICE_PRINT'] = Price::format($arPrice['RESULT_PRICE']['BASE_PRICE']) . ' ₽';
        $converted_item['PRICES']['DISCOUNT'] = $arPrice['RESULT_PRICE']['DISCOUNT'];
        $converted_item['PRICES']['DISCOUNT_PRINT'] = Price::format($arPrice['RESULT_PRICE']['DISCOUNT']) . ' ₽';
    }
}
$arResult['CONVERTED_ITEMS'] = array_map(
    function ($item) {
        return \Custom\CatalogItem::convertFromCatalogProductsViewed($item);
    },
    $arResult['ITEMS']
);