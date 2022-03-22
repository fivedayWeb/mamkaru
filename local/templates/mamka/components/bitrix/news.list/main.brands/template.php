<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>
<div class="center">
    <div class="headers-blocks clear">
        <h2>Брэнды</h2>
    </div>
    <div class="clear home-brands-cards">
        <?foreach($arResult['ITEMS'] as $arItem):?>
            <?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
			<div  class="home-brand-card" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                <img src="<?=$arItem['PREVIEW_PICTURE']["SRC"]?>" alt="<?=$arItem['NAME']?>" />
            </div>
		<?endforeach; //href="$arItem['DETAIL_PAGE_URL']"?>
    </div>
    <a href="/brands/" class="more-button">Просмотреть еще</a>
</div>