<?php

namespace Bitrix\Saybert\Helpers;

class IblockElement
{

    CONST CACHE_TIME = 360000;

    /**
     * @return string Возвращает префикс для индетифекатора кэша
     */
    static private function _cacheIDPrefix()
    {
        return "Bitrix_Saybert_Helpers_IblockElement";
    }

    /**
     * @param $elementID - Id элемента
     * @return array - Возвращает массив элемента
     */
    static function getIblockElement($elementID)
    {
        $cacheTime = self::CACHE_TIME;
        $cache_id = self::_cacheIDPrefix() . '_getIblockElement' . $elementID;
        $cache = new \CPHPCache();
        if ($cache->InitCache($cacheTime, $cache_id)) {
            $result = $cache->GetVars();
        } else {
            $rsElement = \CIBlockElement::GetByID($elementID)->GetNextElement();
            if ($rsElement) {
                $result = $rsElement->GetFields();
                $result['PROPERTIES'] = $rsElement->GetProperties();
            }
            $cache->StartDataCache($cache_id, $cache_id);
            $cache->EndDataCache($result);
        }
        return $result;
    }

    /**
     * Обертка на функцию CIBlockElement::GetList()
     */
    static function getListIblockElement(
        $arOrder = Array("SORT" => "ASC"),
        $arFilter = Array(),
        $arGroupBy = false,
        $arNavStartParams = false
    ) {
        $cacheTime = self::CACHE_TIME;
        $cache_id = self::_cacheIDPrefix() . 'getListIblockElement' . serialize(array(
                $arOrder,
                $arFilter,
                $arGroupBy,
                $arNavStartParams
            ));
        $cache = new \CPHPCache();
        if ($cache->InitCache($cacheTime, $cache_id)) {
            $result = $cache->GetVars();
        } else {
            $result = array();
            $rsElement = \CIBlockElement::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, array('*', 'CATALOG_QUANTITY'));
            if ($rsElement) {
                while ($arElement = $rsElement->GetNextElement()) {
                    $prop = $arElement->GetProperties();
                    $arElement = $arElement->GetFields();
                    $arElement['PROPERTIES'] = $prop;
                    $result[] = $arElement;
                }
            }
            $cache->StartDataCache($cache_id, $cache_id);
            $cache->EndDataCache($result);
        }


        return $result;
    }

    /**
     * @param int $iblockId - идентификетор информационго блока
     * @param $code - код элемента
     * @return array - массив описывающий элемент с кодом $code
     */
    static function getIblockElementByCode($iblockId, $code)
    {
        $result = self::getListIblockElement(array(), array(
            'IBLOCK_ID' => $iblockId,
            'CODE' => $code,
        ));
        if (is_array($result)) {
            return current($result);
        }
        return array();
    }


    /**
     * @return array|null Выводит свойство типа список, записей в ИБ
     * @var string $propertyCode - код свойства
     * @var int $iblockId ID инфоблока
     */
    public static function getListProperty($iblockId, $propertyCode)
    {
        $cacheTime = self::CACHE_TIME;
        $cache_id = 'getListProperty' . $iblockId . '_' . $propertyCode;
        $cache = new \CPHPCache();
        if ($cache->InitCache($cacheTime, $cache_id)) {
            $result = $cache->GetVars();
        } else {
            $arProperty = \CIBlock::GetProperties($iblockId, array(), array('CODE' => $propertyCode))->Fetch();
            $rsPropValue = \CIBlockProperty::GetPropertyEnum($arProperty['ID'], array(), array());
            while ($arPropValue = $rsPropValue->Fetch()) {
                $arProperty['VALUE'][$arPropValue['XML_ID']] = $arPropValue;
            }
            $result = $arProperty;
            $cache->StartDataCache($cache_id, $cache_id);
            $cache->EndDataCache($result);
        }
        return $result;
    }

    /**
     * @param $arFilter - Массив для фильтрации
     * @return int - количество элементов по фильтру $arFilter
     */
    public static function getCountElement($arFilter)
    {
        $cacheTime = self::CACHE_TIME;
        $cache_id = 'getCountElement' . serialize($arFilter);
        $cache = new \CPHPCache();
        if ($cache->InitCache($cacheTime, $cache_id)) {
            $result = $cache->GetVars();
        } else {
            $result = \CIBlockElement::GetList(array(), $arFilter, array());
            $cache->StartDataCache($cache_id, $cache_id);
            $cache->EndDataCache($result);
        }
        return $result;
    }
}