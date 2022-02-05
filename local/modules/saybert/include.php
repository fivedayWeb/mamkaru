<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

\Bitrix\Main\Loader::includeModule('iblock');

spl_autoload_register(function($className){
    $className = ltrim($className,'\\');
    $arNesting = explode('\\',$className );
    if(array_shift($arNesting) != 'Saybert') return false;
    $path = "";
    foreach($arNesting as $arItem)
        $path .= "/".mb_strtolower($arItem);
    $modulePath = __DIR__;
    include($modulePath.'/vendor'.$path.".php");
});