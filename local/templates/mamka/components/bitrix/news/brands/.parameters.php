<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\ModuleManager;

\Bitrix\Main\Loader::includeModule('saybert');
$arTypesEx = \CIBlockParameters::GetIBlockTypes(array("-"=>" "));
$arIBlocks[] = '-';
$db_iblock = \CIBlock::GetList(array("SORT"=>"ASC"), array("SITE_ID"=>$_REQUEST["site"], "TYPE" => ($arCurrentValues["CATALOG_IBLOCK_TYPE"]!="-"?$arCurrentValues["CATALOG_IBLOCK_TYPE"]:"")));
while($arRes = $db_iblock->Fetch())
	$arIBlocks[$arRes["ID"]] = $arRes["NAME"];


$arTemplateParameters = array(
	"CATALOG_IBLOCK_TYPE" => array(
		"PARENT" => "BASE",
		"NAME" => "Тип информационного блока католога",
		"TYPE" => "LIST",
		"VALUES" => $arTypesEx,
		"REFRESH" => "Y",
	),
	"CATALOG_IBLOCK_ID" => array(
		"PARENT" => "BASE",
		"NAME" => "Информационный блок каталога",
		"TYPE" => "LIST",
		"VALUES" => $arIBlocks,
		"REFRESH" => "Y",
	),
);

