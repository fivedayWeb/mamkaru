<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

\Bitrix\Main\Loader::includeModule('saybert');
$arTypesEx = \CIBlockParameters::GetIBlockTypes(array("-"=>" "));
$arIBlocks[] = '-';
$db_iblock = \CIBlock::GetList(array("SORT"=>"ASC"), array("SITE_ID"=>$_REQUEST["site"], "TYPE" => ($arCurrentValues["IBLOCK_TYPE"]!="-"?$arCurrentValues["IBLOCK_TYPE"]:"")));
while($arRes = $db_iblock->Fetch())
	$arIBlocks[$arRes["ID"]] = $arRes["NAME"];

$arIblockElements = \Bitrix\Saybert\Helpers\IblockElement::getListIblockElement(array(),array(
	'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'],
));
$arElements = [];
foreach ($arIblockElements as $arElement)
	$arElements[$arElement['ID']] = "{$arElement['ID']}{$arElement['NAME']}";

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"IBLOCK_TYPE" => array(
			"PARENT" => "BASE",
			"NAME" => "Тип информационного блока",
			"TYPE" => "LIST",
			"VALUES" => $arTypesEx,
			"REFRESH" => "Y",
		),
		"IBLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => "Информационный блок",
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"REFRESH" => "Y",
		),
		'ELEMENT_ID' => array(
			"PARENT" => "BASE",
			"NAME" => "Элемент",
			"TYPE" => "LIST",
			"VALUES" => $arElements,
		)
	)
);