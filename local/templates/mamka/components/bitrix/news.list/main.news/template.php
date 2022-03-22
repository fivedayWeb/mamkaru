<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>
<div class="center">
    <div class="headers-blocks clear">
        <h2>Новости</h2>
    </div>
    <div class="clear">
        <?foreach($arResult['ITEMS'] as $arItem):?>
            <?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
            <div class="home-news-card clear" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="news-card-image">
                    <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>" />
                </a>
                <div class="news-card-content">
                    <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="news-card-title"><?=$arItem['NAME']?></a>
                    <?if($arItem['PROPERTIES']['DATE']['VALUE'] ):?>
                        <time class="news-card-time">
                            <?=FormatDate($arParams['ACTIVE_DATE_FORMAT'],strtotime($arItem['PROPERTIES']['DATE']['VALUE']))?>
                        </time>
                    <?endif;?>
                    <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="news-card-more-link">Подробнее &#62;</a>
                </div>
            </div>
        <?endforeach;?>
    </div>
</div>