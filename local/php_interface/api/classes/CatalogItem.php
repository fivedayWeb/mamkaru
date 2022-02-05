<?

namespace Custom;

use \Bitrix\Main\Loader;

class CatalogItem
{
    public static function convertFromCatalogProductsViewed($arItem)
    {
        $arOffer = $arItem['OFFERS'][array_search(self::getNeedleOffer($arItem),
            array_column($arItem['OFFERS'], 'ID'))];

        if($arOffer) {
            $prices = $arOffer['PRICES'];
        } else {
            $prices = $arItem['PRICES'];
        }

        if ($arOffer) {
            return array(
                'ID' => $arOffer['ID'],
                'NAME' => $arItem['NAME'],
                'PREVIEW_PICTURE' => $arOffer['PREVIEW_PICTURE']['SRC'],
                'PREVIEW_PICTURE_ANOTHER' => $arItem['DETAIL_PICTURE']['SRC'],
                'QUANTITY' => $arOffer['CATALOG_QUANTITY'],
                'PRICE' => $prices['PRICE'],
                'PRICE_PRINT' => $prices['PRICE_PRINT'],
                'DISCOUNT' => $prices['DISCOUNT'],
                'DISCOUNT_PRINT' => $prices['DISCOUNT_PRINT'],
                'BASE_PRICE' => $prices['BASE_PRICE'],
                'BASE_PRICE_PRINT' => $prices['BASE_PRICE_PRINT'],
                'URL' => $arItem['DETAIL_PAGE_URL'],
                'CURRENCY' => reset($arOffer['ITEM_PRICES'])['CURRENCY'],
            );
        } else {
            return array(
                'ID' => $arItem['ID'],
                'NAME' => $arItem['NAME'],
                'PREVIEW_PICTURE' => $arItem['PREVIEW_PICTURE']['SRC'],
                'PREVIEW_PICTURE_ANOTHER' => $arItem['DETAIL_PICTURE']['SRC'],
                'QUANTITY' => $arItem['CATALOG_QUANTITY'],
                'PRICE' => $prices['PRICE'],
                'PRICE_PRINT' => $prices['PRICE_PRINT'],
                'DISCOUNT' => $prices['DISCOUNT'],
                'DISCOUNT_PRINT' => $prices['DISCOUNT_PRINT'],
                'BASE_PRICE' => $prices['BASE_PRICE'],
                'BASE_PRICE_PRINT' => $prices['BASE_PRICE_PRINT'],
                'URL' => $arItem['DETAIL_PAGE_URL'],
                'CURRENCY' => reset($arItem['ITEM_PRICES'])['CURRENCY'],
            );
        }
    }

    public static function convertFromFavouriteProducts($arItem)
    {

        $arOffer = $arItem['OFFERS'][array_search(self::getNeedleOffer($arItem),
            array_column($arItem['OFFERS'], 'ID'))];

        if ($arOffer) {
            $prices = $arOffer['PRICES'];
        } else {
            $prices = $arItem['PRICES'];
        }


        if ($arOffer) {
            return array(
                'ID' => $arOffer['ID'],
                'NAME' => $arItem['NAME'],
                'PREVIEW_PICTURE' => $arOffer['PREVIEW_PICTURE'],
                'PREVIEW_PICTURE_ANOTHER' => $arItem['PREVIEW_PICTURE'],
                'QUANTITY' => $arOffer['CATALOG_QUANTITY'],
                'PRICE' => $prices['DISCOUNT_PRICE'],
                'DISCOUNT' => $prices['RESULT_PRICE']['DISCOUNT'],
                'BASE_PRICE' => $prices['RESULT_PRICE']['BASE_PRICE'],
                'URL' => $arItem['DETAIL_PAGE_URL'],
                'CURRENCY' => $prices['CURRENCY'],
            );
        } else {
            return array(
                'ID' => $arItem['ID'],
                'NAME' => $arItem['NAME'],
                'PREVIEW_PICTURE' => $arItem['PREVIEW_PICTURE'],
                'PREVIEW_PICTURE_ANOTHER' => $arItem['PREVIEW_PICTURE'],
                'QUANTITY' => $arItem['CATALOG_QUANTITY'],
                'PRICE' => $prices['DISCOUNT_PRICE'],
                'DISCOUNT' => $prices['RESULT_PRICE']['DISCOUNT'],
                'BASE_PRICE' => $prices['RESULT_PRICE']['BASE_PRICE'],
                'URL' => $arItem['DETAIL_PAGE_URL'],
                'CURRENCY' => $prices['CURRENCY'],
            );
        }
    }

