<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);?>
<div class="center">
    <h1 class="content-header">Статьи</h1>
    <div id="content-text" class="left-content content-text-news">
        <?foreach($arResult['ITEMS'] as $arItem):?>
            <?\Bitrix\Saybert\Helpers\Tag::getTags($arItem['ID'])?>
            <?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
            <div class="inside-news-card" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                
                <div class="inside-news-card-info">
                    <h2><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a></h2>
                    <p>
                        <?=$arItem['PREVIEW_TEXT']?>
                    </p>
                    <div class="news-card-tags">
                        <?if($arItem['PROPERTIES']['DATE']['VALUE'] ):?>
                            <time class="news-card-time">
                                <?=FormatDate($arParams['ACTIVE_DATE_FORMAT'],strtotime($arItem['PROPERTIES']['DATE']['VALUE']))?>
                            </time>
                        <?endif;?>
                        <?$arTags = \Bitrix\Saybert\Helpers\Tag::getTags($arItem['ID']);?>
                        <?foreach($arTags as $arTag):?>
                            <a href="#" class="news-card-tag"><?=$arTag['NAME']?></a>
                        <?endforeach;?>
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="news-card-more">Далее ></a>
                    </div>
                </div>
            </div>
        <?endforeach;?>
        <?=$arResult['NAV_STRING']?>
    </div>
</div>
