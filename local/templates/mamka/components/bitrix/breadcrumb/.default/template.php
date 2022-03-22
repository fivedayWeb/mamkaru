<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

//delayed function must return a string
if(empty($arResult))
	return "";

$strReturn = '';

foreach ($arResult as &$item) {
    if(count(explode('/',$item["LINK"])) > 2) {
        $r = end(array_filter(explode('/',$item["LINK"]), function($element) {
            return !empty($element);
        }));
        $db_r = CIBlockSection::getList(
            [],
            ['CODE' => $r],
            false,
            ['NAME']
        )->fetch();
        if($db_r) {
            $item['TITLE'] = $db_r['NAME'];
        }
    }
}
unset($item);

//we can't use $APPLICATION->SetAdditionalCSS() here because we are inside the buffered function GetNavChain()
$css = $APPLICATION->GetCSSArray();

$strReturn .= '<div id="breadcrumbs" style="background: url(\''.SITE_TEMPLATE_PATH.'/i/breadcrumbs-bg.png\') no-repeat;"><div class="center"><ul>';

$itemSize = count($arResult);
for($index = 0; $index < $itemSize; $index++)
{
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);

	$nextRef = ($index < $itemSize-2 && $arResult[$index+1]["LINK"] <> ""? ' itemref="bx_breadcrumb_'.($index+1).'"' : '');
	$child = ($index > 0? ' itemprop="child"' : '');
	$arrow = ($index > 0? '<i class="fa fa-angle-right"></i>' : '');

	if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1)
	{
		$strReturn .= "<li><a href=\"{$arResult[$index]["LINK"]}\">{$title}</a><span>/</span></li>";
	}
	else
	{
		$strReturn .= "<li><a>{$title}</a></li>";
	}
}

$strReturn .= '</ul></div></div>';

return $strReturn;
