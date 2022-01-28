<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
\Bitrix\Main\Loader::includeModule('saybert');

$oRequest = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
if($oRequest->isAjaxRequest()){
    $result = [
        'error' => false,
    ];
    if(!empty($_REQUEST['name']) && !empty($_REQUEST['`phone'])) {
        try {
            \CEvent::Send('CALL_TO_USER',SITE_ID ,['NAME' => htmlspecialchars($_REQUEST['name']),'PHONE' => htmlspecialchars($_REQUEST['phone'])] );
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
            'message' => 'Заполнены не все данные',
        ];
    }
    echo json_encode($result);die();

}else{
    include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');
    \Bitrix\Iblock\Component\Tools::process404("",true,true,true,'');
}