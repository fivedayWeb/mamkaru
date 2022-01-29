<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;
$aMenuLinksExt = array();

if(CModule::IncludeModule('saybert'))
{
	$iblock = \Bitrix\Saybert\Helpers\IBlock::getIblock("catalog");
	$arSections = \Bitrix\Saybert\Helpers\IblockSection::getList(
		[
			'SORT' => "ASC",
			'NAME' => "ASC"
		],
		[
			"IBLOCK_ID" => $iblock['ID'],
			"ACTIVE" => "Y",
			"DEPTH_LEVEL" => 1,
		],
		false,
		array('UF_*')
	);
	foreach($arSections as $arSection){
		$arFileIcon = CFile::GetPath($arSection['UF_MENU_ICON']);
		$aMenuLinksExt[] = [
			$arSection['NAME'],
			$arSection['SECTION_URL'],
			[],
			[
				'MENU_ICON' => CFile::GetPath($arSection['UF_MENU_ICON']),
				'MENU_ICON_ACTIVE' => CFile::GetPath($arSection['UF_MENU_ICON_ACTIVE']),
			],
			""
		];
	}
}

$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
?>