<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

\Bitrix\Main\Loader::includeModule('sale');
\Bitrix\Main\Loader::includeModule('catalog');
\Bitrix\Main\Loader::includeModule('saybert');

$res = CCatalogDiscount::GetDiscountProductsList(array() , array(
    ">=DISCOUNT_ID" => 1
) , false, false, array());

/*while($arItem = $res->Fetch()){
    $tmp = 1;
}*/

\Bitrix\Saybert\Handler\OnIBlockPropertyBuildList::handle([1]);