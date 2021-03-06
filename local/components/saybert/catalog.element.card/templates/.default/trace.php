<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
$arItem = $arResult['ITEM'];

// result_modifier.php START
$arItem['NAME'] = html_entity_decode($arItem['NAME']);
// result_modifier.php END
?>
<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="card clear no-float" data-offer="<?=$arItem['ID']?>">
    <div class="card-image-link clear">
        <?
        $pictureSrc = $arItem['PREVIEW_PICTURE']['SRC'];
        if (empty($pictureSrc)) {
            if (!empty($arItem['DETAIL_PICTURE'])) {
                $pictureSrc = CFile::ResizeImageGet(
                    $arItem['DETAIL_PICTURE'],
                    [
                        'width' => 268, 
                        'height' => 268
                    ], 
                    BX_RESIZE_IMAGE_PROPORTIONAL_ALT
                )['src'];
            } elseif (!empty($arItem['PROPERTIES']['MORE_PHOTO']['VALUE'])) {
                $pictureSrc = CFile::GetPath(reset($arItem['PROPERTIES']['MORE_PHOTO']['VALUE']));
            } else {
                $pictureSrc = ConfigOptionsClass::$NO_IMAGE;
            }
        }
        ?>
        <img src="<?=$pictureSrc?>" class="_picture" alt="<?=$arItem['NAME']?>" />
    </div>
    <div class="card-content">
        <span href="<?=$arItem['DETAIL_PAGE_URL']?>" class="card-title _name"><?=$arItem['NAME']?></span>
        <div class="clear">
            <?
            $totalQuantity = $arItem['CATALOG_QUANTITY'] ?: $arItem['CATALOG_QUANTITY_1'] ?: $arItem['CATALOG']['QUANTITY'];
            foreach ($arItem['OFFERS'] as $arOffer) {
                $arOfferQuantity = $arOffer['CATALOG_QUANTITY'] ?: $arOffer['CATALOG_QUANTITY_1'] ?: $arOffer['CATALOG']['QUANTITY'];
                if ($arOfferQuantity > 0) {
                    $totalQuantity += $arOfferQuantity;
                }
            }
            ?>
            <div class="product-in-store">
                <?if($totalQuantity > 0):?>
                    Товар на складе: <span class="green">В наличии</span>
                <?else:?>
                    Товар на складе: <span style="font-weight: 500;color:red;">Под заказ</span>
                <?endif?>
            </div>
            <?if(!empty($arItem['PROPERTIES']['ARTNUMBER']['VALUE'])):?>
                <div class="card-code">Артикул <?=$arItem['PROPERTIES']['ARTNUMBER']['VALUE']?></div>
            <?endif;?>
        </div>
        <?
