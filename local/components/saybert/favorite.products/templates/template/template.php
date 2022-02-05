<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true); ?>

<div class="flex">
    <?
    foreach ($arResult['CONVERTED_ITEMS'] as $arItem): ?>
        <? $APPLICATION->IncludeComponent(
            'bitrix:catalog.item',
            'catalog-item',
            array(
                'RESULT' => array(
                    'ITEM' => $arItem,
                )
            )
        );
    endforeach; ?>
</div>