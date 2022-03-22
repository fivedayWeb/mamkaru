<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true)?>
<ul class="dropdown-menu">
    <?foreach($arResult as $arItem):
        $hasImage = !empty($arItem['PARAMS']['icon']);?>
        <li>
            <a href="<?=$arItem['LINK']?>" class="menu-item">
                <div class="img flex">
                    <?if ($hasImage):?>
                        <img src="<?=$arItem['PARAMS']['icon']?>" alt="<?=$arItem['TEXT']?>" />
                    <?endif;?>
                </div>
                <div class="link"><?=$arItem['TEXT']?></div>
            </a>
        </li>
    <?endforeach;?>
</ul>