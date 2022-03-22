<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);?>
<div class="center clear">
    <h1 class="content-header"><?= $APPLICATION->GetTitle() ? $APPLICATION->GetTitle() : 'Акции'?></h1>
    <div class="clear">
        <?foreach($arResult['ITEMS'] as $arItem):?>
            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="inside-action">
                <div class="inside-action-card-image">
                    <?if($arItem['PREVIEW_PICTURE']['SRC']):?>
                        <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>" />
                    <?else:?>
                        <img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo.jpg" alt="<?=$arItem['NAME']?>" />
                    <?endif;?>
                </div>
                <div class="inside-action-title"><?=$arItem['NAME']?></div>

                <?if($arItem['DATE_ACTIVE_FROM'] || $arItem['DATE_ACTIVE_TO']):?>
                    <time class="inside-action-time">
                        <?if($arItem['DATE_ACTIVE_FROM']):?>
                            c <?=FormatDate("d F",strtotime($arItem['DATE_ACTIVE_FROM']))?>
                        <?endif?>
                        <?if($arItem['DATE_ACTIVE_TO']):?>
                            по <?=FormatDate("d F",strtotime($arItem['DATE_ACTIVE_TO']))?>
                        <?endif;?>
                    </time>
                <?endif?>

            </a>
        <?endforeach;?>
    </div>
    <?=$arResult['NAV_STRING']?>
</div>

