<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
\Bitrix\Main\Loader::includeModule('saybert');

$oRequest = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
if($oRequest->isAjaxRequest()){
    $result = [
        'error' => false,
    ];
    if(!empty($_REQUEST['productID'])) {
        try {
            $productID = $_REQUEST['productID'];
            \Bitrix\Saybert\Entity\FavoriteProductTable::addProduct($productID);
        } catch (Exception $e) {
            $result = [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }
    else{
        $result = [
            'error' => true,
            'message' => 'Нужно указать индетификатор товара',
        ];
    }
    echo json_encode($result);die();

}else{
    include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');
    \Bitrix\Iblock\Component\Tools::process404("",true,true,true,'');
}