<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arCur = current($arItem["VALUES"]);
switch ($arItem["DISPLAY_TYPE"]):
    case "A"://NUMBERS_WITH_SLIDER
        include('types/default.php');
        break;
    case "B"://NUMBERS
        include('types/default.php');
        break;
    case "G"://CHECKBOXES_WITH_PICTURES
        include('types/default.php');
        break;
    case "H"://CHECKBOXES_WITH_PICTURES_AND_LABELS
        include('types/default.php');
        break;
    case "P"://DROPDOWN
        include('types/default.php');
        break;
    case "R"://DROPDOWN_WITH_PICTURES_AND_LABELS
        include('types/default.php');
        break;
    case "K"://RADIO_BUTTONS
        include('types/default.php');
        break;
    case "U"://CALENDAR
        include('types/default.php');
        break;
    default://CHECKBOXES
        include('types/default.php');
endswitch;