<?php

$arOrders = $arResult['ORDERS'];
$ids = [];
$properties = [];

foreach ($arOrders as $item) {
    $ids[] = $item['ORDER']['ID'];
}
//get props
$db_sales = CSaleOrder::GetList(
    array(
        "DATE_INSERT" => "ASC"
    ),
    array(
        "ID" => $ids
    ),
    false,
    false,
    array(
        "ID",
        "PROPERTY_VAL_BY_CODE_SEND_PAYMENT_LINK"
    )
);

while ($ar_sales = $db_sales->Fetch()) {
    $properties[$ar_sales['ID']] = $ar_sales;
}

foreach ($arOrders as &$item) {
    $item['ORDER']['PROPS'] = $properties[$item['ORDER']['ID']];
}

usort($arOrders, function ($a, $b) {
    return $a['ORDER']['ID'] < $b['ORDER']['ID'] ? 1 : -1;
});

$arResult['ORDERS'] = $arOrders;