<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

CModule::IncludeModule("iblock");
global $TEMPLATE_OPTIONS, $MShopSectionID;
$arParams["ADD_ELEMENT_CHAIN"] = (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : "N");
$bFastViewMode = (isset($_REQUEST['FAST_VIEW']) && $_REQUEST['FAST_VIEW'] == 'Y');
$arParams["DISPLAY_WISH_BUTTONS"] = $TEMPLATE_OPTIONS['SHOW_DELAY_BUTTON']['CURRENT_VALUE'];
// get current section ID

if($arResult["VARIABLES"]["SECTION_ID"] > 0){
	$arSections = CMshopCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"Y", "TAG" => CMshopCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arResult["VARIABLES"]["SECTION_ID"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "UF_TIZERS", "SECTION_PAGE_URL", "NAME", "IBLOCK_SECTION_ID", "DEPTH_LEVEL", "LEFT_MARGIN", "RIGHT_MARGIN", "UF_OFFERS_TYPE", "UF_ELEMENT_DETAIL"));
}
elseif(strlen(trim($arResult["VARIABLES"]["SECTION_CODE"])) > 0){
	$arSections = CMshopCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"Y", "TAG" => CMshopCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "=CODE" => $arResult["VARIABLES"]["SECTION_CODE"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "UF_TIZERS", "SECTION_PAGE_URL", "NAME", "IBLOCK_SECTION_ID", "DEPTH_LEVEL", "LEFT_MARGIN", "RIGHT_MARGIN", "UF_OFFERS_TYPE", "UF_ELEMENT_DETAIL"));
}

if(count($arSections) > 1)
{
	foreach($arSections as $key => $arTmpSection)
	{
		if(str_replace($arParams['SEF_FOLDER'], '', $arTmpSection['SECTION_PAGE_URL']) == $arResult['VARIABLES']['SECTION_CODE_PATH'].'/')
		{
			$section = $arTmpSection;
		}
		
	}
}
else
{
	$section = current($arSections);
}

if($arResult["VARIABLES"]["ELEMENT_ID"] > 0){
	$arElement = CMshopCache::CIBLockElement_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMshopCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE"=>"Y", "ID" => $arResult["VARIABLES"]["ELEMENT_ID"]), false, false, array("ID", "IBLOCK_SECTION_ID", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_PICTURE"));
}
elseif(strlen(trim($arResult["VARIABLES"]["ELEMENT_CODE"])) > 0){
	$arElement = CMshopCache::CIBLockElement_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMshopCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE"=>"Y", "=CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"]), false, false, array("ID", "IBLOCK_SECTION_ID", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_PICTURE"));
}
if(!$section["ID"]){
	if($arElement["IBLOCK_SECTION_ID"] && !$section){
		$section=CMshopCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMshopCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arElement["IBLOCK_SECTION_ID"]), false, array("ID"));
	}
}
$MShopSectionID = $section["ID"];

global $TEMPLATE_OPTIONS;

$typeSKU = '';
//set offer view type
$typeTmpSKU = 0;
if($section['UF_OFFERS_TYPE'])
	$typeTmpSKU = $section['UF_OFFERS_TYPE'];
else
{
	if($section["DEPTH_LEVEL"] > 2)
	{
		$arSectionParent = CMshopCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMshopCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "ID" => $section["IBLOCK_SECTION_ID"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "NAME", "UF_OFFERS_TYPE"));
		if($arSectionParent['UF_OFFERS_TYPE'] && !$typeTmpSKU)
			$typeTmpSKU = $arSectionParent['UF_OFFERS_TYPE'];

		if(!$typeTmpSKU)
		{
			$arSectionRoot = CMshopCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMshopCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "<=LEFT_BORDER" => $section["LEFT_MARGIN"], ">=RIGHT_BORDER" => $section["RIGHT_MARGIN"], "DEPTH_LEVEL" => 1, "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "NAME", "UF_OFFERS_TYPE"));
			if($arSectionRoot['UF_OFFERS_TYPE'] && !$typeTmpSKU)
				$typeTmpSKU = $arSectionRoot['UF_OFFERS_TYPE'];
		}
	}
	else
	{
		$arSectionRoot = CMshopCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMshopCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "<=LEFT_BORDER" => $section["LEFT_MARGIN"], ">=RIGHT_BORDER" => $section["RIGHT_MARGIN"], "DEPTH_LEVEL" => 1, "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "NAME", "UF_OFFERS_TYPE"));
		if($arSectionRoot['UF_OFFERS_TYPE'] && !$typeTmpSKU)
			$typeTmpSKU = $arSectionRoot['UF_OFFERS_TYPE'];
	}
}
if($typeTmpSKU)
{
	$rsTypes = CUserFieldEnum::GetList(array(), array("ID" => $typeTmpSKU));
	if($arType = $rsTypes->GetNext())
	{
		$typeSKU = $arType['XML_ID'];
		$TEMPLATE_OPTIONS["TYPE_SKU"]["CURRENT_VALUE"] = $typeSKU;
	}
}
?>
<?CMShop::AddMeta(
	array(
		'og:description' => $arElement['PREVIEW_TEXT'],
		'og:image' => (($arElement['PREVIEW_PICTURE'] || $arElement['DETAIL_PICTURE']) ? CFile::GetPath(($arElement['PREVIEW_PICTURE'] ? $arElement['PREVIEW_PICTURE'] : $arElement['DETAIL_PICTURE'])) : false),
	)
);?>

<?
$arParams["GRUPPER_PROPS"] = COption::GetOptionString('aspro.mshop', "GRUPPER_PROPS", 'NOT', SITE_ID); 

if($arParams["GRUPPER_PROPS"] != "NOT") 
{ 
    $arParams["PROPERTIES_DISPLAY_TYPE"] = "TABLE"; 

    if($arParams["GRUPPER_PROPS"] == "GRUPPER" && !\Bitrix\Main\Loader::includeModule("redsign.grupper")) 
        $arParams["GRUPPER_PROPS"] = "NOT"; 
    if($arParams["GRUPPER_PROPS"] == "WEBDEBUG" && !\Bitrix\Main\Loader::includeModule("webdebug.utilities")) 
        $arParams["GRUPPER_PROPS"] = "NOT"; 
    if($arParams["GRUPPER_PROPS"] == "YENISITE_GRUPPER" && !\Bitrix\Main\Loader::includeModule("yenisite.infoblockpropsplus")) 
        $arParams["GRUPPER_PROPS"] = "NOT"; 
}
?>

<?if($bFastViewMode):?>
	<?include_once('element_fast_view.php');?>
<?else:?>
	<?include_once('element_normal.php');?>
<?endif;?>