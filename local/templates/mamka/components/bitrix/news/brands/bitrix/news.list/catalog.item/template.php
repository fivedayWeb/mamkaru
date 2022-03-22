<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true); ?>

<?
$class = '';
foreach ($arResult['ITEMS'] as $arItem):
    if (count($arItem['OFFERS']) && $arItem['IS_SKU']) {
        $class = 'with-sku';
    }
endforeach; 
?>

<div class="clear test2">
    <div class="flex <?= $class ?>">
		<?php //CONVERTED_ITEMS
		foreach ($arResult['CONVERTED_ITEMS'] as $arItem): ?>
		<? echo "news/brands/bitrix/news.list/catalog.item/template.php"; ?>
            <?=$APPLICATION->IncludeComponent(
	"bitrix:catalog.item", 
	"catalog-item", 
	array(
		"RESULT" => array(
			"ITEM" => $arItem
		)
	),
	false
);
        endforeach; ?>
    </div>
</div>

<? echo $arResult["NAV_STRING"]; ?>