//        Log::add($arItem, 'preview/' . $arItem['ID'], date('H_i_s'));
        $arPrice = array(
            'VALUE' => $arItem['MIN_PRICE']['VALUE'] ?: $arItem['CATALOG_PRICE_1'] ?: $arItem['CATALOG_PRICE_3'] ?: $arItem['PRICES']['PRICE']['PRICE'] ?: reset($arItem['ITEM_PRICES'])['PRICE'],
            'DISCOUNT' => $arItem['MIN_PRICE']['DISCOUNT_DIFF'] ?: ($arItem['PRICES']['PRICE']['PRICE'] - $arItem['PRICES']['DISCOUNT_PRICE']) ?: reset($arItem['ITEM_PRICES'])['DISCOUNT'],
            'CURRENCY' => $arItem['MIN_PRICE']['CURRENCY'] ?: $arItem['CATALOG_CURRENCY_1'] ?: $arItem['CATALOG_CURRENCY_3'] ?: $arItem['PRICES']['PRICE']['CURRENCY'] ?: 'RUB',
        );

        if (empty($arPrice['VALUE']) && $arItem['CATALOG_TYPE'] == 3 && array_key_exists('OFFERS', $arItem) && !empty($arItem['OFFERS'])) {
            if (array_key_exists('ITEM_PRICES', reset($arItem['OFFERS']))) {
                $minPrice = reset(reset($arItem['OFFERS'])['ITEM_PRICES'])['PRICE'];
                foreach ($arItem['OFFERS'] as $arOffer) {
                    $price = reset($arOffer['ITEM_PRICES'])['PRICE'];
                    if ($price < $minPrice || empty($minPrice)) {
                        $minPrice = $price;
                    }
                }
            } else {
                $minPrice = reset($arItem['OFFERS'])['MIN_PRICE']['VALUE'];
                foreach ($arItem['OFFERS'] as $arOffer) {
                    if ($arOffer['MIN_PRICE']['VALUE'] < $minPrice) {
                        $minPrice = $arOffer['MIN_PRICE']['VALUE'];
                    }
                }
            }
            $arPrice['VALUE'] = $minPrice;
        }
        
        if (empty($arPrice['DISCOUNT']) && $arItem['CATALOG_TYPE'] == 3) {
            foreach ($arItem['OFFERS'] as $arOffer) {
                if ($arOffer['MIN_PRICE']['VALUE'] == $arPrice['VALUE']) {
                    $arPrice['DISCOUNT'] = $arOffer['MIN_PRICE']['DISCOUNT_DIFF'];
                    break;
                }
            }
        }
        if (!empty($arPrice['DISCOUNT'])) {
            $arPrice['DISCOUNT_PERCENT'] = intval($arItem['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'] ?: ($arPrice['DISCOUNT'] / $arPrice['VALUE']) * 100);
        }
        ?>
        <?if ($arPrice['VALUE']):?>
            <?if (round($arPrice['DISCOUNT']) > 0):?>
                <div class="card-price">
                    <div class="old-price">
                        <s>
                            <?=Price::format(round($arPrice['VALUE']))?>
                            <?=($arPrice['CURRENCY'] == 'RUB') ? '₽': $arPrice['CURRENCY'];?>
                        </s>
                    </div>
                    <div class="price _price">
                        <?=Price::format($arPrice['VALUE'] - $arPrice['DISCOUNT'])?>
                        <?=($arPrice['CURRENCY'] == 'RUB') ? '₽': $arPrice['CURRENCY'];?>
                    </div>
                </div>
            <?else:?>
                <div class="card-price">
                    <div class="price _price"><?=Price::format($arPrice['VALUE'])?> <?=($arPrice['CURRENCY'] == 'RUB') ? '₽': $arPrice['CURRENCY'];?></div>
                </div>
            <?endif;?>
        <?endif?>

        <?if ($totalQuantity):?>
            <div class="flex buttons-block">
                <form class="card-form flexable buy-form _add_to_basket_form" action="<?=$arItem['DETAIL_PAGE_URL']?>" method="GET" data-type="json">
                    <div class="count-picker">
                        <input type="hidden" name="action" value="BUY">
                        <input type="hidden" name="ajax_basket" value="Y">
                        <?
                        $offerID = $arItem['ID'];
                        if (!empty($arItem['OFFERS'])) {
                            $arOffers = $arItem['OFFERS'];
                            $arOfferFirst = reset($arOffers);
                            if (!empty($arOfferFirst['ID'])) {
                                $offerID = $arOfferFirst['ID'];
                            } elseif (!empty($arOfferFirst['CATALOG']['ID'])) {
                                $offerID = $arOfferFirst['CATALOG']['ID'];
                            }
                        }
                        ?>
                        <input type="hidden" name="id" value="<?=$offerID?>">
                        <input type="hidden" name="quantity" value="1" data-max="<?=$totalQuantity?>">
                    </div>
                    <?/*<button type="submit" class="card-add-button add-to-basket-button">В корзину</button>*/?>
                </form>
                <div class="card-form more-form flexable">
                    <span class="card-add-button more-info">Подробнее</span>
                </div>    
            </div>
        <?else:?>
            <div class="card-form clear">
                <span class="card-add-button more-info">Подробнее</span>
            </div>
        <?endif?>
        
        <?if($arItem['PROPERTIES']['NEWPRODUCT']['VALUE']):?>
            <div class="card-tag new">new</div>
        <?endif;?>
        <?if($arPrice['DISCOUNT_PERCENT'] > 0):?>
            <div class="card-tag action">Акция</div>
            <div class="card-tag discount"><?=$arPrice['DISCOUNT_PERCENT']?>% скидка</div>
        <?endif;?>
    </div>
</a>