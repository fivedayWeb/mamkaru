<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogProductsViewedComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode(true);
$uid = uniqid();

$is_sku = isset($arResult['ITEM']['OFFERS']);
$JSOffers = false;

if ($is_sku) {
    $countOffers = count($arResult['ITEM']['OFFERS']);

    foreach ($arResult['ITEM']['OFFERS'] as &$offer) {
        $offer['IS_CURRENT'] = $offer['ID'] == $arResult['ITEM']['ID'];
    }
    unset($offer);
    usort($arResult['ITEM']['OFFERS'], function ($a, $b) {
        if ($a['ID'] == $b['ID']) {
            return 0;
        }
        return ($a['ID'] > $b['ID']) ? -1 : 1;
    });

    usort($arResult['ITEM']['OFFERS'], function ($a, $b) {
        if (reset($a['PRICES'])['DISCOUNT_VALUE'] == reset($b['PRICES'])['DISCOUNT_VALUE']) {
            return 0;
        }
        return (reset($a['PRICES'])['DISCOUNT_VALUE'] < reset($b['PRICES'])['DISCOUNT_VALUE']) ? -1 : 1;
    });

    usort($arResult['ITEM']['OFFERS'], function ($a, $b) {
        if ($a['CATALOG_AVAILABLE'] == $b['CATALOG_AVAILABLE']) {
            return 0;
        }
        return ($a['CATALOG_AVAILABLE'] == 'Y') ? -1 : 1;
    });
	usort($arResult['ITEM']['OFFERS'], function ($a, $b) {
        if ($a['PRODUCT']['QUANTITY'] == $b['PRODUCT']['QUANTITY']) {
            return 0;
        }
        return ($a['PRODUCT']['QUANTITY'] > 0) ? -1 : 1;
    });

    usort($arResult['ITEM']['OFFERS'], function ($a, $b) {
        if ($a['IS_CURRENT'] == $b['IS_CURRENT']) {
            return 0;
        }
        return ($a['IS_CURRENT'] == true) ? -1 : 1;
    });

    if ($countOffers) {
        foreach ($arResult['ITEM']['OFFERS'] as $offer) {
            $JSOffers[$offer['ID']] = $offer;
        }
    }
}

$arJSParams = array(
    'uid' => $uid,
    'offers' => $JSOffers,
    'url' => $arResult['ITEM']['URL'],
    'no_photo' => ConfigOptionsClass::$NO_IMAGE,
);

