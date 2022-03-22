<?php
\Bitrix\Main\Loader::includeModule('saybert');

$arResult['DISCOUNTS'] = \Bitrix\Saybert\Helpers\IblockElement::getListIblockElement(
    [
        'SORT' => "ASC",
        'NAME' => "ASC"
    ],
    [
        'IBLOCK_ID' => $arParams['DIS_IBLOCK_ID'],
        "ACTIVE" => 'Y'
    ],
    false,
    [
        'nTopCount' => 4
    ]
);
