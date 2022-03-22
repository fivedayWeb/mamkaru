<?php
CModule::IncludeModule('saybert');
use \Bitrix\Saybert\Helpers\IblockSection;

$arSort = [
    "sort" => "asc",
    "left_margin" => "asc",
    "id" => "asc"
];
$arSections = IblockSection::getList(
    $arSort,
    [
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'DEPTH_LEVEL' => '1',
        'ACTIVE' => 'Y',
    ],
    true
);
$arResult['SECTIONS'] = [];
$aMenuLinks = [];
foreach($arSections as $arSection) {
    if (!$arSection['ELEMENT_CNT']) continue;
    $arSubSections = IblockSection::getList(
        $arSort,
        [
            'IBLOCK_ID' => $arParams['ID'],
            'SECTION_ID' => $arSection['ID'],
            'ACTIVE' => 'Y',
        ],
        true
    );
    if (!empty($arSubSections)) {
        foreach ($arSubSections as $key2 => &$arSection2) {
            if (!$arSection2['ELEMENT_CNT']) {
                unset($arSubSections[$key2]);
                continue;
            }
            $arSections3 = IblockSection::getList(
                $arSort,
                [
                    'IBLOCK_ID' => $arParams['ID'],
                    'SECTION_ID' => $arSection2['ID'],
                    'ACTIVE' => 'Y',
                ],
                true
            );
            foreach ($arSections3 as $key3 => $arSection3) {
                if (!$arSection3['ELEMENT_CNT']) {
                    unset($arSections3[$key3]);
                    continue;
                }
            }
            $arSection2['SUB_SECTIONS'] = $arSections3;
        }
        unset($arSection2);
        $arSection['SUB_SECTIONS'] = $arSubSections;
    }
    $arResult['SECTIONS'][] = $arSection;
}

$arResult['CURRENT_SECTION_ID'] = array();
foreach ($arResult['SECTIONS'] as $arSection) {
    foreach ($arSection['SUB_SECTIONS'] as $arSubSection) {
        foreach ($arSubSection['SUB_SECTIONS'] as $arSection3) {
            if ($arSection3['CODE'] == $arParams['SECTION_CODE']) {
                $arResult['CURRENT_SECTION_ID'][] = $arSection3['ID'];
                $arResult['CURRENT_SECTION_ID'][] = $arSubSection['ID'];
                $arResult['CURRENT_SECTION_ID'][] = $arSection['ID'];
            }
        }
        if ($arSubSection['CODE'] == $arParams['SECTION_CODE']) {
            $arResult['CURRENT_SECTION_ID'][] = $arSubSection['ID'];
            $arResult['CURRENT_SECTION_ID'][] = $arSection['ID'];
        }
    }
    if ($arSection['CODE'] == $arParams['SECTION_CODE']) {
        $arResult['CURRENT_SECTION_ID'][] = $arSection['ID'];
    }
    
}