<?

class Page
{
	public static function getDomain()
	{
		return (CMain::IsHTTPS() ? "https://" : "http://") . $_SERVER["HTTP_HOST"];
	}

	public static function getURI()
	{
		global $APPLICATION;
		return $APPLICATION->GetCurUri();
	}

	public static function getFull()
	{
		return self::getDomain() . self::getURI();
	}
}