if (isset($arResult['ITEM'])) :
    $item = $arResult['ITEM'];
    $item['DISCOUNT_PERCENT'] = ($item['DISCOUNT'] * 100) / $item['BASE_PRICE'];

    if ($is_sku): ?>
        <div id="<?= $uid ?>-catalog-item" class="card clear no-float offers-card" data-offer="">
            <a href="<?= $item['URL'] ?>" data-item-href>
                <div class="card-image-link clear">
                    <? if (!is_null($item['PREVIEW_PICTURE']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $item['PREVIEW_PICTURE']) && !strpos($item['PREVIEW_PICTURE'],
                            'no_photo')): ?>
                        <img src="<?= $item['PREVIEW_PICTURE'] ?>"
                             class="_picture" alt="<?= $item['NAME'] ?>" data-item-image/>
                    <? elseif (!is_null($item['PREVIEW_PICTURE_ANOTHER']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $item['PREVIEW_PICTURE_ANOTHER']) && !strpos($item['PREVIEW_PICTURE_ANOTHER'],
                            'no_photo')): ?>
                        <img src="<?= $item['PREVIEW_PICTURE_ANOTHER'] ?>"
                             class="_picture" alt="<?= $item['NAME'] ?>" data-item-image/>
                    <? else: ?>
                        <img src="<?= ConfigOptionsClass::$NO_IMAGE ?>"
                             class="_picture" alt="<?= $item['NAME'] ?>" style="width: auto"/>
                    <? endif; ?>
                </div>
            </a>

			<? //echo 'ЕСТЬ ПРЕДЛОЖЕНИЯ'; print_r($arResult['ITEM']); ?>
            <div class="offers-horizontal-carousel-container">
                <div id="<?= $uid ?>-offers-horizontal-carousel" class="hidden">


					<? foreach ($arResult['ITEM']['OFFERS'] as $i => $offer){ 
																			 ?>
                        <? $res = CFile::ResizeImageGet($offer['PREVIEW_PICTURE']['ID'],
                            array('width' => 100, 'height' => 100), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                        if (!$res) {
                            $res = ['src' => ConfigOptionsClass::$NO_IMAGE];
                        }
                        ?>
					<? //echo $offer['PRODUCT']['QUANTITY']; ?>
                        <div class="offers-small-product-image <?= $offer['PRODUCT']['QUANTITY'] > 0 ? 'HAS_AVAILABLE' : 'NOT_HAS_AVAILABLE' ?> <?= $offer['IS_CURRENT'] ? 'active' : '' ?>"data-carousel-item data-carousel-offer-id="<?= $offer['ID'] ?>">
                            <img <?= $countOffers >= 4 ? 'class="owl-lazy"' : '' ?>
                                    src="<?= $countOffers < 4 ? $res['src'] : 'data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=' ?>"
                                    data-src="<?= $res['src'] ?>"
                                    data-carousel-offer-id="<?= $offer['ID'] ?>" alt="<?= $item['NAME'] ?>"/>
                        </div>
					<? }; ?>
                </div>
            </div>
            <div class="card-content">
                <a href="<?= $item['URL'] ?>" class="card-title _name" data-item-href><?= $item['NAME'] ?></a>
                <div class="clear">
                    <div class="product-in-store" data-item-is-available>
                        <? if ($item['QUANTITY'] > 0): ?>
                            Товар на складе: <span class="green">В наличии</span>
                        <? else: ?>
                            Товар на складе: <span class="red">Под заказ</span>
                        <? endif ?>
                    </div>
                    <? if (!empty($arItem['PROPERTIES']['ARTNUMBER']['VALUE'])): ?>
                        <div class="card-code">Артикул <?= $arItem['PROPERTIES']['ARTNUMBER']['VALUE'] ?></div>
                    <? endif; ?>
                </div>
				<? if ($item['BASE_PRICE'] && $item['QUANTITY'] > 0): ?>
					<? if (round($item['DISCOUNT']) > 0): ?>
					<div class="card-price" data-item-card-price>
						<div class="old-price">
							<s>
								<?= $item['BASE_PRICE_PRINT'] ?>
							</s>
						</div>
						<div class="price _price">
							<?= $item['PRICE_PRINT'] ?>
						</div>
					</div>
					<? else: ?>
					<div class="card-price" data-item-card-price>
						<div class="price _price">
							<?= $item['PRICE_PRINT'] ?>
						</div>
					</div>
					<? endif; ?>
				<? else: ?>
					<div class="card-price" data-item-card-price>
						<div class="price noprice">Цену уточняйте у менеджера</div>
					</div>
				<? endif; ?>

                <? if ($item['QUANTITY'] > 0): ?>
                    <div class="flex buttons-block">
                        <form class="card-form flexable buy-form _add_to_basket_form"
                              action="<?= $item['URL'] ?>" method="GET" data-type="json">
                            <div class="count-picker">
                                <input type="hidden" name="action" value="BUY">
                                <input type="hidden" name="ajax_basket" value="Y">
                                <input type="hidden" name="id" value="<?= $item['ID'] ?>" data-item-input-id>
                                <input type="hidden" name="quantity" value="1" data-max="<?= $item['QUANTITY'] ?>">
                            </div>
                            <button type="submit" class="card-add-button add-to-basket-button"
                                    data-item-button-basket>В корзину
                            </button>
                            <a data-modal="order-you-modal"
                               class='modal-open bx-product-add-to-order add-to-order hidden'
                               data-offer="<?= $item['ID'] ?>" data-add-to-order-button>Заказать</a>
                        </form>
                        <div class="card-form more-form flexable">
                            <a href="<?= $item['URL'] ?>" data-item-href>
                                <span class="card-add-button more-info">Подробнее</span>
                            </a>
                        </div>
                    </div>
                <? else: ?>
                    <div class="card-form clear">
                        <a data-modal="order-you-modal" class='modal-open bx-product-add-to-order add-to-order'
                           data-offer="<?= $item['ID'] ?>" data-add-to-order-button>Заказать</a>
                        <a href="<?= $item['URL'] ?>" data-item-href>
                            <span class="card-add-button more-info">Подробнее</span>
                        </a>
                    </div>
                <? endif ?>

                <? if ($item['PROPERTIES']['NEWPRODUCT']['VALUE']): ?>
                    <div class="card-tag new">new</div>
                <? endif; ?>
                <? if ($item['DISCOUNT_PERCENT'] > 0): ?>
                    <!--                <div class="card-tag action">Акция</div>-->
                    <div class="card-tag discount"><?= round($item['DISCOUNT_PERCENT']) ?>% скидка</div>
                <? endif; ?>
            </div>
        </div>
    <? else: ?>
        <div id="<?= $uid ?>-catalog-item" class="card clear no-float <?= $item['QUANTITY'] > 0 ? 'HAS_AVAILABLE' : 'NOT_HAS_AVAILABLE' ?>" >
            <a href="<?= $item['URL'] ?>">
                <div class="card-image-link clear">
                    <? if (!is_null($item['PREVIEW_PICTURE']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $item['PREVIEW_PICTURE']) && !strpos($item['PREVIEW_PICTURE'],
                            'no_photo')): ?>
                        <img src="<?= $item['PREVIEW_PICTURE'] ?>"
                             class="_picture" alt="<?= $item['NAME'] ?>"/>
                    <? elseif (!is_null($item['PREVIEW_PICTURE_ANOTHER']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $item['PREVIEW_PICTURE_ANOTHER']) && !strpos($item['PREVIEW_PICTURE_ANOTHER'],
                            'no_photo')): ?>
                        <img src="<?= $item['PREVIEW_PICTURE_ANOTHER'] ?>"
                             class="_picture" alt="<?= $item['NAME'] ?>"/>
                    <? else: ?>
                        <img src="<?= ConfigOptionsClass::$NO_IMAGE ?>"
                             class="_picture" alt="<?= $item['NAME'] ?>" style="width: auto"/>
                    <? endif; ?>
                </div>
            </a>
			<? //echo 'НЕТ ПРЕДЛОЖЕНИЙ'; print_r($arResult['ITEM']); ?>
            <!--<div class="offers-horizontal-carousel-container">
                <div id="<?= $uid ?>-offers-horizontal-carousel" class="hidden">
                </div>
            </div>-->
            <div class="card-content">
                <a href="<?= $item['URL'] ?>" class="card-title _name"><?= $item['NAME'] ?></a>
                <div class="clear">
                    <div class="product-in-store">
                        <? if ($item['QUANTITY'] > 0): ?>
                            Товар на складе: <span class="green">В наличии</span>
                        <? else: ?>
                            Товар на складе: <span style="font-weight: 500;color:red;">Под заказ</span>
                        <? endif ?>
                    </div>
                    <? if (!empty($arItem['PROPERTIES']['ARTNUMBER']['VALUE'])): ?>
                        <div class="card-code">Артикул <?= $arItem['PROPERTIES']['ARTNUMBER']['VALUE'] ?></div>
                    <? endif; ?>
                </div>
                <? if ($item['BASE_PRICE'] && $item['QUANTITY'] > 0): ?>
                    <? if (round($item['DISCOUNT']) > 0): ?>
                        <div class="card-price">
                            <div class="old-price">
                                <s>
                                    <?= $item['BASE_PRICE_PRINT'] ?>
                                </s>
                            </div>
                            <div class="price _price">
                                <?= $item['PRICE_PRINT'] ?>
                            </div>
                        </div>
                    <? else: ?>
                        <div class="card-price">
                            <div class="price _price"><?= $item['PRICE_PRINT'] ?></div>
                        </div>
                    <? endif; ?>
				<? else: ?>
					<div class="card-price" data-item-card-price>
						<div class="price noprice">Цену уточняйте у менеджера</div>
					</div>
				<? endif; ?>

                <? if ($item['QUANTITY'] > 0): ?>
                    <div class="flex buttons-block">
                        <form class="card-form flexable buy-form _add_to_basket_form"
                              action="<?= $item['URL'] ?>" method="GET" data-type="json">
                            <div class="count-picker">
                                <input type="hidden" name="action" value="BUY">
                                <input type="hidden" name="ajax_basket" value="Y">
                                <input type="hidden" name="id" value="<?= $item['ID'] ?>">
                                <input type="hidden" name="quantity" value="1" data-max="<?= $item['QUANTITY'] ?>">
                            </div>
                            <button type="submit" class="card-add-button add-to-basket-button">В корзину</button>
                        </form>
                        <div class="card-form more-form flexable">
                            <a href="<?= $item['URL'] ?>">
                                <span class="card-add-button more-info">Подробнее</span>
                            </a>
                        </div>
                    </div>
                <? else: ?>
                    <div class="card-form clear">
                        <button data-modal="order-you-modal" class='modal-open bx-product-add-to-order add-to-order'
                                data-offer="<?= $item['ID'] ?>" data-add-to-order-button>Заказать
                        </button>
                        <a href="<?= $item['URL'] ?>">
                            <span class="card-add-button more-info">Подробнее</span>
                        </a>
                    </div>
                <? endif ?>

                <? if ($item['PROPERTIES']['NEWPRODUCT']['VALUE']): ?>
                    <div class="card-tag new">new</div>
                <? endif; ?>
                <? if ($item['DISCOUNT_PERCENT'] > 0): ?>
                    <!--                <div class="card-tag action">Акция</div>-->
                    <div class="card-tag discount"><?= round($item['DISCOUNT_PERCENT']) ?>% скидка</div>
                <? endif; ?>
            </div>
        </div>
    <? endif; ?>
<? endif; ?>
<script>
    $(function () {
        window.CatalogItem<?=$uid?> = new JSCatalogItem(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
    });
</script>