<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="product-tab tab-content clear bx-reviews">
    <?$APPLICATION->IncludeComponent(
        "saybert:catalog.reviews",
        "",
        array(
            'PRODUCT_ID' => $arResult['ID']
        )
    );?>
</div>