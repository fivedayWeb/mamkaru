<?php

$arResult['JS']['ENGLISH_ALPHABET'] = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P",
    "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", );
$arResult['JS']['RUSSIAN_ALPHABET'] = array("А", "Б", "В", "Г", "Д", "Е", "Ё", "Ж", "З", "И", "Й", "К", "Л", "М", "Н", "О",
    "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Э", "Ю", "Я");
$arResult['JS']['OTHER'] = array();

$arResult['ISSET_RUSSION_LETTER'] = false;
$maybeLeter = [];
$arItemByFirstSymbol =[];
$arResult['COMPONENT_ITEMS'] = $arResult['ITEMS'];

foreach($arResult['ITEMS'] as $arItem)
{
    $firstLetter = substr($arItem['NAME'], 0, 1);
    if (!in_array($firstLetter, $maybeLeter))
    {
        $maybeLeter[] = $firstLetter;
    }
    if (
        in_array($firstLetter, $arResult['JS']['ENGLISH_ALPHABET']) 
        || 
        in_array($firstLetter, $arResult['JS']['RUSSIAN_ALPHABET']))
    {
        $arItemByFirstSymbol[$firstLetter][] = $arItem;
    }
    else
    {
        $arItemByFirstSymbol['0-9'][] = $arItem;
    }
    if (in_array($firstLetter,$arResult['JS']['RUSSIAN_ALPHABET']))
    {
        $arResult['ISSET_RUSSION_LETTER'] = true;
    }
}
if (isset($arItemByFirstSymbol['0-9']))
{
    $tmp = $arItemByFirstSymbol['0-9'];
    unset($arItemByFirstSymbol['0-9']);
    $arItemByFirstSymbol['0-9'] = $tmp;
}

$arResult['ITEMS'] = $arItemByFirstSymbol;
foreach ($arResult['ITEMS'] as $letter => $arItems)
{
    foreach ($arItems as $arItem)
    {
        $arResult['JS']['ITEMS'][$letter][] = array(
            'NAME' => $arItem['NAME'],
            'DETAIL_PAGE_URL' => $arItem['DETAIL_PAGE_URL']
        );
    }
}

$arResult['JS']['MAY_ITEMS'] = $maybeLeter;
