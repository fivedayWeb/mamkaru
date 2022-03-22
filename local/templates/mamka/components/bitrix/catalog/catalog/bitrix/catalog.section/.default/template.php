<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true); 

usort($arResult['CONVERTED_ITEMS'], function ($a, $b) {
	if ($a['CATALOG_AVAILABLE'] == $b['CATALOG_AVAILABLE']) {
		return 0;
	}
	return ($a['CATALOG_AVAILABLE'] == 'Y') ? -1 : 1;
});
/*
usort($arResult['CONVERTED_ITEMS'],function($a,$b){

})*/?>

<? $strHeader = ($arResult['IPROPERTY_VALUES']['SECTION_META_TITLE']) ? $arResult['IPROPERTY_VALUES']['SECTION_META_TITLE'] : $arResult['NAME'] ?>
<h1><?= $strHeader ?></h1>

<?
	$arSort = [
    // [
    // 	'CODE' => '',
    // 	'NAME' => 'не выбрано',
    // ],
    [
        'CODE' => 'price_asc',
        'NAME' => 'сначала дешевле',
    ],
    [
        'CODE' => 'price_desc',
        'NAME' => 'сначала дороже',
    ],
    [
        'CODE' => 'popular',
        'NAME' => 'по популярности',
    ],
];
foreach ($arSort as &$item) {
    $item['ACTIVE'] = $item['CODE'] == $_REQUEST['sort'];
}
unset($item);

$uid = uniqid('select_');
?>
<div id="<?= $uid ?>" class="catalog_sort <?= !empty($_REQUEST['sort']) ? 'selected' : '' ?>">
    <div class="select">
        <div class="title">Сортировать</div>
        <div class="options">
<? foreach ($arSort as $item):
                $arClass = ['option'];
                if ($item['ACTIVE']) {
                    $arClass[] = 'selected';
                }
                $addParams = '';
                if (!empty($item['CODE'])) {
                    $addParams = 'sort=' . $item['CODE'];
                }
                ?>
                <div class="<?= implode(' ', $arClass) ?>">
                    <? if ($item['ACTIVE']): ?>
                        <span class="link"><?= $item['NAME'] ?></span>
                    <? else: ?>
                        <a href="<?= $APPLICATION->GetCurPageParam($addParams, ['sort']) ?>"
                           class="link"><?= $item['NAME'] ?></a>
                    <? endif; ?>
                </div>
<? endforeach; ?>
        </div>
    </div>
</div>
<script>
    new JSSelect(<?=json_encode(['id' => $uid])?>);
</script>
<?
$class = '';
foreach ($arResult['ITEMS'] as $arItem):
    if (count($arItem['OFFERS']) && $arItem['IS_SKU']) {
        $class = 'with-sku';
    }
endforeach; ?>
<div class="clear">
    <div class="flex <?= $class ?>">
        <?
	//print_r($arResult['CONVERTED_ITEMS']);
        foreach ($arResult['CONVERTED_ITEMS'] as $arItem): ?>
		<h2><? if($arItem['CATALOG_AVAILABLE'] == 'Y') { echo 'Y'; } else { echo ''; } ?></h2>
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
</div>

<? echo $arResult["NAV_STRING"]; ?>
