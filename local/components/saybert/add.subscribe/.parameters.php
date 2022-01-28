<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CModule::IncludeModule('subscribe');
$arSubscribes = ['-'];
$rsSubscribe = \CRubric::GetList([],[]);

while($arSubscribe = $rsSubscribe->Fetch()) {
    $arSubscribes[$arSubscribe['ID']] = "[{$arSubscribe['ID']}]{$arSubscribe['NAME']}";
}
$arComponentParameters = array(
    "PARAMETERS" => array(
        "PRODUCT_ID" => Array(
            "NAME" => "Рассылка",
            "TYPE" => "LIST",
            "VALUES" => $arSubscribes
        ),
    )
);