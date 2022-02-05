<?php
use Bitrix\Main\Context;
use Bitrix\Main\HttpRequest;
use Bitrix\Saybert\Helpers;

/**
 * Class CommentAddComponent
 */
class CatalogReviewComponent extends CBitrixComponent
{
    protected $_ajaxValidatedRequest;
    protected $_ajaxError;
    protected  $_arIblock;

    public function onPrepareComponentParams($arParams)
    {
        Bitrix\Main\Loader::includeModule('saybert');
        $arParams = parent::onPrepareComponentParams($arParams);
        $this->_arIblock = Helpers\IBlock::getIblock('reviews');
        return $arParams;
    }

    public function executeComponent()
    {

        if(\Bitrix\Main\Context::getCurrent()->getRequest()->isAjaxRequest()){
            if($this->isOurRequest()) {
                $this->prepareResponse();
                //$this->includeComponentTemplate('ajax');
            }
        }
        else {
            $this->arResult['ITEMS'] = $this->getReviews();
            $this->includeComponentTemplate();
        }
    }


    /**
     * @return bool - True - если запрос с данными для текущего компонента
     */
    protected function isOurRequest(){
        $result = $_SERVER['REQUEST_METHOD'] == "POST";
        $result = $result && $_POST['action'] == 'addReview';
        $result = $result && !empty($_POST['element']);
        $result = $result && !empty($_POST['review']);
        return $result;
    }

    /**
     * Обрабатывает ajax вопрос
     */
    public function prepareResponse(){
        global $APPLICATION;
        try {
            $result = $this->ajaxAddReview();
        }
        catch (Exception $e){
            $result = [
                'error' => true,
                'errorMessage' => $e->getMessage()
            ];
        }
        $APPLICATION->RestartBuffer();
        header('Content-Type: application/json');
        echo json_encode($result);
        die();
    }

    public function getReviews(){
        $arFilter = [
            'IBLOCK_ID' => $this->_arIblock['ID'],
            'ACTIVE' => 'Y',
            'PROPERTY_PRODUCT_ID' => $this->arParams['PRODUCT_ID'],
        ];

        return Helpers\IblockElement::getListIblockElement(array(),$arFilter);
    }


    protected function ajaxAddReview(){
        global $USER;
        $arFields = [
            'IBLOCK_ID' => $this->_arIblock['ID'],
            'NAME' => 'Отзыв пользователя от '.date('d.m.Y H:i:s'),
            'ACTIVE' => 'N',
            'DETAIL_TEXT' => htmlspecialchars($_POST['review']),
            'DETAIL_TEXT_TYPE' => 'text'
        ];

        $arProperty = [
            'PRODUCT_ID' => $this->arParams['PRODUCT_ID'],
            'DATE' => date('d.m.Y'),
        ];

        if($USER->IsAuthorized())
            $arProperty['USER_ID'] = $USER->GetID();

        $arFields['PROPERTY_VALUES'] = $arProperty;
        $el = new CIBlockElement();
        $result = $el->Add($arFields);
        if(!$result)
            throw new Exception($el->LAST_ERROR);

        $arUser = $USER->GetByID($USER->GetID())->Fetch();
        $arFields['USER_NAME'] = $arUser['NAME'];
        return $arFields;
    }
}