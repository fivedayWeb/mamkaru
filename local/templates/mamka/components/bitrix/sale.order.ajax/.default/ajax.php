<? require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

Cmodule::IncludeModule('catalog');

$error = array();
$errorMail = array();

foreach ($_POST['ITEMS'] as $basketItem) {
    $db_res = CCatalogProduct::GetList(
        array("QUANTITY" => "DESC"),
        array("ID" => $basketItem['PRODUCT_ID']),
        false,
        false,
        array()
    );
    $ar_res = $db_res->Fetch();

    if ($ar_res['QUANTITY'] < $basketItem['QUANTITY']) {
        $error[] = $basketItem['NAME'] . ' - Доступно ' . $ar_res['QUANTITY'] . ' из ' . $basketItem['QUANTITY'];
        $errorMail[] = $basketItem['NAME'] . ' - Доступно ' . $ar_res['QUANTITY'] . ' из ' . $basketItem['QUANTITY'];
    }
}
if (count($error)) {
    $n = $_POST['USERDATA']['NAME'];
    $u = $_POST['USERDATA']['URL'];
    $link = "<a href = '$u'>$n</a>";
    CEvent::Send("WF_ORDER_ERROR", 's1', array("MESSAGE" => implode('<br>', $errorMail), "ORDER_USER" => $link));
    $error[] = 'Извините, пока Вы оформляли заказ другой пользователь уже оплатил некоторый товар из Вашего заказа.';
    $error[] = 'Измените состав заказа для того чтобы продолжить его оформление!';
    echo(implode('<br>', $error));
} else {
    echo false;
}

