<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';
CModule::IncludeModule('iblock');

$arSelect = Array("ID", "NAME", "CODE");
$arFilter = Array("IBLOCK_ID"=>2, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement())
{
    $arFields = $ob->GetFields();
    echo '.';

    $trans = CUtil::translit($arFields['NAME'],"ru",array("replace_space"=>"-","replace_other"=>"-"));


    var_dump($trans);
    var_dump($arFields['NAME']);

    die;
}