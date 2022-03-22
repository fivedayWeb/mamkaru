<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
CModule::IncludeModule('saybert');
use \Bitrix\Saybert\Helpers\IblockSection;

$arSectionSort = [
	"sort" => "asc",
	"left_margin" => "asc",
	"id" => "asc"
];
$arSections = IblockSection::getList(
	$arSectionSort,
	[
		'IBLOCK_ID' => $arParams['IBLOCK_ID'],
		'DEPTH_LEVEL' => '1',
		'ACTIVE' => 'Y',
	],
	true
);
$arResult['SECTIONS'] = [];
foreach($arSections as $arSection){
    if (!$arSection['ELEMENT_CNT']) continue;
	$arSubSections = IblockSection::getList(
		$arSectionSort,
		[
			'IBLOCK_ID' => $arParams['ID'],
			'SECTION_ID' => $arSection['ID'],
			'ACTIVE' => 'Y',
		],
		true
	);
	if (!empty($arSubSections)) {
		foreach ($arSubSections as $key2 => &$arSection2) {
			if (!$arSection2['ELEMENT_CNT']) {
				unset($arSubSections[$key2]);
				continue;
			}
			$arSections3 = IblockSection::getList(
				$arSectionSort,
				[
					'IBLOCK_ID' => $arParams['ID'],
					'SECTION_ID' => $arSection2['ID'],
					'ACTIVE' => 'Y',
				],
				true
			);
			foreach ($arSections3 as $key3 => $arSection3) {
				if (!$arSection3['ELEMENT_CNT']) {
					unset($arSections3[$key3]);
					continue;
				}
			}
			$arSection2['SUB_SECTIONS'] = $arSections3;
		}
		unset($arSection2);
		$arSection['SUB_SECTIONS'] = $arSubSections;
	}
	$arResult['SECTIONS'][] = $arSection;
}
?>
<section id="content">
	<div class="center">
		<aside id="sidebar" class="left-sidebar">
			<div id="sidebar-categories">
				<ul>
					<?foreach($arResult['SECTIONS'] as $arSection):?>
						<li>
							<a href="<?=empty($arSection['SUB_SECTIONS']) ? $arSection['SECTION_URL'] : '' ?>" 
								class="<?=!empty($arSection['SUB_SECTIONS']) ? 'with-subcategory' : '' ?>">
								<?=$arSection['NAME']?>
							</a>

							<?if (!empty($arSection['SUB_SECTIONS'])):?>
								<ul>
									<?foreach($arSection['SUB_SECTIONS'] as $arSubSection):?>
										<li>
											<?
											$arClassName = array();
											if (!empty($arSubSection['SUB_SECTIONS'])) {
												$arClassName[] = 'with-subcategory';
											}
											?>
											<a href="<?=$arSubSection['SECTION_URL']?>" class="<?=implode(' ', $arClassName)?>"><?=$arSubSection['NAME']?></a>
											<?if(!empty($arSubSection['SUB_SECTIONS'])):?>
												<ul>
													<?foreach($arSubSection['SUB_SECTIONS'] as $arSection3):?>
														<li>
															<a href="<?=$arSection3['SECTION_URL']?>"><?=$arSection3['NAME']?></a>
														</li>
													<?endforeach;?>
												</ul>
											<?endif;?>
										</li>
									<?endforeach;?>
								</ul>
							<?endif;?>
						</li>
					<?endforeach;?>
				</ul>
			</div>
		</aside>
		<div class="right-content">
			<h1>Каталог</h1>
			<div class="catalog-content-items">
				<?foreach ($arResult['SECTIONS'] as $arSection):?>
					<a href="<?=$arSection['SECTION_URL']?>" class="category-card">
						<?if ($arSection['PICTURE']):?>
							<?$picture = CFile::ResizeImageGet($arSection['PICTURE'], array('width' => 253, 'height'=> 420), BX_RESIZE_IMAGE_PROPORTIONAL);?>
							<img src="<?=$picture['src']?>" alt="<?=$arSection['NAME']?>" />
						<?else:?>
							<img src="<?=SITE_TEMPLATE_PATH?>/i/category-card.png"  alt="<?=$arSection['NAME']?>" />
						<?endif;?>
						<div class="category-card-title"><?=$arSection['NAME']?></div>
					</a>
				<?endforeach;;?>
			</div>
		</div>
	</div>
</section>