    public static function convertFromBrands($arItem)
    {
        foreach ($arItem['OFFERS'] as &$offer) {
            $offer['ID'] = $offer['CATALOG']['ID'];
        }

        if (count($arItem['OFFERS'])) {
            foreach ($arItem['OFFERS'] as &$offer) {
                $offer['CATALOG_QUANTITY'] = $offer['CATALOG']['QUANTITY'];
            }
        } else {
            $arItem['CATALOG_QUANTITY'] = $arItem['CATALOG']['QUANTITY'];
        }

        $needleOffer = self::getNeedleOffer($arItem);

        $arOffer = $arItem['OFFERS'][array_search($needleOffer,
            array_column($arItem['OFFERS'], 'ID'))];

        if ($arOffer) {
            $prices = $arOffer['PRICES'];
        } else {
            $prices = $arItem['PRICES'];
        }

        if ($arOffer) {
            return array(
                'ID' => $needleOffer,
                'NAME' => $arItem['NAME'],
                'PREVIEW_PICTURE' => $arOffer['PREVIEW_PICTURE'],
                'PREVIEW_PICTURE_ANOTHER' => $arItem['PREVIEW_PICTURE'],
                'QUANTITY' => $arOffer['CATALOG_QUANTITY'],
                'PRICE' => $prices['DISCOUNT_PRICE'],
                'DISCOUNT' => $prices['RESULT_PRICE']['DISCOUNT'],
                'BASE_PRICE' => $prices['RESULT_PRICE']['BASE_PRICE'],
                'URL' => $arItem['DETAIL_PAGE_URL'],
                'CURRENCY' => $prices['CURRENCY'],
            );
        } else {
            return array(
                'ID' => $arItem['ID'],
                'NAME' => $arItem['NAME'],
                'PREVIEW_PICTURE' => $arItem['PREVIEW_PICTURE'],
                'QUANTITY' => $arItem['CATALOG_QUANTITY'],
                'PRICE' => $prices['DISCOUNT_PRICE'],
                'DISCOUNT' => $prices['RESULT_PRICE']['DISCOUNT'],
                'BASE_PRICE' => $prices['RESULT_PRICE']['BASE_PRICE'],
                'URL' => $arItem['DETAIL_PAGE_URL'],
                'CURRENCY' => $prices['CURRENCY'],
            );
        }
    }

