<?php
namespace Bitrix\Saybert\Helpers;

class User
{
    const CACHE_DIR = '/user/';

    public static function getList($filter,$params = false)
    {
        $result = false;
        $obCache = new \CPHPCache();

        $cacheId = 'user' . serialize($filter) .serialize($params);

        if ($obCache->InitCache(604800, $cacheId, static::CACHE_DIR)) {
            $result = $obCache->GetVars();
        } elseif ($obCache->StartDataCache()) {
            $result = false;
            $rsUser = \CUser::GetlIst(($by="personal_country"), ($order="desc"), $filter,$params);
            while($arUser = $rsUser->Fetch())
                $result[] = $arUser;

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


    public static function getUserByFilter($filter){
        $arUser = static::getList($filter,['SELECT' => ['UF_*']]);
        if($arUser)
            return $arUser[0];
        return false;
    }

    public static function isAdmin() {
        global $USER;
        return $USER->IsAdmin();
    }

}