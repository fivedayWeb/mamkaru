<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>
<ul>
    <?foreach ($arResult as $arItem):?>
        <li id="<?=$arItem['ADDITIONAL_LINKS'][0]?>">
            <a href="<?=$arItem['LINK']?>" <?if($arItem['PARAMS']['class']):?>class="<?=$arItem['PARAMS']['class']?>"<?endif?>>
                <?=$arItem['TEXT']?>
            </a>
        </li>
    <?endforeach;?>
</ul>

