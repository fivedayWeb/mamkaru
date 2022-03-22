<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>
<div class="news_list_main_grid center">
    <div class="clear flex">
        <?foreach($arResult['ITEMS'] as $index => $arItem):
        	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            $class = $arItem['PROPERTIES']['SIZE']['VALUE_ENUM_ID'] == '76623' ? "big" : "small"; ?>
            <a href="<?=$arItem['PROPERTIES']['URL']['VALUE']?>" class="action-card <?=$class?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>" />
                <div class="action-title"><?=$arItem['NAME']?></div>
                <div class="action-more">Подробнее</div>
            </a>
        <?endforeach;?>
    </div>
</div>