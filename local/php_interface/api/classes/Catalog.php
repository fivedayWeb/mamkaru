<?
namespace Custom;

use \Bitrix\Main\Loader;

class Catalog
{
	const IBLOCK_ID = 469;
	const PRODUCT_IBLOCK_ID = 470;

	const PROPERTY_MANUFACTURER_ID = 21208;

	private static $isCheckBarCode = true;
	public static function OnAfterIblockElementUpdate(&$arFields)
	{
		if (!$arFields['RESULT']) return;
		if (!in_array($arFields['IBLOCK_ID'], [self::PRODUCT_IBLOCK_ID])) return;

		$elementId = $arFields['ID'];
		if (!$elementId) return;

		if (self::$isCheckBarCode) {
			self::checkBarCode($elementId);
		}
	}

	public static function OnAfterIblockElementAdd(&$arFields)
	{
		if (!in_array($arFields['IBLOCK_ID'], [self::PRODUCT_IBLOCK_ID])) return;

		$elementId = $arFields['ID'];
		if (!$elementId) return;

		if (self::$isCheckBarCode) {
			self::checkBarCode($elementId);
		}
	}

	public static function checkBarCode($elementId) 
	{
		if (!$elementId) return;
        if (!Loader::includeModule('iblock')) return;

        list($arFields, $arProperties) = \Custom\IblockElementHelper::getData($elementId);

        if ($arFields['ACTIVE'] == 'Y' && empty($arProperties['CML2_BAR_CODE']['VALUE'])) {
        	self::$isCheckBarCode = false;

        	$el = new \CIBlockElement;
        	$el->Update($elementId, ['ACTIVE' => 'N']);

        	self::$isCheckBarCode = true;

        	return false;
        }

        if ($arFields['ACTIVE'] != 'Y' && !empty($arProperties['CML2_BAR_CODE']['VALUE'])) {
        	self::$isCheckBarCode = false;

        	$el = new \CIBlockElement;
        	$el->Update($elementId, ['ACTIVE' => 'Y']);

        	self::$isCheckBarCode = true;

        	return true;
        }
	}
}