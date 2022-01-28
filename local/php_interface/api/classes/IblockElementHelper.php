<?
namespace Custom;

use \Bitrix\Main\Loader;

class IblockElementHelper
{
	public static function getData($elementId)
	{
		if (!$elementId) return;
        if (!Loader::includeModule('iblock')) return;

		$dbElement = \CIBlockElement::GetByID($elementId)->GetNextElement();
        if (!$dbElement) return;

        return [
        	$dbElement->GetFields(), 
        	$dbElement->GetProperties()
        ];
	}

	private static $_cache = [];
	public static function getDataFromCache($elementId)
	{
		if (!array_key_exists($elementId, self::$_cache)) 
		{
	        self::$_cache[$elementId] = self::getData($elementId);
		}
		return self::$_cache[$elementId];
	}
}