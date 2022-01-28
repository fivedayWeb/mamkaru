<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
$arItem = $arResult['ELEMENT'];
$arItem['CAN_BUY'] = false;
if($arItem['CATALOG']['CATALOG']['CAN_BUY_ZERO'] == 'Y')
    $arItem['CAN_BUY'] = true;
elseif($arItem['CATALOG']['CATALOG']['QUANTITY'] > 0)
    $arItem['CAN_BUY'] = true;

?>
<div class="card clear no-float" data-offer="<?=$arItem['ID']?>">
    <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="card-image-link clear">
        <?
        $cur_picture = $arItem['PREVIEW_PICTURE'];
        if($arItem['DETAIL_PICTURE'])
            $cur_picture = $arItem['DETAIL_PICTURE'];
        if($cur_picture)
        {
            $picture = CFile::ResizeImageGet($cur_picture, array('width'=>268, 'height'=>268), BX_RESIZE_IMAGE_PROPORTIONAL );
            $picture = array_change_key_case($picture, CASE_UPPER);
        }
        else
            $picture = array(
                'SRC' => ConfigOptionsClass::$NO_IMAGE
            );
        ?>

        <img src="<?=$picture['SRC']?>" alt="<?=$arItem['NAME']?>" />
    </a>
    <div class="card-content">
        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="card-title"><?=$arItem['NAME']?></a>
        <div class="clear">
            <?if(!empty($arItem['PROPERTIES']['ARTNUMBER']['VALUE'])):?>
                <div class="card-code">Артикул <?=$arItem['PROPERTIES']['ARTNUMBER']['VALUE']?></div>
            <?endif;?>
            <?if($arItem['CATALOG']['CATALOG']['QUANTITY'] > 0):?>
                <div class="card-absent green">В наличии</div>
            <?else:?>
                <div class="card-absent" style="font-weight: 500;color:red;">Под заказ</div>
            <?endif;?>
        </div>

        <?if($arItem['CATALOG']['PRICES']['RESULT_PRICE']['DISCOUNT'] != 0):?>
            <div class="card-price">
                <div class="old-price">
                    <s>
                        <?=round($arItem['CATALOG']['PRICES']['RESULT_PRICE']['BASE_PRICE'])?>
                        <?echo ($arItem['CATALOG']['PRICES']['RESULT_PRICE']['CURRENCY'] == 'RUB') ? '₽': $arItem['CATALOG']['PRICES']['RESULT_PRICE']['CURRENCY'];?>
                    </s>
                </div>
                <div class="price">
                    <?=$arItem['CATALOG']['PRICES']['RESULT_PRICE']['DISCOUNT_PRICE']?>
                    <?echo ($arItem['CATALOG']['PRICES']['RESULT_PRICE']['CURRENCY'] == 'RUB') ? '₽': $arItem['CATALOG']['PRICES']['RESULT_PRICE']['CURRENCY'];?>
                </div>
            </div>
        <?else:?>
            <div class="card-price">
                <div class="price">
                    <?=$arItem['CATALOG']['PRICES']['RESULT_PRICE']['BASE_PRICE']?>
                    <?echo ($arItem['CATALOG']['PRICES']['RESULT_PRICE']['CURRENCY'] == 'RUB') ? '₽': $arItem['CATALOG']['PRICES']['RESULT_PRICE']['CURRENCY'];?>
                </div>
            </div>
        <?endif;?>

        <form class="card-form clear">

            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="card-add-button">Подробнее</a>
        </form>
        <?if($arItem['PROPERTIES']['NEWPRODUCT']['VALUE']):?>
            <div class="card-tag new">new</div>
        <?endif;?>
        <?if($arItem['CATALOG']['PRICES']['RESULT_PRICE']['DISCOUNT'] != 0):?>
            <div class="card-tag action">Акция</div>
            <div class="card-tag discount"><?=$arItem['CATALOG']['PRICES']['RESULT_PRICE']['PERCENT']?>% скидка</div>
        <?endif;?>
    </div>
</div>