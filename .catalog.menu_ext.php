<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use \Bitrix\Saybert\Helpers\Iblock;
use \Bitrix\Saybert\Helpers\IblockSection;

global $APPLICATION;
$aMenuLinksExt = array();

if(CModule::IncludeModule('saybert'))
{

	$arIblock = IBlock::getIblock('catalog');
	if ($arIblock) {
		$arSections = IblockSection::getList(
			[
				"left_margin"=>"asc",
				"sort"=>"asc",
				"name"=>"asc"
			],
			[
				'IBLOCK_ID' => $arIblock['ID'],
				'DEPTH_LEVEL' => '1',
				'ACTIVE' => 'Y',
			],
			true
		);

		$aMenuLinks = [];
		foreach($arSections as $arSection){
			if (!$arSection['ELEMENT_CNT']) continue;
			$arSubMenuItems = false;
			$arSubSections = IblockSection::getList(
				[
					'SORT' => "ASC",
					'ID' => "ASC"
				],
				[
					'IBLOCK_ID' => $arIblock['ID'],
					'SECTION_ID' => $arSection['ID'],
					'ACTIVE' => 'Y',
				]
			);
			if (!empty($arSubSections)) {
				foreach ($arSubSections as $arSubSection) {
					$arSubMenuItems[] = [
						$arSubSection['NAME'],
						$arSubSection['SECTION_URL'],
						'',
						''
					];
				}
			}

			$arItemMenu = [
				$arSection['NAME'],
				$arSection['SECTION_URL'],
				$arSubMenuItems,
				[
					'icon' => $arSection['PICTURE'] ? CFile::GetPath($arSection['PICTURE']) : SITE_TEMPLATE_PATH.'/i/category-card.png', 
				]
			];
			$aMenuLinks[] = $arItemMenu;
		}
	}
}

