<?php

namespace Bitrix\Saybert\Helpers;

use Bitrix\Main\Loader;

class IblockSection
{
    const CACHE_DIR = '/section/';
    CONST CACHE_TIME = 360000;

    public static function get($id)
    {
        $result = false;
        $obCache = new \CPHPCache();

        $cacheId = 'section_' . $id;

        if ($obCache->InitCache(604800, $cacheId, static::CACHE_DIR)) {
            $result = $obCache->GetVars();
        } elseif ($obCache->StartDataCache()) {
            Loader::includeModule('iblock');
            /** @noinspection PhpDynamicAsStaticMethodCallInspection */
            $result = \CIBlockSection::GetByID($id)->Fetch();

            if ($result) {
                global $CACHE_MANAGER;

                $CACHE_MANAGER->StartTagCache(static::CACHE_DIR);
                $CACHE_MANAGER->RegisterTag("iblock_id_" . $result['IBLOCK_ID']);
                $CACHE_MANAGER->EndTagCache();

                $obCache->EndDataCache($result);
            } else {
                $obCache->AbortDataCache();
            }
        }

        return is_array($result) ? $result : false;
    }

    public static function getName($id)
    {
        $section = self::get($id);
        if (!empty($section)) {
            return $section['NAME'];
        }
        return $section;
    }

    /**
     * @param $code
     * @return array|bool
     */
    public static function getByCode($code)
    {
        $result = false;
        $obCache = new \CPHPCache();

        $cacheId = 'section_' . $code;

        if ($obCache->InitCache(604800, $cacheId, static::CACHE_DIR)) {
            $result = $obCache->GetVars();
        } elseif ($obCache->StartDataCache()) {
            Loader::includeModule('iblock');
            /** @noinspection PhpDynamicAsStaticMethodCallInspection */
            $result = \CIBlockSection::GetList([], ['CODE' => $code], false, ['*', 'UF_*'])->Fetch();

            if ($result) {
                global $CACHE_MANAGER;

                $CACHE_MANAGER->StartTagCache(static::CACHE_DIR);
                $CACHE_MANAGER->RegisterTag("iblock_id_" . $result['IBLOCK_ID']);
                $CACHE_MANAGER->EndTagCache();

                $obCache->EndDataCache($result);
            } else {
                $obCache->AbortDataCache();
            }
        }

        return is_array($result) ? $result : false;
    }

    public static function getIdByCode($code)
    {
        $section = self::getByCode($code);
        if (!empty($section)) {
            return $section['ID'];
        }
        return false;
    }

    public static function getList(
            $arOrder =["SORT"=>"ASC"],
            $arFilter= [],
            $bIncCnt = false,
            $arSelect = [],
            $arNavStartParams=false
    ){
        $cacheTime = self::CACHE_TIME;
        $cache_id = 'Bitrix\Saybert\Helpers\IblockSection'.'_getListSectionElement';
        $cache_id .= serialize([$arOrder,$arFilter,$bIncCnt,$arSelect,$arNavStartParams]);
        $cache = new \CPHPCache();
        if ($cache->InitCache($cacheTime, $cache_id)){
            $result = $cache->GetVars();
        }
        else{
            $result = array();
            $rsSections = \CIBlockSection::GetList($arOrder, $arFilter, $bIncCnt, $arSelect, $arNavStartParams);
            if($rsSections instanceof \CDBResult) {
                while ($arSection = $rsSections->Fetch()) {
                    $templateUrl = $arSection['SECTION_PAGE_URL'];
                    $arSection['SECTION_URL'] = str_replace(
                        ['/','#SECTION_CODE#','#SECTION_ID#'],
                        ["",$arSection['CODE'],$arSection['ID']],
                        $templateUrl
                    );
                    $result[] = $arSection;
                }

            }
            $cache->StartDataCache($cache_id, $cache_id);
            $cache->EndDataCache($result);
        }
        return $result;
    }

    public static function getSectionByFilter($arFilter){
        $arSections = static::getList([],$arFilter, false, ['*', 'UF_*']);
        return $arSections[0];
    }
}