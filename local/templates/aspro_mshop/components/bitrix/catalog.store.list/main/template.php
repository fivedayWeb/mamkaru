<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if(strlen($arResult["ERROR_MESSAGE"])>0) ShowError($arResult["ERROR_MESSAGE"]);?>
<?CMshop::drawShopsList($arResult["STORES"], $arParams, "N", $arResult["SECTIONS"]);?>