<?php
use Bitrix\Main\Context;
use Bitrix\Main\HttpRequest;
use Bitrix\Saybert\Helpers;

/**
 * Class CatalogElementCardComponent
 */
class IblockElementsByCategories extends CBitrixComponent
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
        $arSections = \Bitrix\Saybert\Helpers\IblockSection::getList(
            array('SORT' => "ASC",'NAME' => 'ASC'),
            array(
                'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
                'ACTIVE' => 'Y'
            )
        );
        $this->arResult['SECTIONS'] = [];
        foreach($arSections as $arSection){
            $arElements = \Bitrix\Saybert\Helpers\IblockElement::getListIblockElement(
                array(
                    ['SORT' => "ASC",'NAME' => 'ASC']
                ),
                array(
                    'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
                    'ACTIVE' => "Y",
                    'SECTION_ID' => $arSection['ID']
                )
            );
            $arSection['ELEMENTS'] = $arElements;
            $this->arResult['SECTIONS'][] = $arSection;
        }
        $this->includeComponentTemplate();
    }
}