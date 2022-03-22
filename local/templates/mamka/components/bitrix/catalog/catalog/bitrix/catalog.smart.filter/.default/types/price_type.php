<?php
$precision = 2;
if (Bitrix\Main\Loader::includeModule("currency"))
{
    $res = CCurrencyLang::GetFormatDescription($arItem["VALUES"]["MIN"]["CURRENCY"]);
    $precision = $res['DECIMALS'];
}

$dataStep = "50";
$dataMin = intval($arItem['VALUES']['MIN']['VALUE']);
$dataMax = intval($arItem['VALUES']['MAX']['VALUE']);
$dataMinCurrent = intval($arItem['VALUES']['MIN']['HTML_VALUE']) ?: $dataMin;
$dataMaxCurrent = intval($arItem['VALUES']['MAX']['HTML_VALUE']) ?: $dataMax;
?>
<div class="bx_block_filter_inputs bx-filter-parameters-box my-range-block">
    <div class="clear">
        <label class="price-from">От
            <input type="text"
                   name="<?=$arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
                   id="<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
                   value="<?=$dataMinCurrent?>"
                   size="5"
                   onchange="smartFilter.change(this)"
            >
        </label>
        <label class="price-to">До
            <input type="text"
                   name="<?=$arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
                   id="<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
                   value="<?=$dataMaxCurrent?>"
                   size="5"
                   onchange="smartFilter.change(this)"
            >
        </label>
    </div>
    <span class="bx-filter-container-modef"></span>
    <div class="my-range-w">
        <div data-values="[<?=$dataMinCurrent?>, <?=$dataMaxCurrent?>]" data-step="1" data-min="<?=$dataMin?>" data-max="<?=$dataMax?>" class="my-range"></div>
        <div class="my-range-value">
            <div class="v-min"><?=$dataMin?></div>
            <div class="v-max"><?=$dataMax?></div>
        </div>
    </div>
</div>