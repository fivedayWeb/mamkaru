<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?
use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>

<?if($arParams["NO_GROUPS_STORE"] != "Y"):?>
	<?
	CModule::IncludeModule('catalog');

	$arStores=CMshopCache::CCatalogStore_GetList(array(), array("ACTIVE" => "Y"), false, false, array("ID", "SORT", "UF_STORE_GROUP"));

	$arAllSections = $arAllSectionsIds = array();
	$arAllSectionsIds = array_column($arStores, 'UF_STORE_GROUP');
	$arAllSectionsIds = array_unique($arAllSectionsIds);
	//$arAllSectionsIds = array_filter($arAllSectionsIds);

	global $USER_FIELD_MANAGER;
	$arFields = $USER_FIELD_MANAGER->GetUserFields("CAT_STORE");
	$obEnum = new CUserFieldEnum;
	$rsEnum = $obEnum->GetList(array(), array("USER_FIELD_ID" => $arFields["UF_STORE_GROUP"]["ID"], "ID" => $arAllSectionsIds));
	while($arEnum = $rsEnum->GetNext()){
	   //$arAllSections[$arEnum["ID"]] = $arEnum;
	   $arAllSections[$arEnum["ID"]]['SECTION']['NAME'] = $arEnum["VALUE"];
	   $arAllSections[$arEnum["ID"]]['SECTION']['ID'] = $arEnum["ID"];
	}
	?>
<?endif;?>

<?$bHasSections = (isset($arAllSections) && $arAllSections);?>
<?if($bHasSections):?>
	<div class="wrapper_inner">
	    <div class="region-row">
	        <div class="choise_region">
	            <select class="city">
	                <option value="0" selected><?=Loc::getMessage('CHOISE_ITEM', array('#ITEM#' => Loc::getMessage('CITY')))?></option>
	                <?foreach($arAllSections as $arSection):?>
	                    <option value="<?=$arSection['SECTION']['ID'];?>"><?=$arSection['SECTION']['NAME'];?></option>
	                <?endforeach;?>
	            </select>
	        </div>
	    </div>
	</div>
    
<?endif;?>
<?$bPostSection = (isset($_POST['ID']) && $_POST['ID']);?>
<?
$isAjax = (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y');
?>
<div class="ajax_items shops_block">
    <?if($isAjax){
        $APPLICATION->RestartBuffer();?>
    <?}?>

    <?$bPostSection = (isset($_POST['ID']) && $_POST['ID']);?>

	<?$APPLICATION->ShowViewContent('map_content');?>

	<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.store.list",
		"main",
		Array(
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"PHONE" => $arParams["PHONE"],
			"SCHEDULE" => $arParams["SCHEDULE"],
			"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
			"TITLE" => $arParams["TITLE"],
			"SET_TITLE" => "N",
			"PATH_TO_ELEMENT" => $arResult["PATH_TO_ELEMENT"],
			"PATH_TO_LISTSTORES" => $arResult["PATH_TO_LISTSTORES"],
			"MAP_TYPE" => $arParams["MAP_TYPE"],		
			"GOOGLE_API_KEY" => $arParams["GOOGLE_API_KEY"],
			"SECTION_FILTER" => ($bPostSection ? $_POST['ID'] : ''),
			"NO_GROUPS_STORE" => $arParams["NO_GROUPS_STORE"],
		),
		$component
	);?>

	<?if($isAjax){
        die();?>
    <?}?>

</div>