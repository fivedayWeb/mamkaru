<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */
/** @global CUserTypeManager $USER_FIELD_MANAGER */
global $USER_FIELD_MANAGER;
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
Loader::includeModule('iblock');
$arIBlockType = CIBlockParameters::GetIBlockTypes();

$arProperty = array();
$arIblocksFilter  = array();
if((IntVal($arCurrentValues["CATALOG_IBLOCK_ID1"]) > 0)||(IntVal($arCurrentValues["CATALOG_IBLOCK_ID2"]) > 0)||(IntVal($arCurrentValues["CATALOG_IBLOCK_ID3"]) > 0)||(IntVal($arCurrentValues["CATALOG_IBLOCK_ID4"]) > 0))
{
	if (IntVal($arCurrentValues["CATALOG_IBLOCK_ID1"]) > 0) $arIblocksFilter[] = $arCurrentValues["CATALOG_IBLOCK_ID1"];
	if (IntVal($arCurrentValues["CATALOG_IBLOCK_ID2"]) > 0) $arIblocksFilter[] = $arCurrentValues["CATALOG_IBLOCK_ID2"];
	if (IntVal($arCurrentValues["CATALOG_IBLOCK_ID3"]) > 0) $arIblocksFilter[] = $arCurrentValues["CATALOG_IBLOCK_ID3"];
}

if(\Bitrix\Main\Loader::includeModule('aspro.mshop')){
	$arPageBlocks = CMShop::GetComponentTemplatePageBlocks(__DIR__);

	$arPageBlocksParams = CMShop::GetComponentTemplatePageBlocksParams($arPageBlocks);

	CMShop::AddComponentTemplateModulePageBlocksParams(__DIR__, $arPageBlocksParams, array('SECTION' => 'BRAND_PAGE', 'OPTION' => 'BRAND')); // add option value FROM_MODULE
}


