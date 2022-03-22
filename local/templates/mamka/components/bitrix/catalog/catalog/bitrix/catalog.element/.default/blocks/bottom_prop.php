<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>

<? if ($arResult['CATALOG_TYPE'] == 3): ?>
    <div class="product-tab clear active tab-content" data-product-description>
        <div class="pordoct-description">
            <?= htmlspecialchars_decode($arResult['DETAIL_TEXT']) ?>
            <? if ($arResult['BRAND']): ?>
                <div class="product-brand">
                    <? if ($arResult['BRAND']['IBLOCK_VALUES']['PREVIEW_PICTURE']): ?>
                        <? $picture = CFile::ResizeImageGet($arResult['BRAND']['IBLOCK_VALUES']['PREVIEW_PICTURE'],
                            ['width' => 200, 'height' => 200], BX_RESIZE_IMAGE_PROPORTIONAL) ?>
                        <img src="<?= $picture['src'] ?>">
                    <? else: ?>
                        <img src="<?= SITE_TEMPLATE_PATH ?>/images/no_photo.jpg">
                    <? endif; ?>
                    <a href="<?= $arResult['BRAND']['IBLOCK_VALUES']['DETAIL_PAGE_URL'] ?>" class="to-brand-link">
                        Посмотреть все товары <?= $arResult['BRAND']['IBLOCK_VALUES']['NAME'] ?>
                    </a>
                </div>
            <? endif; ?>
        </div>
        <? foreach ($arResult['PROPERTY_SPLIT_BY_OFFER_BOTTOM'] as $arOfferItems): ?>
            <? $activeOffer = $arOfferItems['offer']['ID'] == $arResult['OFFER_SELECTED']['ID'] ?>
            <div class="bx-bottom-prop-block"
                 data-offer="<?= $arOfferItems['offer']['ID'] ?>" <? if (!$activeOffer): ?> style="display: none"<? endif; ?>>
                <table class="char-table">
                    <?
                    $arProperties = array();
                    foreach ($arOfferItems['items'] as $arItem) {
                        if ($arItem['MULTIPLE'] == 'Y' && count($arItem['DESCRIPTION']) > 0) {
                            foreach ($arItem['DISPLAY_VALUE'] as $key => $strValue) {
                                $arProperties[] = array(
                                    'NAME' => $arItem['DESCRIPTION'][$key],
                                    'VALUE' => $strValue,
                                );
                            }
                        } else {
                            if ($arItem['PROPERTY_TYPE'] == 'E') {
                                $arProperties[] = array(
                                    'NAME' => $arItem['NAME'],
                                    'VALUE' => $arItem['DISPLAY_VALUE'],
                                );
                            } elseif ($arItem['MULTIPLE'] == 'Y') {
                                foreach ($arItem['DISPLAY_VALUE'] as $strValue) {
                                    $arProperties[] = array(
                                        'NAME' => $arItem['NAME'],
                                        'VALUE' => $strValue,
                                    );
                                }
                            } else {
                                $arProperties[] = array(
                                    'NAME' => $arItem['NAME'],
                                    'VALUE' => $arItem['DISPLAY_VALUE'],
                                );
                            }
                        }
                    }

                    $countColumn = 2;
                    $countProperties = count($arProperties);
                    $isNeedColspan = $countProperties % $countColumn != 0;
                    foreach ($arProperties as $i => $arProp):
                        $isColspan = $isNeedColspan && ($countProperties - $i == 1);
                        if ($i % $countColumn == 0):?>
                            <tr>
                        <? endif; ?>
                        <td><?= $arProp['NAME'] ?></td>
                        <td><?= $arProp['VALUE'] ?></td>
                        <?
                        if ($isColspan):?>
                            <td colspan="<?= $countColumn ?>"></td>
                        <? endif ?>
                        <?
                        if ($i % $countColumn == $countColumn - 1):?>
                            </tr>
                        <?endif;
                    endforeach;
                    if ($i % $countColumn != $countColumn - 1):?>
                        </tr>
                    <? endif ?>
                </table>
            </div>
        <? endforeach; ?>
    </div>
