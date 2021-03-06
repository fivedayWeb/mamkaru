<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div id="product-gallery">
    <?if (!empty($arResult['PHOTOS'])):
        if($arResult['CURRENT_PHOTO']) {
            $firstPhoto = $arResult['CURRENT_PHOTO'];
        } else {
            $firstPhoto = current($arResult['PHOTOS']);
        }
        ?>
        <div id="big-product-image">
            <a class="bx-active-photo" href="<?=$firstPhoto['detail']?>" data-fancybox="images1">
                <img class="bx-active-photo lazyload"
                    src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs="
                    data-src="<?=$firstPhoto['preview']?>"
                    data-detail="<?=$firstPhoto['detail']?>"
                    alt="<?=$arResult['NAME']?>"
                    />
			</a>
            <? foreach ($arResult['OFFERS'] as $arOffer): ?>
                <? $is_sel = ($arOffer['ID'] == $arResult['OFFER_SELECTED']['ID'])?>
                <?if($is_sel && $arOffer['PRICES']['BASE2']['DISCOUNT_DIFF_PERCENT'] > 0):?>
                    <div class="card-detail-tag discount"><?=$arOffer['PRICES']['BASE2']['DISCOUNT_DIFF_PERCENT']?>% скидка</div>
                <?endif;?>
            <?endforeach;?>
            <button id="like-button" class="bx-like-button" data-product="<?=$arResult['ID']?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 300"><defs><style>.cls-1{stroke-linecap:round;stroke-linejoin:round;stroke-width:6px;}</style></defs><title>Добавить в избранное</title><path class="cls-1" d="M234.5 127.7c0-28-20.4-50.7-45.5-50.7-23.2 0-42.3 11.7-45 36.7-3-25-22-36.7-45.2-36.7-25 0-45.4 22.7-45.4 50.7C56 175.4 140 209 144 210.2c4-1.2 88-34.8 90.5-82.5z"/></svg>
                <span>в избранное</span>
            </button>
        </div>
    <?endif;?>
</div>

<div id="photos-vertical">
    <?foreach($arResult['PHOTOS'] as $i => $arPhoto):?>
        <div class="small-product-image"
            <?if ($arPhoto['type'] != 'product'):?>
                style="display: none;"
            <?endif;?>
            >
            <img
            src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs="
            class="lazyload"
            data-src="<?=$arPhoto['small']?>"
            <?if($arPhoto['type'] == 'offer'):?>
                data-offer="<?=$arPhoto['offerId']?>"
            <?endif;?>
            data-type="<?=$arPhoto['type']?>"
            alt="<?=$arResult['NAME']?>"
            data-preview="<?=$arPhoto['preview']?>"
            data-detail="<?=$arPhoto['detail']?>"
            data-index="<?=$i?>"
            />
        </div>
    <?endforeach;?>
</div>