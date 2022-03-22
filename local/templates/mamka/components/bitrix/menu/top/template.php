<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>
<div id="bottom-header">
    <div class="center clear">
        <?foreach($arResult as $arItem):?>
            <a href="<?=$arItem['LINK']?>" >
                <div class="header-icon">
                    <div style="background-image: url('<?=$arItem['PARAMS']['MENU_ICON']?>');" class="header-icon-blue"></div>
                    <div style="background-image: url('<?=$arItem['PARAMS']['MENU_ICON_ACTIVE']?>');" class="header-icon-pink"></div>
                </div>
                <span><?=$arItem['TEXT']?></span>
            </a>
        <?endforeach;?>
    </div>
</div>