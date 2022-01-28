<?php
use Bitrix\Main\Context;
use Bitrix\Main\HttpRequest;
use Bitrix\Saybert\Helpers;

/**
 * Class CommentAddComponent
 */
class AddSubscribe extends CBitrixComponent
{
    protected $_ajaxValidatedRequest;
    protected $_ajaxError;
    protected  $_arIblock;

    public function onPrepareComponentParams($arParams)
    {
        Bitrix\Main\Loader::includeModule('sale');
        Bitrix\Main\Loader::includeModule('saybert');
        $arParams = parent::onPrepareComponentParams($arParams);
        $this->_arIblock = Helpers\IBlock::getIblock('reviews');
        return $arParams;
    }

    public function executeComponent()
    {
        global $APPLICATION;
        if(!empty($_POST['action']) && !empty($_POST['email']) && $_POST['action']=='addsubscriber'){
            \Bitrix\Main\Loader::includeModule('subscribe');
            $arFields = [
                'RUB_ID' => [$this->arParams['PRODUCT_ID']],
                'SEND_CONFIRM' => 'N',
                'EMAIL' => $_POST['email']
            ];
            $oSubscribe = new \CSubscription();
            $id = $oSubscribe->Add($arFields);
            if($id)
                $result = ['error' => false];
            else{
                $result = [
                    'error' => true,
                    'message' => trim(strip_tags($oSubscribe->LAST_ERROR))
                ];
            }
            if(\Bitrix\Main\Application::getInstance()->getContext()->getRequest()->isAjaxRequest()) {
                $APPLICATION->RestartBuffer();
                echo json_encode($result);
                die();
            }
            else
                $arResult['STATUS'] = $result;
        }
        $this->includeComponentTemplate();
    }
}