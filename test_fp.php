<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
\Bitrix\Main\Loader::includeModule('saybert');

$productID = 329;

//$tmp = \Bitrix\Saybert\Entity\FavoriteProductTable::addProduct($productID);
$tmp = \Bitrix\Saybert\Entity\FavoriteProductTable::getFavoriteProducts($productID);
$tmp = 1;