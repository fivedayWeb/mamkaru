<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
$index = 1;
?>
<?foreach($arResult['ITEMS'] as $arItem):?>
    <?if($arItem['ID'] == $arParams['CURRENT_ELEMENT_ID'])
        continue;
    if($index >= 5) break;?>
    <?$catalogInfo = \Bitrix\Saybert\Helpers\CatalogElement::getElementByID($arItem['ID']);?>

    <div class="card clear" data-offer="<?=$arItem['ID']?>">
        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="card-image-link clear">
            <?if($arItem['PREVIEW_PICTURE']):?>
                <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>" />
            <?else:?>
                <img src="<?= ConfigOptionsClass::$NO_IMAGE ?>" alt="<?=$arItem['NAME']?>" />
            <?endif?>
        </a>
        <div class="card-content">
            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="card-title">
                <?=$arItem['NAME']?>
            </a>
            <div class="clear">
                <div class="card-code">Артикул <?=$arItem['PROPERTIES']['ARTNUMBER']['VALUE']?></div>
                <div class="card-absent green">
                    <?
                    if($arItem['CATALOG_QUANTITY'] > 0):?>
                        В наличии
                    <?else:?>
                        <span style="color:red;">Под заказ</span>
                    <?endif;?>
                </div>
            </div>
            <?if($catalogInfo['PRICES']['RESULT_PRICE']['DISCOUNT'] != 0):?>
                <div class="card-price">
                    <div class="old-price">
                        <s>
                            <?=round($catalogInfo['PRICES']['RESULT_PRICE']['BASE_PRICE'])?>
                            <?echo ($catalogInfo['PRICES']['RESULT_PRICE']['CURRENCY'] == 'RUB') ? '₽': $catalogInfo['PRICES']['RESULT_PRICE']['CURRENCY'];?>
                        </s>
                    </div>
                    <div class="price">
                        <?=$catalogInfo['PRICES']['RESULT_PRICE']['DISCOUNT_PRICE']?>
                        <?echo ($catalogInfo['PRICES']['RESULT_PRICE']['CURRENCY'] == 'RUB') ? '₽': $catalogInfo['PRICES']['RESULT_PRICE']['CURRENCY'];?>
                    </div>
                </div>
            <?else:?>
                <div class="card-price">
                    <div class="price">
                        <?=$catalogInfo['PRICES']['RESULT_PRICE']['BASE_PRICE']?>
                        <?echo ($catalogInfo['PRICES']['RESULT_PRICE']['CURRENCY'] == 'RUB') ? '₽': $catalogInfo['PRICES']['RESULT_PRICE']['CURRENCY'];?>
                    </div>
                </div>
            <?endif;?>
            <form class="card-form clear">
                <div class="count-picker">
                    <input type="hidden" name="count" value="1">
                    <button class="count-button minus">-</button>
                    <div class="count-counter">1</div>
                    <button class="count-button plus">+</button>
                </div>
                <button class="card-add-button">В корзину</button>
            </form>
            <?if($arItem['PROPERTIES']['NEWPRODUCT']['VALUE']):?>
                <div class="card-tag new">new</div>
            <?endif;?>
            <?if($catalogInfo['PRICES']['RESULT_PRICE']['DISCOUNT'] != 0):?>
                <div class="card-tag action">Акция</div>
                <div class="card-tag discount"><?=$catalogInfo['PRICES']['RESULT_PRICE']['PERCENT']?>% скидка</div>
            <?endif;?>
        </div>
    </div>
    <?$index++;?>
<?endforeach;;?>
