<?php
$precision = 2;
if (Bitrix\Main\Loader::includeModule("currency"))
{
    $res = CCurrencyLang::GetFormatDescription($arItem["VALUES"]["MIN"]["CURRENCY"]);
    $precision = $res['DECIMALS'];
}
?>
<div class="bx_block_filter_inputs bx-filter-parameters-box my-range-block">
    <div class="clear">
        <label class="price-from">От
            <input type="text"
                   name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
                   id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
                   value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
                   size="5"
                   onchange="smartFilter.change(this)"
            >
        </label>
        <label class="price-to">До
            <input type="text"
                   name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
                   id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
                   value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
                   size="5"
                   onchange="smartFilter.change(this)"
            >
        </label>
    </div>
    <span class="bx-filter-container-modef"></span>
    <div class="my-range-w">
        <?
        $dataMin = $arItem['VALUES']['MIN']['VALUE'];
        $dataMax = $arItem['VALUES']['MAX']['VALUE'];
        ?>
        <div data-values="[<?=$dataMin?>, <?=($dataMax)?>]" data-step="<?=round($dataMax/$dataMin)?>" data-min="<?=$dataMin?>" data-max="<?=($dataMax)?>" class="my-range"></div>
        <div class="my-range-value">
            <div class="v-min"><?=$dataMin?></div>
            <div class="v-max"><?=$dataMax?></div>
        </div>
    </div>
</div>