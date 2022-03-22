<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
if (isset($arResult['ITEM'])) :
    if(!($arResult['ITEM']['PRICE_PRINT'])) {
        $arResult['ITEM']['PRICE_PRINT'] = Price::format($arResult['ITEM']['PRICE']) . ' ₽';
    }
    if(!($arResult['ITEM']['BASE_PRICE_PRINT'])) {
        $arResult['ITEM']['BASE_PRICE_PRINT'] = Price::format($arResult['ITEM']['BASE_PRICE']) . ' ₽';
    }
    if(!($arResult['ITEM']['PRICE_PRINT'])) {
        $arResult['ITEM']['DISCOUNT_PRINT'] = Price::format($arResult['ITEM']['DISCOUNT']) . ' ₽';
    }
endif;