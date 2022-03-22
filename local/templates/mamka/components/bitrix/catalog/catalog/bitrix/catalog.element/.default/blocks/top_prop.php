<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="product-char-content">
    <?if($arResult['CATALOG_TYPE'] == 3):?>
        <?foreach($arResult['PROPERTY_SPLIT_BY_OFFER_TOP'] as $arOfferItems):?>
            <?$activeOffer = $arOfferItems['offer']['ID'] == $arResult['OFFER_SELECTED']['ID']?>
            <div class="bx-top-prop-block" data-offer="<?=$arOfferItems['offer']['ID']?>" <?if(!$activeOffer):?> style="display: none"<?endif;?>>
                <?foreach($arParams['TOP_BLOCK_PROPERTIES'] as $prop){?>
                    <?foreach($arOfferItems['items'] as $arItem):
                        if($prop != $arItem['CODE']) continue;?>
                        <div>
                            <?=$arItem['NAME']?>:
                            <?if($arItem['PROPERTY_TYPE'] == 'E'):?>
                                <strong><?=$arItem['DISPLAY_VALUE']?></strong>
                            <?elseif($arItem['MULTIPLE'] == 'Y'):?>
                                <strong><?=implode(', ',$arItem['VALUE']);?></strong>
                            <?else:?>
                                <strong><?=$arItem['DISPLAY_VALUE']?></strong>
                            <?endif;?>
                        </div>
                    <?endforeach;?>
                <?}?>
            </div>
            <?$first = false;?>
        <? endforeach;?>
    <?elseif($arResult['CATALOG_TYPE'] == 1):?>
        <?foreach($arResult['PROPERTY_SPLIT_BY_OFFER_TOP'] as $arProperty):?>
            <div>
                <?=$arProperty['NAME']?>:
                <?if($arProperty['PROPERTY_TYPE'] == 'E'):?>
                    <strong><?=$arProperty['DISPLAY_VALUE']?></strong>
                <?elseif($arProperty['MULTIPLE'] == 'Y'):?>
                    <strong><?=implode(', ',$arProperty['VALUE']);?></strong>
                <?else:?>
                    <strong><?=$arProperty['VALUE']?></strong>
                <?endif;?>
            </div>
        <?endforeach;?>
        <?if(!empty($arResult['CATALOG_WEIGHT'])):?>
            <div>
                Вес: <strong><?=$arResult['CATALOG_WEIGHT']?> грамм</strong>
            </div>
        <?endif;?>
        <?if(!empty($arResult['CATALOG_LENGTH'])):?>
            <div>
                Длина: <strong><?=$arResult['CATALOG_LENGTH']?> мм</strong>
            </div>
        <?endif;?>
        <?if(!empty($arResult['CATALOG_WIDTH'])):?>
            <div>
                Ширина: <strong><?=$arResult['CATALOG_WIDTH']?> мм</strong>
            </div>
        <?endif;?>
        <?if(!empty($arResult['CATALOG_HEIGHT'])):?>
            <div>
                Высота: <strong><?=$arResult['CATALOG_HEIGHT']?> мм</strong>
            </div>
        <?endif;?>
    <?endif;?>
</div>