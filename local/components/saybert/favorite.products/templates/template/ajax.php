<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$data = array();
try {
    if (!check_bitrix_sessid()) throw new Exception("Не удалось подтвердить сессию");
    switch ($_REQUEST['action']) {
        case 'delete':
            if (empty($_REQUEST['PRODUCT_ID'])) throw new Exception("Не указан ID товара");
            \Bitrix\Main\Loader::includeModule('saybert');
           	\Bitrix\Saybert\Entity\FavoriteProductTable::deleteProduct($_REQUEST['PRODUCT_ID']);
            break;
        default: throw new Exception("Неизвестное действие");
    }
} catch (Exception $e) {
    $data['error'] = $e->getMessage();
}
echo json_encode($data);