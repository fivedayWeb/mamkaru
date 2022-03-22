<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

//delayed function must return a string
if(empty($arResult))
	return "";

$itemSize = count($arResult);

$index = $itemSize - 2;
$title = $arResult[$index]['TITLE'];
$href = $arResult[$index]['LINK'];
$strReturn = "<a id=\"mobile-crumb\" href=\"{$href}\">{$title}</a>";

return $strReturn;

