<?php
namespace Bitrix\Saybert\Helpers;

class CatalogElement{
    CONST CACHE_TIME = 360000;

    /**
     * @return string Возвращает префикс для индетифекатора кэша
     */
    static private function _cacheIDPrefix(){ return "Bitrix_Saybert_Helpers_CatalogElement"; }

    public static function getElementByID($elementID){
        if(\Bitrix\Main\Loader::includeModule('catalog')) {

            $cacheTime = self::CACHE_TIME;
            $cache_id = self::_cacheIDPrefix().'_getElementByID_'.$elementID;
            $cache = new \CPHPCache();
            if ($cache->InitCache($cacheTime, $cache_id)){
                $result = $cache->GetVars();
            }
            else{
                global $USER;
                $result = [];
                $arBasePrice = \CPrice::GetBasePrice($elementID);

                $arElement = \Bitrix\Saybert\Helpers\IblockElement::getIblockElement($elementID);
                $arInfo = \CCatalogSKU::GetInfoByProductIBlock($arElement['IBLOCK_ID']); 
                if (is_array($arInfo)) 
                { 
                    $rsOffers = \CIBlockElement::GetList(
                        array(),
                        array(
                            'IBLOCK_ID' => $arInfo['IBLOCK_ID'], 
                            'PROPERTY_'.$arInfo['SKU_PROPERTY_ID'] => $arElement['ID'],
                        ),
                        false,
                        false,
                        []
                    );
                    $arOffers = array();
                    while ($arOffer = $rsOffers->GetNext()) 
                    {
                        $offer = array_merge($arOffer, self::getElementByID($arOffer['ID']));
                        $offer['PREVIEW_PICTURE'] = \CFile::GetPath($offer['PREVIEW_PICTURE']);
                        $arOffers[] = $offer;
                    }
                    $result['OFFERS'] = $arOffers;
                } 

                $arCatalog = \CCatalogProduct::GetByID($elementID);
                $arOptimalPrice = \CCatalogProduct::GetOptimalPrice($elementID, 1, $USER->GetUserGroupArray(), 'N');

                $result['BASE_PRICE'] = $arBasePrice;
                $result['CATALOG'] = $arCatalog;
                $result['PRICES'] = $arOptimalPrice;

                $cache->StartDataCache($cache_id, $cache_id);
                $cache->EndDataCache($result);
            }
            return $result;
        }
        return false;
    }
}