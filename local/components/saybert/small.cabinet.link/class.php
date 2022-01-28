<?php
use Bitrix\Main\Context;
use Bitrix\Main\HttpRequest;
use Bitrix\Saybert\Helpers;

/**
 * Class CommentAddComponent
 */
class SmallCabinetLink extends CBitrixComponent
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
        global $USER;
        $this->arResult['IS_AUTH'] = $USER->IsAuthorized();
        $this->includeComponentTemplate();
    }
}