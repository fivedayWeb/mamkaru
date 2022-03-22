<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true);
?>
<h1><?= $arResult['IPROPERTY_VALUES']['SECTION_META_TITLE'] ?: $arResult['NAME'] ?></h1>
<form class="content-search" action="/catalog/">
    <input type="text" name="q" value="<?= htmlspecialchars($_GET['q']) ?>"><i class="fa fa-custom-search" onclick="$(this).closest('form').submit();"></i>
    <? if ($arParams['SEARCH_COUNT'] > 0): ?>
        <div class="count-search">
            Найдено <?= $arParams['SEARCH_COUNT'] ?> <? echo \Bitrix\Saybert\Tools\Text::plural($arParams['SEARCH_COUNT'],
                'товар', 'товара', 'товаров') ?></div>
    <? else: ?>
        <div class="count-search">
            Сожалеем, но ничего не найдено.
        </div>
    <? endif; ?>
</form>

<div class="clear flex">
    <?
    foreach ($arResult['CONVERTED_ITEMS'] as $arItem): ?>
        <?
        $APPLICATION->IncludeComponent(
            'bitrix:catalog.item',
            'catalog-item',
            array(
                'RESULT' => array(
                    'ITEM' => $arItem,
                )
            )
        );
    endforeach;
    ?>
</div>

<?= $arResult["NAV_STRING"]; ?>
