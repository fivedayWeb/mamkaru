<?php
use Bitrix\Main\Context;
use Bitrix\Main\HttpRequest;
use Bitrix\Saybert\Helpers;

/**
 * Class CommentAddComponent
 */
class FavoriteProductsComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        Bitrix\Main\Loader::includeModule('saybert');
        $arParams = parent::onPrepareComponentParams($arParams);
        return $arParams;
    }

    public function executeComponent()
    {
        $this->arResult['ITEMS'] = \Bitrix\Saybert\Entity\FavoriteProductTable::getFavoriteProducts();
        $this->setResultCacheKeys(array(
            "ITEMS"
        ));
        $this->includeComponentTemplate();
    }
}