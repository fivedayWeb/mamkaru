<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>
<?$arBigIndexes = [2,3];?>
<div class="center">
    <div class="headers-blocks clear">
        <h2>Акции</h2>
    </div>
    <div class="clear">
        <?$index = 0;?>
        <?foreach($arResult['ITEMS'] as $arItem):?>
            <?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
            <?$class = "small-action-card";
            if(in_array($index,$arBigIndexes)) $class="big-action-card";?>
            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="<?=$class?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>" />
                <div class="action-title"><?=$arItem['NAME']?></div>
                <div class="action-more">Подробнее</div>
            </a>
            <?$index++;?>
        <?endforeach;?>
    </div>
</div>
<a href="/discount/" class="more-button">Просмотреть еще</a>