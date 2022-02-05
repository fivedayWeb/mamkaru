<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?
use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>

<?

$arItemFilter = CMshop::GetIBlockAllElementsFilter($arParams);
$arItemSelect = array('ID', 'NAME', 'IBLOCK_ID', 'DETAIL_PAGE_URL', 'IBLOCK_SECTION_ID', 'PROPERTY_MAP', 'PROPERTY_PHONE', 'PROPERTY_SCHEDULE', 'PROPERTY_METRO', 'PROPERTY_EMAIL', 'PROPERTY_ADDRESS');
$arItems = CMshopCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CMshopCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), $arItemFilter, false, false, $arItemSelect);

$arAllSections = array();
if($arItems && $arParams["NO_GROUPS_STORE"] != "Y")
    $arAllSections = CMshop::GetSections($arItems, $arParams);

if (!$arParams['MAP_TYPE']) {
	$arParams['MAP_TYPE'] = 0;
}
?>

<?$bHasSections = (isset($arAllSections['ALL_SECTIONS']) && $arAllSections['ALL_SECTIONS']);?>
<?$bHasChildSections = (isset($arAllSections['CHILD_SECTIONS']) && $arAllSections['CHILD_SECTIONS']);?>
<?if($bHasSections):?>
	<div class="wrapper_inner">
	    <div class="region-row">
	        <div class="choise_region">
	            <select class="<?=($bHasChildSections ? 'region' : 'city');?>">
	                <option value="0" selected><?=Loc::getMessage('CHOISE_ITEM', array('#ITEM#' => ($bHasChildSections ? Loc::getMessage('REGION') : Loc::getMessage('CITY'))))?></option>
	                <?foreach($arAllSections['ALL_SECTIONS'] as $arSection):?>
	                    <option value="<?=$arSection['SECTION']['ID'];?>"><?=$arSection['SECTION']['NAME'];?></option>
	                <?endforeach;?>
	            </select>
	        </div>
	        <?if($bHasChildSections):?>
	            <div class="choise_city">
	                <select class="city">
	                    <option value="0" selected><?=Loc::getMessage('CHOISE_ITEM', array('#ITEM#' => Loc::getMessage('CITY')))?></option>
	                    <?foreach($arAllSections['CHILD_SECTIONS'] as $arSection):?>
	                        <option style="display:none;" disabled="disabled" value="<?=$arSection['ID'];?>" data-parent_section="<?=$arSection['IBLOCK_SECTION_ID'];?>"><?=$arSection['NAME'];?></option>
	                    <?endforeach;?>
	                </select>
	            </div>
	        <?endif;?>
	    </div>
	</div>
    
<?endif;?>

<?
$isAjax = (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y');
?>
<div class="ajax_items shops_block">
    <?if($isAjax){
        $APPLICATION->RestartBuffer();?>
    <?}?>

    <?
    // $dbRes = CIBlock::GetProperties($arParams['IBLOCK_ID']);
    // while($arRes = $dbRes->Fetch()){
    //     $arProperties[$arRes['CODE']] = $arRes;
    // }
    ?>

    <?$bPostSection = (isset($_POST['ID']) && $_POST['ID']);?>
    <?
    //$bUseMap = CMshop::GetFrontParametrValue('CONTACTS_USE_MAP', SITE_ID) != 'N';
    $mapLAT = $mapLON = $iCountShops =0;
    $arPlacemarks = array();
    if($bPostSection)
    {
        //$arItems = CMshopCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CMshopCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array_merge($arItemFilter, array('SECTION_ID' => $_POST['ID'])), false, false, $arItemSelect);
        if(!strlen($arParams['FILTER_NAME'])){
        	$arParams['FILTER_NAME'] = "arShopFilter";
        }
        $GLOBALS[$arParams['FILTER_NAME']]['SECTION_ID'] = $_POST['ID'];
    }

    ?>


	<?$APPLICATION->ShowViewContent('map_content');?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:news.list",
		"shops",
		Array(
			"IBLOCK_TYPE"	=>	$arParams["IBLOCK_TYPE"],
			"IBLOCK_ID"	=>	$arParams["IBLOCK_ID"],
			"NEWS_COUNT"	=>	$arParams["NEWS_COUNT"],
			"SORT_BY1"	=>	$arParams["SORT_BY1"],
			"SORT_ORDER1"	=>	$arParams["SORT_ORDER1"],
			"SORT_BY2"	=>	$arParams["SORT_BY2"],
			"SORT_ORDER2"	=>	$arParams["SORT_ORDER2"],
			"FIELD_CODE"	=>	$arParams["LIST_FIELD_CODE"],
			"PROPERTY_CODE"	=>	$arParams["LIST_PROPERTY_CODE"],
			"DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
			"SECTION_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
			"IBLOCK_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
			"DISPLAY_PANEL"	=>	$arParams["DISPLAY_PANEL"],
			"SET_TITLE"	=>	"N",
			"SET_STATUS_404" => $arParams["SET_STATUS_404"],
			"INCLUDE_IBLOCK_INTO_CHAIN"	=>	$arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
			"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
			"ADD_ELEMENT_CHAIN" => $arParams["ADD_ELEMENT_CHAIN"],
			"CACHE_TYPE"	=>	'A', // for map!
			"CACHE_TIME"	=>	$arParams["CACHE_TIME"],
			"CACHE_FILTER"	=>	$arParams["CACHE_FILTER"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"DISPLAY_TOP_PAGER"	=>	$arParams["DISPLAY_TOP_PAGER"],
			"DISPLAY_BOTTOM_PAGER"	=>	$arParams["DISPLAY_BOTTOM_PAGER"],
			"PAGER_TITLE"	=>	$arParams["PAGER_TITLE"],
			"PAGER_TEMPLATE"	=>	$arParams["PAGER_TEMPLATE"],
			"PAGER_SHOW_ALWAYS"	=>	$arParams["PAGER_SHOW_ALWAYS"],
			"PAGER_DESC_NUMBERING"	=>	$arParams["PAGER_DESC_NUMBERING"],
			"PAGER_DESC_NUMBERING_CACHE_TIME"	=>	$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
			"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
			"DISPLAY_DATE"	=>	$arParams["DISPLAY_DATE"],
			"DISPLAY_NAME"	=>	"Y",
			"DISPLAY_PICTURE"	=>	$arParams["DISPLAY_PICTURE"],
			"DISPLAY_PREVIEW_TEXT"	=>	$arParams["DISPLAY_PREVIEW_TEXT"],
			"PREVIEW_TRUNCATE_LEN"	=>	$arParams["PREVIEW_TRUNCATE_LEN"],
			"ACTIVE_DATE_FORMAT"	=>	$arParams["LIST_ACTIVE_DATE_FORMAT"],
			"USE_PERMISSIONS"	=>	$arParams["USE_PERMISSIONS"],
			"GROUP_PERMISSIONS"	=>	$arParams["GROUP_PERMISSIONS"],
			"FILTER_NAME"	=>	$arParams["FILTER_NAME"],
			"HIDE_LINK_WHEN_NO_DETAIL"	=>	$arParams["HIDE_LINK_WHEN_NO_DETAIL"],
			"CHECK_DATES"	=>	$arParams["CHECK_DATES"],
			"GOOGLE_API_KEY" => $arParams["GOOGLE_API_KEY"],
			"MAP_TYPE" => $arParams["MAP_TYPE"],
			"NO_GROUPS_STORE" => $arParams["NO_GROUPS_STORE"],
		),
		$component
	);?>

	<?if($isAjax){
        die();?>
    <?}?>

</div>