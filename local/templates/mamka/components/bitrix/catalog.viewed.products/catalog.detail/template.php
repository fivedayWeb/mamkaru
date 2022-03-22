<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<div class="catalog_viewed_products_catalog_detail">
    <?foreach($arResult['ITEMS'] as $arItem):
        if($arItem['ID'] == $arParams['CURRENT_ELEMENT_ID']) continue;
        // $catalogInfo = \Bitrix\Saybert\Helpers\CatalogElement::getElementByID($arItem['ID']);
        ?>
        <?$APPLICATION->IncludeComponent('saybert:catalog.element.card','',array(
            'arItem' => $arItem
        ));?>
    <?endforeach;;?>
</div>