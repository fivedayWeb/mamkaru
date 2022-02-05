<?php

namespace Bitrix\Saybert\Entity;

use Bitrix\Main\Entity;


/**
 * Сущность любимых товаров
 * Class FavoriteProductTable
 * @package Bitrix\Saybert\Entity
 */
class FavoriteProductTable extends Entity\DataManager
{

    public static function getTableName()
    {
        return 'b_favorite_product';
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),
            new Entity\IntegerField('PRODUCT_ID', [
                'required' => true,
                'title' => 'ID товарв'
            ]),
            new Entity\TextField('USER_ID', [
                'required' => true,
                'title' => 'ID пользователя'
            ]),
            new Entity\DatetimeField('DATE_ADDING', [
                'required' => true,
                'title' => 'Дата добавления'
            ])
        ];
    }

    public static function addProduct($productID)
    {
        global $USER;
        if (!is_numeric($productID)) {
            throw new \Exception("ID товара должено  быть число");
        }

        $arProduct = \Bitrix\Saybert\Helpers\IblockElement::getIblockElement($productID);
        if (!is_array($arProduct) || !$arProduct) {
            throw new \Exception("Товар с ID $productID не найден");
        }
        $arField = array(
            'PRODUCT_ID' => $productID,
            'DATE_ADDING' => new \Bitrix\Main\Type\DateTime(),
        );
        if ($USER->IsAuthorized()) {
            $arField['USER_ID'] = $USER->GetID();
            $result = static::add($arField);
            if (!$result->isSuccess()) {
                throw new \Exception($result->getErrorMessages());
            }
            return $result->getId();
        } else {
            $arField['USER_ID'] = false;
            $_SESSION['FAVORITE_PRODUCTS'][$productID] = $arField;
        }
        return true;

    }

    public static function getProduct($productID)
    {
        return \Bitrix\Saybert\Helpers\IblockElement::getIblockElement($productID);
    }

    public static function getFavoriteProducts()
    {
        global $USER;
        $productIDs = [];
        $arItems = [];
        if (!empty($_SESSION['FAVORITE_PRODUCTS'])) {
            $arItems = $_SESSION['FAVORITE_PRODUCTS'];
        }
        $productIDs = array_keys($_SESSION['FAVORITE_PRODUCTS']);

        if ($USER->IsAuthorized()) {
            $arItemsTmp = $arTableData = static::getList([
                'filter' => [
                    'USER_ID' => $USER->GetID()
                ]
            ])->fetchAll();
            foreach ($arItemsTmp as $arItem) {
                $arItems[$arItem['PRODUCT_ID']] = $arItem;
                $productIDs[] = $arItem['PRODUCT_ID'];
            }
        }


        if (!empty($arItems)) {
            $obCache = new \CPHPCache();
            $cachePath = '/sale/Bitrix_Saybert_Entity_FavoriteProductTable_getFavoriteProducts';
            if ($obCache->InitCache(36000, serialize($productIDs), $cachePath)) {
                $result = $obCache->GetVars();
            } elseif ($obCache->StartDataCache()) {
                $arProducts = \Bitrix\Saybert\Helpers\IblockElement::getListIblockElement(
                    array(),
                    array(
                        'ID' => $productIDs,
                        'ACTIVE' => 'Y',
                    )
                );
                if (empty($arProducts)) {
                    return false;
                }
                $result = [];
                foreach ($arProducts as $arProduct) {
                    $arProduct['FAVORITE_DATA'] = $arItems[$arProduct['ID']];
                    $result[] = $arProduct;
                }
                $obCache->EndDataCache($result);
            }
            return $result;
        }
        return false;
    }

    public static function deleteProduct($productID)
    {
        global $USER;
        if (!is_numeric($productID)) {
            throw new \Exception("ID товара должно быть число");
        }

        $arProduct = \Bitrix\Saybert\Helpers\IblockElement::getIblockElement($productID);
        if (!is_array($arProduct) || !$arProduct) {
            throw new \Exception("Товар с ID $productID не найден");
        }

        if ($USER->IsAuthorized()) {
            $arFilter = array(
                'USER_ID' => $USER->GetID(),
                'PRODUCT_ID' => $productID,
            );
            $res = static::getList(['filter' => $arFilter]);
            while ($arItem = $res->fetch()) {
                $result = static::delete($arItem['ID']);
                if (!$result->isSuccess()) {
                    throw new \Exception($result->getErrorMessages());
                }
            }
        } else {
            unset($_SESSION['FAVORITE_PRODUCTS'][$productID]);
        }
        return true;
    }

    public static function IsInFavorite($productID)
    {
        global $USER;
        if (!is_numeric($productID)) {
            throw new \Exception("ID товара должно быть число");
        }

        if ($USER->IsAuthorized()) {
            $arFilter = array(
                'USER_ID' => $USER->GetID(),
                'PRODUCT_ID' => $productID,
            );
            return static::getCount($arFilter) > 0;
        } else {
            return array_key_exists($productID, $_SESSION['FAVORITE_PRODUCTS']);
        }
    }
}