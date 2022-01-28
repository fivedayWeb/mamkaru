<?php
namespace Bitrix\Saybert\Helpers;

class Tag{

    public static function getTags($elementID){
        $result = false;
        $cacheTime = 36000;
        $cache_id = 'getTags__' . $elementID;
        $cache = new \CPHPCache();
        if ($cache->InitCache($cacheTime, $cache_id)){
            $result = $cache->GetVars();
        }
        else{
            \Bitrix\Main\Loader::includeModule('search');
            $rsResult = \CSearchTags::GetList([],['PARAM2' => $elementID,'MODULE_ID' => "iblock"]);
            while($arResult = $rsResult->Fetch()){
                $result[] = $arResult;
            }
            $cache->StartDataCache($cache_id, $cache_id);
            $cache->EndDataCache($result);
        }
        return $result;
    }
}