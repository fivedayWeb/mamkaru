<?php

$arSortFields = [
    'NAME' ,
    'EMAIL',
    'LOGIN',
    'PASSWORD',
    'CONFIRM_PASSWORD',
];
foreach($arResult["SHOW_FIELDS"] as $key => $value) {
    if (!in_array($value, $arSortFields))
        $arSortFields[] = $value;

}

$arResult["SHOW_FIELDS"] = $arSortFields;

global $USER;
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_REQUEST["register_submit_button"]) && $USER->IsAuthorized() && $_POST['check_subscribe']){
    \Bitrix\Main\Loader::includeModule('subscribe');
    $arFields = [
        'RUB_ID' => [$arParams['PRODUCT_ID']],
        'SEND_CONFIRM' => 'N',
        'EMAIL' => $_POST['REGISTER']['EMAIL']
    ];
    $oSubscribe = new \CSubscription();
    $id = $oSubscribe->Add($arFields);
}