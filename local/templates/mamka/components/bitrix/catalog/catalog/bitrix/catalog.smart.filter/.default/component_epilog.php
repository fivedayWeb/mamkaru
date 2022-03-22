<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */

CJSCore::Init(array('fx', 'popup'));

\Bitrix\Main\Page\Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/jquery-ui.min.css');
\Bitrix\Main\Page\Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/jquery-ui.min.js');
?>