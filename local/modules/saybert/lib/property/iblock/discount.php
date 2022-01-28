<?php
namespace Bitrix\Saybert\Property\Iblock;

class Discount{
    protected static $pathToModule;

    public static function GetPropertyFieldHtml($arProperty,$value){
        static::init();
        return '<input type="text">';
    }
    public static function GetAdminListViewHTML(){
        return '';
    }

    public static function init(){
        static::$pathToModule = dirname(dirname(dirname(__DIR__)));
    }

    public function includeTemplate(){
        $pathToTemplate = static::$pathToModule.'templates/property/iblock/discount';
        $tmp =  include($pathToTemplate);
        $tmp =  1;
    }
}