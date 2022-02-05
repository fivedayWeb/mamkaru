<?php

namespace Bitrix\Saybert\Helpers;

use Bitrix\Main\Loader;

class IBlock
{
    const CACHE_DIR = '/iblock/';

    /**
     * Получить идентификатор инфоболока по его символьному коду
     *
     * @param string $iblockCode
     * @return int|bool В случае если инфоблок не найден будет возвращен false
     */
    public static function getIblockId($iblockCode)
    {
        $result = static::getIblock($iblockCode);

        return is_array($result) ? $result['ID'] : false;
    }

    /**
     * Получить тип инфоболока по его символьному коду
     *
     * @param string $iblockCode
     * @return int|bool В случае если инфоблок не найден будет возвращен false
     */
    public static function getIblockType($iblockCode)
    {
        $result = static::getIblock($iblockCode);

        return is_array($result) ? $result['IBLOCK_TYPE_ID'] : false;
    }

    /**
     * Получить данные инфоблока по его символьному коду
     *
     * @param $iblockCode
     * @return array|bool В случае если инфоблок не найден будет возвращен false
     */
    public static function getIblock($iblockCode)
    {
        $result = false;
        $obCache = new \CPHPCache();

        $cacheId = 'iblock_' . $iblockCode;

        if ($obCache->InitCache(604800, $cacheId, static::CACHE_DIR)) {
            $vars = $obCache->GetVars();
            $result = $vars['IBLOCK'];
        } elseif ($obCache->StartDataCache()) {
            Loader::includeModule('iblock');
            /** @noinspection PhpDynamicAsStaticMethodCallInspection */
            $dbRes = \CIBlock::GetList(array('ID' => 'ASC'), array('CODE' => $iblockCode, 'CHECK_PERMISSIONS' => 'N'),
                false);
            $result = $dbRes->Fetch();

            if ($result) {
                global $CACHE_MANAGER;

                $CACHE_MANAGER->StartTagCache(static::CACHE_DIR);
                $CACHE_MANAGER->RegisterTag("iblock_id_" . $result['ID']);
                $CACHE_MANAGER->RegisterTag("iblock_code_" . $iblockCode);
                $CACHE_MANAGER->EndTagCache();

                $obCache->EndDataCache(array('IBLOCK' => $result));
            } else {
                $obCache->AbortDataCache();
            }
        }

        return is_array($result) ? $result : false;
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
     * Получить данные инфоблока по его ID
     *
     * @param $iblockId
     * @return array|bool В случае если инфоблок не найден будет возвращен false
     */
    public static function getIBlockById($iblockId)
    {
        $result = false;
        $obCache = new \CPHPCache();

        $cacheId = 'iblock_id_' . $iblockId;

        if ($obCache->InitCache(604800, $cacheId, static::CACHE_DIR)) {
            $vars = $obCache->GetVars();
            $result = $vars['IBLOCK'];
        } elseif ($obCache->StartDataCache()) {
            Loader::includeModule('iblock');
            /** @noinspection PhpDynamicAsStaticMethodCallInspection */
            $dbRes = \CIBlock::GetList(array('ID' => 'ASC'), array('ID' => $iblockId, 'CHECK_PERMISSIONS' => 'N'),
                false);
            $result = $dbRes->Fetch();

            if ($result) {
                global $CACHE_MANAGER;

                $CACHE_MANAGER->StartTagCache(static::CACHE_DIR);
                $CACHE_MANAGER->RegisterTag("iblock_id_" . $iblockId);
                $CACHE_MANAGER->RegisterTag("iblock_code_" . $result["CODE"]);
                $CACHE_MANAGER->EndTagCache();

                $obCache->EndDataCache(array('IBLOCK' => $result));
            } else {
                $obCache->AbortDataCache();
            }
        }

        return is_array($result) ? $result : false;
    }
}