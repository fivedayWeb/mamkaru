<?
// переменные для хранения какую версию показывать мобильную или полную, инициализируются в header.php
class SiteVersion
{
    public static $isMobile = false;
    public static $isTablet = false;

    public static function Init()
    {
    	$Mobile_Detect = new Mobile_Detect();
		SiteVersion::$isMobile = $Mobile_Detect->isMobile();
		SiteVersion::$isTablet = $Mobile_Detect->isTablet();
    }

    public static function isMobile()
    {
    	return self::$isMobile;
    }
    public static function isTablet()
    {
    	return self::$isTablet;
    }
    public static function isPhone()
    {
    	return self::isMobile() && !self::isTablet();
    }
}