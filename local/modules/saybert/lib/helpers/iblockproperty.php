<?php

namespace Bitrix\Saybert\Helpers;

use Bitrix\Main\Loader;

class IBlockProperty
{
    const CACHE_DIR = '/iblock_property/';

    /**
     * Получить идентификатор свойства инфоболока по его символьному коду
     *
     * @param string $iblockCode Код инфоблока
     * @param string $propertyCode Код свойства
     * @return int|bool В случае если инфоблок не найден будет возвращен false
     */
    public static function getPropertyId($iblockCode, $propertyCode)
    {
        $result = static::getProperty($iblockCode, $propertyCode);

        return is_array($result) ? $result['ID'] : false;
    }

    /**
     * Получить данные по свойству инфоблока
     * @param string $iblockCode Код инфоблока
     * @param string $propertyCode Код свойства
     * @return array|bool
     */
    public static function getProperty($iblockCode, $propertyCode)
    {
        $result = false;
        $obCache = new \CPHPCache();

        $cacheId = 'iblock_' . $iblockCode . '_property_' . $propertyCode;

        if ($obCache->InitCache(604800, $cacheId, static::CACHE_DIR)) {
            $vars = $obCache->GetVars();
            $result = $vars['PROPERTY'];
        } elseif ($obCache->StartDataCache()) {
            Loader::includeModule('iblock');

            $dbRes = \CIBlockProperty::GetByID($propertyCode, false, $iblockCode);
            $result = $dbRes->Fetch();

            if ($result) {
                global $CACHE_MANAGER;

                $CACHE_MANAGER->StartTagCache(static::CACHE_DIR);
                $CACHE_MANAGER->RegisterTag("iblock_id_" . $result['IBLOCK_ID']);
                $CACHE_MANAGER->RegisterTag("iblock_code_" . $iblockCode);
                $CACHE_MANAGER->EndTagCache();

                $obCache->EndDataCache(array('PROPERTY' => $result));
            } else {
                $obCache->AbortDataCache();
            }
        }

        return is_array($result) ? $result : false;
    }

    /**
     * Получить варианты значений по свойству инфоблока
     * @param string $iblockCode Код инфоблока
     * @param string $propertyCode Код свойства
     * @param string|null $xmlId Символьный код значения (Y, yes...)
     * @return array|bool В случае если поле не найдено будет возвращен false.
     * Если не указан символьный код значения, будет возвращен полный массив вариантов списка.
     * Если указан символьный код значение и значение найдено, будет возвращен массив описывающий один элемент списка.
     * Если указан символьный код значение и значение НЕ найдено, будет возвращен false
     */
    public static function getPropertyEnum($iblockCode, $propertyCode, $xmlId = null)
    {
        $result = array();
        $obCache = new \CPHPCache();

        $cacheId = 'iblock_' . $iblockCode . '_property_' . $propertyCode . '_enum';

        if ($obCache->InitCache(604800, $cacheId, static::CACHE_DIR)) {
            $vars = $obCache->GetVars();
            $result = $vars['ITEMS'];
        } elseif ($obCache->StartDataCache()) {
            Loader::includeModule('iblock');
            $iblockId = IBlock::getIblockId($iblockCode);

            if ($iblockId) {
                $dbRes = \CIBlockProperty::GetPropertyEnum($propertyCode, ['sort' => 'ASC', 'id' => 'DESC'],
                    array('IBLOCK_ID' => $iblockId));

                while ($item = $dbRes->Fetch()) {
                    $result[$item['EXTERNAL_ID']] = $item;
                }

                if ($result) {
                    global $CACHE_MANAGER;

                    $CACHE_MANAGER->StartTagCache(static::CACHE_DIR);
                    $CACHE_MANAGER->RegisterTag("iblock_id_" . $iblockId);
                    $CACHE_MANAGER->RegisterTag("iblock_code_" . $iblockCode);
                    $CACHE_MANAGER->EndTagCache();

                    $obCache->EndDataCache(array('ITEMS' => $result));
                } else {
                    $obCache->AbortDataCache();
                }
            } else {
                $obCache->AbortDataCache();
            }
        }

        if ($xmlId !== null) {
            return is_array($result) && isset($result[$xmlId]) ? $result[$xmlId] : false;
        }

        return is_array($result) ? $result : false;
    }

    /**
     * Получить идентификатор элемента списка
     *
     * @param string $iblockCode Код инфоблока
     * @param string $propertyCode Код свойства
     * @param string $xmlId Символьный код значения
     * @return bool|int Если свойство или значение в списке не найдены, то будет возвращен false
     *
     */
    public static function getPropertyEnumId($iblockCode, $propertyCode, $xmlId)
    {
        if (empty($xmlId)) {
            return false;
        }

        $result = static::getPropertyEnum($iblockCode, $propertyCode, $xmlId);

        return is_array($result) ? $result['ID'] : false;
    }

    /**
     * Получить символьный код значения списка по его идентификатору
     *
     * @param string $iblockCode Код инфоблока
     * @param string $propertyCode Код свойства
     * @param int $id Идентификтор значения
     * @return bool|string Если свойство или значение в списке не найдены, то будет возвращен false
     */
    public static function getPropertyEnumXmlIdById($iblockCode, $propertyCode, $id)
    {
        $variants = static::getPropertyEnum($iblockCode, $propertyCode);

        if ($variants) {
            $variantCodes = array();

            foreach ($variants as $item) {
                $variantCodes[$item['ID']] = $item['EXTERNAL_ID'];
            }

            return isset($variantCodes[$id]) ? $variantCodes[$id] : false;
        }

        return false;
    }

    /**
     * Получить элемент списочного свойства по id [элемента]
     *
     * @param $iblock_code
     * @param $property_code
     * @param $id
     * @return bool
     */
    public static function getPropertyEnumById($iblock_code, $property_code, $id)
    {
        $enums = static::getPropertyEnum($iblock_code, $property_code);

        if ($enums) {
            $variants = [];

            foreach ($enums as $enum) {
                $variants[$enum['ID']] = $enum;
            }

            return !empty($variants[$id]) ? $variants[$id] : null;
        }

        return null;
    }

    /**
     * Очищает кеш
     */
    public static function clearCache()
    {
        $obCache = new \CPHPCache();
        $obCache->CleanDir(static::CACHE_DIR);
    }

    /**
     * Получить все свойства инфоблока
     * @param $iblock_id int
     * @return array
     */
    public static function getProperties($iblock_id)
    {
        $db_properties = \CIBlockProperty::GetList([], ["IBLOCK_ID" => $iblock_id]);
        $properties = [];
        while ($prop_fields = $db_properties->Fetch())
        {
            $properties[] = $prop_fields;
        }

        return $properties;
    }
}