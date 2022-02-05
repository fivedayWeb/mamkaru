<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arResult['FILE'] = CFile::MakeFileArray($arParams['LINK_TO_PICTURE']);
$arResult['FILE']['LINK'] = CFile::GetPath($arParams['LINK_TO_PICTURE']);
if($size < 1)
    $size = round($arResult['FILE']['size']/1024,2).' КБ';
$arResult['FILE']['size']=$size;

$arResult['NAME'] = $arParams['HEADER'];
$arResult['TEXT'] = $arParams['TEXT'] ? $arParams['TEXT'] : false;
$arResult['TEXT_URL'] = $arParams['TEXT_URL'] ? $arParams['TEXT_URL'] : false;
$arResult['URL'] = $arParams['URL'] ? $arParams['URL'] : false;
$this->IncludeComponentTemplate();