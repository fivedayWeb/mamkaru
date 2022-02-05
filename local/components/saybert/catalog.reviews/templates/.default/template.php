<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Saybert\Helpers\User;
$this->setFrameMode(true);
?>
<div data-count-reviews="<?=count($arResult['ITEMS'])?>">
    <?foreach($arResult['ITEMS'] as $arItem):?>
        <div class="review-item">
            <div class="review-autor">
                <?if($arItem['PROPERTIES']['USER_ID']['VALUE']):?>
                    <?$arUser = User::getUserByFilter(['ID' => $arItem['PROPERTIES']['USER_ID']['VALUE']]);?>
                    <?=$arUser['NAME']?>
                <?else:?>
                    Анонимный пользователь
                <?endif;?>
            </div>
            <time>
                <?=$arItem['PROPERTIES']['DATE']['VALUE']?>
            </time>
            <blockquote>
                <?=$arItem['DETAIL_TEXT']?>
            </blockquote>
        </div>
    <?endforeach;?>
</div>
<div id="add_review">
    <form id="add-review">
        <textarea name="review" placeholder="Нам будет очень приятно, если вы оставите свой отзыв на сайте"></textarea>
        <input type="hidden" name="element" value="<?=$arParams['PRODUCT_ID']?>">
        <input type="hidden" name="action" value="addReview">
        <input type="submit" value="Отправить отзыв">
    </form>
</div>