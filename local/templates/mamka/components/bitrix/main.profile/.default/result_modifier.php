<?php
CModule::IncludeModule('sale');
$arFilter = array(
    "USER_ID" => $arResult['ID']
);
$rsSaleUser = \CSaleOrderUserProps::GetList(array(),$arFilter);
if($rsSaleUser)
    $arProfile = $rsSaleUser->Fetch();


$USER_ID = intval($arProfile["USER_ID"]);
$PERSON_TYPE = intval($arProfile["PERSON_TYPE_ID"]);
$profileName = $arProfile["NAME"];
$dbProperties = CSaleOrderProps::GetList(
    array("GROUP_SORT" => "ASC", "PROPS_GROUP_ID" => "ASC", "SORT" => "ASC", "NAME" => "ASC"),
    array("PERSON_TYPE_ID" => $PERSON_TYPE, "ACTIVE" => "Y", "USER_PROPS" => "Y", "UTIL" => "N"),
    false,
    false,
    array("*")
);

$arValues = CSaleOrderUserProps::DoLoadProfiles($USER_ID, $PERSON_TYPE);
while($arProp = $dbProperties->Fetch()){
    $arProperties[$arProp['ID']] = $arProp;
}

$needProps = array(
    'PERSONAL_PHONE' => 'PHONE',
);
foreach ($needProps as $userPropCode => $saleUserPropCode){
    if(empty($arResult['User'][$userPropCode])){
        foreach ($arProperties as $arItem){
            if($arItem['CODE'] == $saleUserPropCode){
                if(!empty($arValues[$arProfile['ID']]['VALUES'][$arItem['ID']])) {
                    $arResult['arUser'][$userPropCode] = $arValues[$arProfile['ID']]['VALUES'][$arItem['ID']];
                }

            }
        }
    }
}
