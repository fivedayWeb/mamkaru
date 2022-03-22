<?php
\Bitrix\Main\Loader::includeModule('saybert');
$arSections = \Bitrix\Saybert\Helpers\IblockSection::getList(
    [
        "left_margin"=>"asc",
        "sort"=>"asc",
        "id"=>"asc"
    ],
    [
        'IBLOCK_ID' => $arParams['CATALOG_IBLOCK_ID'],
        'DEPTH_LEVEL' => '1',
        'ACTIVE' => 'Y',
    ],
    true
);
$arResult['SECTIONS'] = [];
$aMenuLinks = [];
foreach($arSections as $arSection){
    if (!$arSection['ELEMENT_CNT']) continue;
    $arSubSections = \Bitrix\Saybert\Helpers\IblockSection::getList(
        [],
        [
            'CATALOG_IBLOCK_ID' => $arParams['ID'],
            'SECTION_ID' => $arSection['ID'],
            'ACTIVE' => 'Y',
        ],
        true
    );
    if(!empty($arSubSections))
        $arSection['SUB_SECTIONS'] = $arSubSections;
    $arResult['SECTIONS'][] = $arSection;
}?>

<aside id="sidebar" class="left-sidebar">
    <div id="sidebar-categories">
        <ul>
            <?foreach($arResult['SECTIONS'] as $arSection):?>
                <li>
                    <a <?if(empty($arSection['SUB_SECTIONS'])):?>href="<?=$arSection['SECTION_URL']?>"<?endif;?>
                       <?if(!empty($arSection['SUB_SECTIONS'])):?>class="with-subcategory"<?endif;?>
                    >
                        <?=$arSection['NAME']?>
                    </a>
                    <?if(!empty($arSection['SUB_SECTIONS'])):?>
                        <ul>
                            <?foreach($arSection['SUB_SECTIONS'] as $arSubSection):?>
                                <?if (!$arSubSection['ELEMENT_CNT']) continue;?>
                                <li><a href="<?=$arSubSection['SECTION_URL']?>"><?=$arSubSection['NAME']?></a></li>
                            <?endforeach;?>
                        </ul>
                    <?endif;?>
                </li>
            <?endforeach;?>
        </ul>
    </div>
</aside>
