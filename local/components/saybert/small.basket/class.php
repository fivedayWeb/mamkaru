<?php
use Bitrix\Main\Context;
use Bitrix\Main\HttpRequest;
use Bitrix\Saybert\Helpers;

/**
 * Class CommentAddComponent
 */
class SmallBasket extends CBitrixComponent
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
        $this->arResult['COUNT_PRODUCT'] = $this->getCountBasket();
        $this->includeComponentTemplate();
    }

    public function getCountBasket()
    {
        $count = 0;
        CModule::IncludeModule("sale");
        $rsElement =  CSaleBasket::GetList(false, array("FUSER_ID" => CSaleBasket::GetBasketUserID(),"LID" => SITE_ID,"ORDER_ID" => "NULL"));
        while($arElement = $rsElement->Fetch())
            $count += $arElement['QUANTITY'];

        return $count;
    }
}