<? elseif ($arResult['CATALOG_TYPE'] == 1): ?>
    <div class="product-tab clear active tab-content" data-product-description>
        <div class="pordoct-description">
            <?= htmlspecialchars_decode($arResult['DETAIL_TEXT']) ?>
            <? if ($arResult['BRAND']): ?>
                <div class="product-brand">
                    <? if ($arResult['BRAND']['IBLOCK_VALUES']['PREVIEW_PICTURE']): ?>
                        <? $picture = CFile::ResizeImageGet($arResult['BRAND']['IBLOCK_VALUES']['PREVIEW_PICTURE'],
                            ['width' => 200, 'height' => 200], BX_RESIZE_IMAGE_PROPORTIONAL) ?>
                        <img src="<?= $picture['src'] ?>">
                    <? else: ?>
                        <img src="<?= SITE_TEMPLATE_PATH ?>/images/no_photo.jpg">
                    <? endif; ?>
                    <a href="<?= $arResult['BRAND']['IBLOCK_VALUES']['DETAIL_PAGE_URL'] ?>" class="to-brand-link">
                        Посмотреть все товары <?= $arResult['BRAND']['IBLOCK_VALUES']['NAME'] ?>
                    </a>
                </div>
            <? endif; ?>
        </div>
        <table class="char-table">
            <?
            $arProperties = array();
            foreach ($arResult['PROPERTY_SPLIT_BY_OFFER_BOTTOM'] as $arItem) {
                if ($arItem['MULTIPLE'] == "Y" && count($arItem['DESCRIPTION']) > 0) {
                    foreach ($arItem['VALUE'] as $key => $strValue) {
                        $arProperties[] = array(
                            'NAME' => $arItem['DESCRIPTION'][$key],
                            'VALUE' => $strValue,
                        );
                    }
                } else {
                    if ($arItem['PROPERTY_TYPE'] == 'E') {
                        $arProperties[] = array(
                            'NAME' => $arItem['NAME'],
                            'VALUE' => $arItem['DISPLAY_VALUE'],
                        );
                    } elseif ($arItem['MULTIPLE'] == 'Y') {
                        foreach ($arItem['VALUE'] as $strValue) {
                            $arProperties[] = array(
                                'NAME' => $arItem['NAME'],
                                'VALUE' => $strValue,
                            );
                        }
                    } else {
                        $arProperties[] = array(
                            'NAME' => $arItem['NAME'],
                            'VALUE' => $arItem['VALUE'],
                        );
                    }
                }
            }

            $countColumn = 2;
            $countProperties = count($arProperties);
            $isNeedColspan = $countProperties % $countColumn != 0;
            foreach ($arProperties as $i => $arProp):
                $isColspan = $isNeedColspan && ($countProperties - $i == 1);
                if ($i % $countColumn == 0):?>
                    <tr>
                <? endif; ?>
                <td><?= $arProp['NAME'] ?></td>
                <td><?= $arProp['VALUE'] ?></td>
                <?
                if ($isColspan):?>
                    <td colspan="<?= $countColumn ?>"></td>
                <? endif ?>
                <?
                if ($i % $countColumn == $countColumn - 1):?>
                    </tr>
                <?endif;
            endforeach;
            if ($i % $countColumn != $countColumn - 1):?>
                </tr>
            <? endif ?>
        </table>
    </div>
<? endif; ?>
<? if (!empty($arResult['PROPERTIES']['YOUTUBE_LINK']['VALUE'])): ?>
    <? $youtube = preg_replace(
        '/(www\.youtube\.com\/watch\?v=|youtu\.be\/)/',
        'www.youtube.com/embed/',
        $arResult['PROPERTIES']['YOUTUBE_LINK']['VALUE']
    ); ?>
    <div class="product-tab clear tab-content">
        <div class="pordoct-description">
            <iframe style="width: 100%;height: 500px;"
                    src="<?= $youtube ?>"
                    frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen=""></iframe>
        </div>
    </div>
<? endif; ?>

<div class="prop_more_info" style="padding: 20px;">Технические характеристики на сайте являются справочными и могут быть указаны в неполном
    объеме. Если какая-либо характеристика Вам особенно важна, то уточняйте её у нашего менеджера.
</div>