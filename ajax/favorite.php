<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$data = array();
try {
    if (!check_bitrix_sessid()) throw new Exception("Не удалось подтвердить сессию");
    \Bitrix\Main\Loader::includeModule('saybert');
    switch ($_REQUEST['action']) {
        case 'add':
            if (empty($_REQUEST['PRODUCT_ID'])) throw new Exception("Не указан ID товара");
            $data['id'] = \Bitrix\Saybert\Entity\FavoriteProductTable::addProduct($_REQUEST['PRODUCT_ID']);
            break;
        case 'delete':
            if (empty($_REQUEST['PRODUCT_ID'])) throw new Exception("Не указан ID товара");
           	\Bitrix\Saybert\Entity\FavoriteProductTable::deleteProduct($_REQUEST['PRODUCT_ID']);
            break;
        case 'check':
            if (empty($_REQUEST['PRODUCT_ID'])) throw new Exception("Не указан ID товара");
            $data['in_favorite'] = \Bitrix\Saybert\Entity\FavoriteProductTable::IsInFavorite($_REQUEST['PRODUCT_ID']);
            break;
        default: throw new Exception("Неизвестное действие");
    }
} catch (Exception $e) {
    $data['error'] = $e->getMessage();
}
echo json_encode($data);