//if ($arIblocksFilter)
//{
	$arIBlock = array();
	$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_CATALOG_TYPE"], "ACTIVE"=>"Y"));
	while($arr=$rsIBlock->Fetch())
		$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];

	foreach($arIblocksFilter as $key=>$value)
	{
		$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID"], "ACTIVE"=>"Y"));
		while ($arr=$rsProp->Fetch())
		{
			if($arr["PROPERTY_TYPE"] != "F")
				$arProperty[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
		}
	}
	$arProperty_LNS = $arProperty;
//}



$arTemplateParameters = array_merge($arPageBlocksParams, array(
	"CATALOG_FILTER_NAME" => Array(
		"NAME" => GetMessage("FILTER_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => "arrProductsFilter",
	),
	"IBLOCK_CATALOG_TYPE" => array(
		"PARENT" => "DETAIL_SETTINGS",
		"NAME" => GetMessage("IBLOCK_CATALOG_TYPE"),
		"TYPE" => "LIST",
		"ADDITIONAL_VALUES" => "Y",
		"VALUES" => $arIBlockType,
		"REFRESH" => "Y",
	),
	"CATALOG_IBLOCK_ID1" => array(
		"PARENT" => "DETAIL_SETTINGS",
		"NAME" => GetMessage("IBLOCK_IBLOCK1"),
		"TYPE" => "LIST",
		"ADDITIONAL_VALUES" => "Y",
		"VALUES" => $arIBlock,
		"REFRESH" => "Y",
	),
	"CATALOG_IBLOCK_ID2" => array(
		"PARENT" => "DETAIL_SETTINGS",
		"NAME" => GetMessage("IBLOCK_IBLOCK2"),
		"TYPE" => "LIST",
		"ADDITIONAL_VALUES" => "Y",
		"VALUES" => $arIBlock,
		"REFRESH" => "Y",
	),
	"CATALOG_IBLOCK_ID3" => array(
		"PARENT" => "DETAIL_SETTINGS",
		"NAME" => GetMessage("IBLOCK_IBLOCK3"),
		"TYPE" => "LIST",
		"VALUES" => $arIBlock,
		"ADDITIONAL_VALUES" => "Y",
		"REFRESH" => "Y",
	),
	"SHOW_BACK_LINK" => array(
		"NAME" => GetMessage("SHOW_BACK_LINK"),
		"PARENT" => "DETAIL_SETTINGS",
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	"SHOW_GALLERY" => array(
		"NAME" => GetMessage("SHOW_GALLERY"),
		"PARENT" => "DETAIL_SETTINGS",
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
));

if($arCurrentValues["SHOW_GALLERY"] !== 'N'){
	$arTemplateParameters = array_merge($arTemplateParameters, array(
		"GALLERY_PROPERTY" => array(
			"NAME" => GetMessage("GALLERY_PROPERTY"),
			"TYPE" => "LIST",
			"PARENT" => "DETAIL_SETTINGS",
			"VALUES" => $arProperty_LNS,
			"ADDITIONAL_VALUES" => "Y",
			"DEFAULT" => "MORE_PHOTO",
		),
	));
}

$arTemplateParameters = array_merge($arTemplateParameters, array(
	"SHOW_LINKED_PRODUCTS_SECTIONS" => array(
		"NAME" => GetMessage("SHOW_LINKED_PRODUCTS_SECTIONS"),
		"TYPE" => "CHECKBOX",
		"PARENT" => "DETAIL_SETTINGS",
		"DEFAULT" => "N",
		"REFRESH" => "Y",
	),
));

$arTemplateParameters = array_merge($arTemplateParameters, array(
	"SHOW_LINKED_PRODUCTS" => array(
		"NAME" => GetMessage("SHOW_LINKED_PRODUCTS"),
		"TYPE" => "CHECKBOX",
		"PARENT" => "DETAIL_SETTINGS",
		"DEFAULT" => "N",
		"REFRESH" => "Y",
	),
));

if($arCurrentValues["SHOW_LINKED_PRODUCTS"] === 'Y'){
	$arTemplateParameters = array_merge($arTemplateParameters, array(
		"VIEW_LINKED_PRODUCTS" => array(
			"PARENT" => "DETAIL_SETTINGS",
			"NAME" => GetMessage("VIEW_LINKED_PRODUCTS"),
			"TYPE" => "LIST",
			"VALUES" => array(
				"slider" => GetMessage("VIEW_LINKED_PRODUCTS_SLIDER"),
				"block" => GetMessage("VIEW_LINKED_PRODUCTS_BLOCK"),
			),
		),
		"LINKED_PRODUCTS_PROPERTY" => array(
			"NAME" => GetMessage("LINKED_PRODUCTS_PROPERTY"),
			"TYPE" => "LIST",
			"PARENT" => "DETAIL_SETTINGS",
			"VALUES" => $arProperty_LNS,
			"ADDITIONAL_VALUES" => "Y",
			"DEFAULT" => "BRAND"
		),
		'LINKED_ELEMENST_PAGE_COUNT' => array(
			'SORT' => 704,
			'NAME' => GetMessage('LINKED_ELEMENST_PAGE_COUNT'),
			'TYPE' => 'TEXT',
			"PARENT" => "DETAIL_SETTINGS",
			'DEFAULT' => '20',
		),
		'SHOW_FILTER_LEFT' => array(
			"NAME" => GetMessage("SHOW_FILTER_LEFT"),
			"TYPE" => "CHECKBOX",
			"PARENT" => "DETAIL_SETTINGS",
			"DEFAULT" => "N",
			"REFRESH" => "Y",
		),
	));
}

if($arCurrentValues["SHOW_FILTER_LEFT"] === 'Y' && $arCurrentValues["SHOW_LINKED_PRODUCTS"] === 'Y'){
	$arTemplateParameters['AJAX_FILTER_CATALOG'] = array(
		"NAME" => GetMessage("AJAX_FILTER_CATALOG"),
		"TYPE" => "CHECKBOX",
		"PARENT" => "DETAIL_SETTINGS",
		"DEFAULT" => "N",
	);
}

if($arCurrentValues["SHOW_LINKED_PRODUCTS"] === 'Y'){
	$arTemplateParameters = array_merge($arTemplateParameters, array(
		"SHOW_ITEM_SECTION_LEFT" => array(
			"NAME" => GetMessage("SHOW_ITEM_SECTION_LEFT"),
			"TYPE" => "CHECKBOX",
			"PARENT" => "DETAIL_SETTINGS",
			"DEFAULT" => "N",
			"REFRESH" => "Y",
		),
	));
}

$arTemplateParameters['DEPTH_LEVEL_BRAND'] = array(
	"NAME" => GetMessage("DEPTH_LEVEL_BRAND"),
	"TYPE" => "STRING",
	"PARENT" => "DETAIL_SETTINGS",
	"DEFAULT" => "3"
);
?>