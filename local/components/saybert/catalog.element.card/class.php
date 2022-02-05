<?php
use Bitrix\Main\Context;
use Bitrix\Main\HttpRequest;
use Bitrix\Saybert\Helpers;

/**
 * Class CatalogElementCardComponent
 */
class CatalogElementCardComponent extends CBitrixComponent
{
    protected $_arItem;

    public function onPrepareComponentParams($arParams)
    {
        Bitrix\Main\Loader::includeModule('saybert');
        $arParams = parent::onPrepareComponentParams($arParams);
        return $arParams;
    }

    public function executeComponent()
    {
        if(!empty($this->arParams['arItem'])) {
            $this->arResult['ITEM'] = $this->arParams['arItem'];
            if (!empty($this->arResult['OFFERS']))
                $this->_updateOffer();
            $this->includeComponentTemplate('trace');
        }
        else {
            $this->_prepareComponentData();
            $this->includeComponentTemplate();
        }


    }

    private function _updateOffer(){
        //TODO:сделать
    }

    private function _prepareComponentData(){
        global $USER;
        $arElement = \Bitrix\Saybert\Helpers\IblockElement::getListIblockElement(array(),array(
            'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
            'ID' => $this->arParams['ELEMENT_ID']
        ));
        if(empty($arElement))
            ShowError('Элемент не найден');
        elseif(count($arElement) > 1 )
            ShowError('Найдено больше одного элемента');

        $arElement = array_shift($arElement);
        
        $arElement['CATALOG'] = \Bitrix\Saybert\Helpers\CatalogElement::getElementByID($arElement['ID']);

        $this->arResult['ELEMENT'] = $arElement;
    }

}