    public static function convertFromCatalogSection($arItem)
    {
        $is_sku = $arItem['IS_SKU'];
        $arItem['OFFERS'] = array_reverse($arItem['OFFERS']);
        $arOffer = $arItem['OFFERS'][array_search(self::getNeedleOffer($arItem),
            array_column($arItem['OFFERS'], 'ID'))];

        if ($arOffer) {
            $prices = $arOffer['PRICES'];
        } else {
            $prices = $arItem['PRICES'];
        }

        if ((count($prices) > 1) && (in_array('BASE2', array_keys($prices)))) {
            $prices = $prices['BASE2'];
        } else {
            $prices = reset($prices);
        }

        if ($arOffer) {
            if($is_sku && count($arItem['OFFERS']) > 1) {
                return array(
                    'ID' => $arOffer['ID'],
                    'NAME' => $arItem['NAME'],
                    'PREVIEW_PICTURE' => $arOffer['PREVIEW_PICTURE']['SRC'],
                    'PREVIEW_PICTURE_ANOTHER' => $arItem['PREVIEW_PICTURE']['SRC'],
                    'QUANTITY' => $arOffer['CATALOG_QUANTITY'],
                    'PRICE' => $prices['DISCOUNT_VALUE'],
                    'DISCOUNT' => $prices['DISCOUNT_DIFF'],
                    'BASE_PRICE' => $prices['VALUE_VAT'],
                    'URL' => $arItem['DETAIL_PAGE_URL'],
                    'CURRENCY' => $prices['CURRENCY'],
                    'OFFERS' => $arItem['OFFERS'],
                );
            } else {
                return array(
                    'ID' => $arOffer['ID'],
                    'NAME' => $arItem['NAME'],
                    'PREVIEW_PICTURE' => $arOffer['PREVIEW_PICTURE']['SRC'],
                    'PREVIEW_PICTURE_ANOTHER' => $arItem['PREVIEW_PICTURE']['SRC'],
                    'QUANTITY' => $arOffer['CATALOG_QUANTITY'],
                    'PRICE' => $prices['DISCOUNT_VALUE'],
                    'DISCOUNT' => $prices['DISCOUNT_DIFF'],
                    'BASE_PRICE' => $prices['VALUE_VAT'],
                    'URL' => $arItem['DETAIL_PAGE_URL'],
                    'CURRENCY' => $prices['CURRENCY'],
                );
            }
        } else {
            return array(
                'ID' => $arItem['ID'],
                'NAME' => $arItem['NAME'],
                'PREVIEW_PICTURE' => $arItem['PREVIEW_PICTURE']['SRC'],
                'PREVIEW_PICTURE_ANOTHER' => $arItem['PREVIEW_PICTURE']['SRC'],
                'QUANTITY' => $arItem['CATALOG_QUANTITY'],
                'PRICE' => $prices['DISCOUNT_VALUE'],
                'DISCOUNT' => $prices['DISCOUNT_DIFF'],
                'BASE_PRICE' => $prices['VALUE_VAT'],
                'URL' => $arItem['DETAIL_PAGE_URL'],
                'CURRENCY' => $prices['CURRENCY'],
            );
        }
    }

    public static function getNeedleOffer($item)
    {
        $offers = $item['OFFERS'];

        if (!count($offers)) {
            return false;
        }
        usort($offers, function ($a, $b) {
            if ($a['ID'] == $b['ID']) {
                return 0;
            }
            return ($a['ID'] > $b['ID']) ? -1 : 1;
        });
        if(reset($offers)['PRICES']['DISCOUNT_PRICE'] > 0) {
            usort($offers, function ($a, $b) {
                if ($a['PRICES']['DISCOUNT_PRICE'] == $b['PRICES']['DISCOUNT_PRICE']) {
                    return 0;
                }
                return ($a['PRICES']['DISCOUNT_PRICE'] < $b['PRICES']['DISCOUNT_PRICE']) ? -1 : 1;
            });
        } elseif(is_array(reset($offers)['PRICES'])) {
            usort($offers, function ($a, $b) {
                if (reset($a['PRICES'])['DISCOUNT_VALUE'] == reset($b['PRICES'])['DISCOUNT_VALUE']) {
                    return 0;
                }
                return (reset($a['PRICES'])['DISCOUNT_VALUE'] < reset($b['PRICES'])['DISCOUNT_VALUE']) ? -1 : 1;
            });
        }

        foreach ($offers as $offer) {
            if (isset($offer['CATALOG_QUANTITY']) && $offer['CATALOG_QUANTITY'] <= 0) {
                continue;
            }
            return $offer['ID'];
        }

        $firstOffer = reset($offers);
        if ($firstOffer['ID']) {
            return $firstOffer['ID'];
        }
        return false;
    }
}