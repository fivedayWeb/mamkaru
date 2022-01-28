<?php
namespace Bitrix\Saybert\Handler;

class OnIBlockPropertyBuildList{

    public static function handle(){
        return array(
            "PROPERTY_TYPE" => "int",
            "USER_TYPE" => "discount",
            "DESCRIPTION" => "Скидка",
            'GetPropertyFieldHtml' => array('\Bitrix\Saybert\Property\Iblock\Discount', 'GetPropertyFieldHtml'),
            'GetAdminListViewHTML' => array('\Bitrix\Saybert\Property\Iblock\Discount', 'GetAdminListViewHTML'),
        );
    }
}