<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<section id="content">
    <? $APPLICATION->IncludeComponent('bitrix:breadcrumb', 'mobile', array()) ?>
    <div class="center clear">
        <div id="content-text" class="right-content clear">
            <?
            $requestBefore = $_REQUEST['q'];
            if (!empty($_REQUEST['q'])) {
                if (!preg_match('/[A-Za-z]/', $_REQUEST['q'])) {
                    $_REQUEST['q'] .= '|' . CUtil::translit($_REQUEST['q'], 'ru');
                }
            }

            $arElements = $APPLICATION->IncludeComponent(
                "bitrix:search.page",
                "catalog.search",
                Array(
                    "RESTART" => $arParams["RESTART"],
                    "NO_WORD_LOGIC" => $arParams["NO_WORD_LOGIC"],
                    "USE_LANGUAGE_GUESS" => $arParams["USE_LANGUAGE_GUESS"],
                    "CHECK_DATES" => $arParams["CHECK_DATES"],
                    "arrFILTER" => array("iblock_" . $arParams["IBLOCK_TYPE"]),
                    "arrFILTER_iblock_" . $arParams["IBLOCK_TYPE"] => array($arParams["IBLOCK_ID"]),
                    "USE_TITLE_RANK" => "N",
                    "DEFAULT_SORT" => "rank",
                    "FILTER_NAME" => "",
                    "SHOW_WHERE" => "N",
                    "arrWHERE" => array(),
                    "SHOW_WHEN" => "N",
                    "PAGE_RESULT_COUNT" => 1000,
                    "DISPLAY_TOP_PAGER" => "N",
                    "DISPLAY_BOTTOM_PAGER" => "N",
                    "PAGER_TITLE" => "",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_TEMPLATE" => "N",
                ),
                $component,
                array('HIDE_ICONS' => 'Y')
            );

            $_REQUEST['q'] = $requestBefore;
            ?>
            <?
            global $searchFilter;
            if(!count($arElements)){
                $searchFilter = array(
                    "=ID" => 0,
                    "SECTION_GLOBAL_ACTIVE" => "Y",
                );
            } else {
                $searchFilter = array(
                    "=ID" => $arElements,
                    "SECTION_GLOBAL_ACTIVE" => "Y",
                );
            }


            $arSectionIds = [];

            if(!count($arElements)) {
                $res = CIBlockElement::GetElementGroups(0, true);
            } else {
                $res = CIBlockElement::GetElementGroups($arElements, true);
            }

            while ($ar_group = $res->Fetch()) {
                if($ar_group['ACTIVE'] == 'Y') {
                    $arSectionIds[] = $ar_group["ID"];
                }
            }

            $res = CIBlockSection::GetList(
                [],
                [
                    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                    'ID' => $arSectionIds,
                    '!UF_NOT_AVAILABLE' => false,
                ]
            );
            $arSectionIds = [];
            while ($arItem = $res->Fetch()) {
                $arSectionIds[] = $arItem['ID'];
            }

            // товар в наличии или в разделе, у которого отмечено "Показывать товары которых нет на складе"
            $searchFilter[] = [
                'LOGIC' => 'OR',
                'CATALOG_AVAILABLE' => 'Y',
                'IBLOCK_SECTION_ID' => $arSectionIds
            ];


            $searchCount = \Bitrix\Saybert\Helpers\IblockElement::getCountElement($searchFilter);
            $APPLICATION->IncludeComponent(
                "bitrix:catalog.section",
                "search.page",
                array(
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
                    "ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
                    "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
                    "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
                    "PAGE_ELEMENT_COUNT" => 12,//$arParams["PAGE_ELEMENT_COUNT"],
                    "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
                    "PROPERTY_CODE" => $arParams["PROPERTY_CODE"],
                    "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                    "OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
                    "OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
                    "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
                    "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
                    "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
                    "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
                    "OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
                    "SECTION_URL" => $arParams["SECTION_URL"],
                    "DETAIL_URL" => $arParams["DETAIL_URL"],
                    "BASKET_URL" => $arParams["BASKET_URL"],
                    "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                    "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                    "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                    "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                    "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
                    "PRICE_CODE" => $arParams["PRICE_CODE"],
                    "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                    "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
                    "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                    "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
                    "USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
                    "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                    "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                    "CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
                    "CURRENCY_ID" => $arParams["CURRENCY_ID"],
                    "HIDE_NOT_AVAILABLE" => 'L',//$arParams["HIDE_NOT_AVAILABLE"],
                    "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
                    "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
                    "PAGER_TITLE" => $arParams["PAGER_TITLE"],
                    "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
                    "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
                    "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
                    "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
                    "FILTER_NAME" => "searchFilter",
                    "SECTION_ID" => "",
                    "SECTION_CODE" => "",
                    "SECTION_USER_FIELDS" => array(),
                    "INCLUDE_SUBSECTIONS" => "Y",
                    "SHOW_ALL_WO_SECTION" => "Y",
                    "META_KEYWORDS" => "",
                    "META_DESCRIPTION" => "",
                    "BROWSER_TITLE" => "",
                    "ADD_SECTIONS_CHAIN" => "N",
                    "SET_TITLE" => "N",
                    "SET_STATUS_404" => "N",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "N",

                    'LABEL_PROP' => $arParams['LABEL_PROP'],
                    'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
                    'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],

                    'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
                    'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
                    'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
                    'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
                    'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
                    'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
                    'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
                    'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
                    'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
                    'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],

                    'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
                    'ADD_TO_BASKET_ACTION' => (isset($arParams['ADD_TO_BASKET_ACTION']) ? $arParams['ADD_TO_BASKET_ACTION'] : ''),
                    'SHOW_CLOSE_POPUP' => (isset($arParams['SHOW_CLOSE_POPUP']) ? $arParams['SHOW_CLOSE_POPUP'] : ''),
                    'COMPARE_PATH' => $arParams['COMPARE_PATH'],

                    'SEARCH_COUNT' => $searchCount
                ),
                $arResult["THEME_COMPONENT"],
                array('HIDE_ICONS' => 'Y')
            );
            ?>
        </div>
    </div>
</section>