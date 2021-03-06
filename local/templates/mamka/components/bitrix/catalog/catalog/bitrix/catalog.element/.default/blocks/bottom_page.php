<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

$arRecomData = array();
$recomCacheID = array('IBLOCK_ID' => $arParams['IBLOCK_ID']);
$obCache = new CPHPCache();
$ElementID = $arResult['ID'];
if ($obCache->InitCache(36000, serialize($recomCacheID), "/catalog/recommended"))
{
    $arRecomData = $obCache->GetVars();
}
elseif ($obCache->StartDataCache())
{
    if (Loader::includeModule("catalog"))
    {
        $arSKU = CCatalogSKU::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
        $arRecomData['OFFER_IBLOCK_ID'] = (!empty($arSKU) ? $arSKU['IBLOCK_ID'] : 0);
        $arRecomData['IBLOCK_LINK'] = '';
        $arRecomData['ALL_LINK'] = '';
        $rsProps = CIBlockProperty::GetList(
            array('SORT' => 'ASC', 'ID' => 'ASC'),
            array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'PROPERTY_TYPE' => 'E', 'ACTIVE' => 'Y')
        );
        $found = false;
        while ($arProp = $rsProps->Fetch())
        {
            if ($found)
            {
                break;
            }
            if ($arProp['CODE'] == '')
            {
                $arProp['CODE'] = $arProp['ID'];
            }
            $arProp['LINK_IBLOCK_ID'] = intval($arProp['LINK_IBLOCK_ID']);
            if ($arProp['LINK_IBLOCK_ID'] != 0 && $arProp['LINK_IBLOCK_ID'] != $arParams['IBLOCK_ID'])
            {
                continue;
            }
            if ($arProp['LINK_IBLOCK_ID'] > 0)
            {
                if ($arRecomData['IBLOCK_LINK'] == '')
                {
                    $arRecomData['IBLOCK_LINK'] = $arProp['CODE'];
                    $found = true;
                }
            }
            else
            {
                if ($arRecomData['ALL_LINK'] == '')
                {
                    $arRecomData['ALL_LINK'] = $arProp['CODE'];
                }
            }
        }
        if ($found)
        {
            if(defined("BX_COMP_MANAGED_CACHE"))
            {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache("/catalog/recommended");
                $CACHE_MANAGER->RegisterTag("iblock_id_".$arParams["IBLOCK_ID"]);
                $CACHE_MANAGER->EndTagCache();
            }
        }
    }
    $obCache->EndDataCache($arRecomData);
}

?>
<div class="recomendet-block tabs clear container" style="display:none;">
    <div class="recomendet-buttons ">
        <div class="recomendet-button bx-recommended-button tab-button" style="display: none;">???????????????? ????????????</div>
        <div class="recomendet-button bx-viewed-button tab-button" style="display: none;">?????????????? ??????????????????????????</div>
    </div>
    <div class="tabs-contents">
        <div class="recomendet-tab-content tab-content bx-sale-recommended-products">
            <?$APPLICATION->IncludeComponent("bitrix:sale.recommended.products",
                "catalog.detail",
                array(
                    "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
                    "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                    "ID" => $ElementID,
                    "MIN_BUYES" => 1,
                    "HIDE_NOT_AVAILABLE" => $arParams['HIDE_NOT_AVAILABLE'],
                    "ELEMENT_COUNT" => $arParams["ALSO_BUY_ELEMENT_COUNT"],
                    "SHOW_DISCOUNT_PERCENT" => "Y",
                    "SHOW_NAME" => "Y",
                    "SHOW_IMAGE" => "Y",
                    "PAGE_ELEMENT_COUNT" => 4,
                    "DETAIL_URL" => $arParams["DETAIL_URL"],
                    "BASKET_URL" => $arParams["BASKET_URL"],
                    "PAGE_ELEMENT_COUNT" => $arParams["ALSO_BUY_ELEMENT_COUNT"],
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "PRICE_CODE" => $arParams["PRICE_CODE"],
                    "SHOW_OLD_PRICE" => "Y",
                    "SHOW_PRICE_COUNT" => "Y",
                    "PROPERTY_CODE_".$arRecomData['OFFER_IBLOCK_ID'] => array(),
                    "CART_PROPERTIES_".$arRecomData['OFFER_IBLOCK_ID'] => array("COLOR_REF", "SIZES_SHOES", "SIZES_CLOTHES", ""),
                    "ADDITIONAL_PICT_PROP_".$arParams['IBLOCK_ID'] => $arParams['ADD_PICT_PROP'],
                    "OFFER_TREE_PROPS_".$arRecomData['OFFER_IBLOCK_ID'] => $arParams["OFFER_TREE_PROPS"],
                    'SHOW_PRODUCTS_'.$arRecomData['OFFER_IBLOCK_ID'] => 'Y',
                    'SHOW_PRODUCTS_'.$arParams['IBLOCK_ID'] => 'Y',
                ),
                $component,
                array("HIDE_ICONS" => "Y")
            );
            ?>
        </div>
        <div class="recomendet-tab-content tab-content bx-catalog-viewed-products">
            <?$APPLICATION->IncludeComponent(
                "bitrix:catalog.products.viewed",
                "catalog.detail",
                array(
                    'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                    "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                    "SHOW_FROM_SECTION" => "N",
                    "HIDE_NOT_AVAILABLE" => $arParams['HIDE_NOT_AVAILABLE'],
                    "SHOW_DISCOUNT_PERCENT" => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
                    "PRODUCT_SUBSCRIPTION" => "N",
                    "CACHE_TYPE" => "Y",
                    "CACHE_TIME" => "36000000",
                    "CACHE_GROUPS" => "Y",
                    "SHOW_OLD_PRICE" => "Y",
                    "PAGE_ELEMENT_COUNT" => 20,
                    "PRICE_CODE" => array(),
                    "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
                    "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                    "CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
                    "BASKET_URL" => $arParams["BASKET_URL"],
                    "ACTION_VARIABLE" => (!empty($arParams["ACTION_VARIABLE"]) ? $arParams["ACTION_VARIABLE"] : "action")."_crp",
                    "PRODUCT_ID_VARIABLE" =>  $arParams["PRODUCT_ID_VARIABLE"],
                    "ADD_PROPERTIES_TO_BASKET" =>(isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                    "PRODUCT_PROPS_VARIABLE" =>  $arParams["PRODUCT_PROPS_VARIABLE"],
                    "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                    "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
                    "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                    "PROPERTY_CODE_".$arRecomData['OFFER_IBLOCK_ID'] => array(),
                    "ADDITIONAL_PICT_PROP_".$arParams['IBLOCK_ID'] => $arParams['ADD_PICT_PROP'],
                    "PROPERTY_CODE_".$arRecomData['OFFER_IBLOCK_ID'] => array(),
                    "CART_PROPERTIES_".$arRecomData['OFFER_IBLOCK_ID'] => array("COLOR_REF", "SIZES_SHOES", "SIZES_CLOTHES", ""),
                    "ADDITIONAL_PICT_PROP_".$arParams['IBLOCK_ID'] => $arParams['ADD_PICT_PROP'],
                    "OFFER_TREE_PROPS_".$arRecomData['OFFER_IBLOCK_ID'] => $arParams["OFFER_TREE_PROPS"],
                    'SHOW_PRODUCTS_'.$arRecomData['OFFER_IBLOCK_ID'] => 'Y',
                    'SHOW_PRODUCTS_'.$arParams['IBLOCK_ID'] => 'Y',
                    'CURRENT_ELEMENT_ID' => $ElementID
                ),
                $component
            );
            ?>
        </div>
    </div>
</div>