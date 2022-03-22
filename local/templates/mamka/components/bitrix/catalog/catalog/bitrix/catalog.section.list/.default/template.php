<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>
<div id="sidebar-categories">
	<ul>
		<?foreach($arResult['SECTIONS'] as $arSection):
			$arClassNameA = $arClassNameLi = [];
			if (in_array($arSection['ID'], $arResult['CURRENT_SECTION_ID'])) {
				$arClassNameA[] = 'active';
				$arClassNameLi[] = 'active';
			}
			if (!empty($arSection['SUB_SECTIONS'])) {
				$arClassNameA[] = 'with-subcategory';
			}
			?>
			<li class="<?=implode(' ', $arClassNameLi)?>">
				<a href="<?=$arSection['SECTION_URL']?>" class="<?=implode(' ', $arClassNameA)?>"><?=$arSection['NAME']?></a>
				<?if(!empty($arSection['SUB_SECTIONS'])):?>
					<ul>
						<?foreach ($arSection['SUB_SECTIONS'] as $arSubSection):
							$arClassNameA = $arClassNameLi = [];
							if (in_array($arSubSection['ID'], $arResult['CURRENT_SECTION_ID'])) {
								$arClassNameA[] = 'active';
								$arClassNameLi[] = 'active';
							}
							if (!empty($arSubSection['SUB_SECTIONS'])) {
								$arClassNameA[] = 'with-subcategory';
							}
							?>
							<li class="<?=implode(' ', $arClassNameLi)?>">
								<a href="<?=$arSubSection['SECTION_URL']?>" class="<?=implode(' ', $arClassNameA)?>"><?=$arSubSection['NAME']?></a>
								<?if(!empty($arSubSection['SUB_SECTIONS'])):?>
									<ul>
										<?foreach($arSubSection['SUB_SECTIONS'] as $arSection3):
											$arClassName = [];
											if (in_array($arSection3['ID'], $arResult['CURRENT_SECTION_ID'])) {
												$arClassName[] = 'active';
											}
											?>
											<li>
												<a href="<?=$arSection3['SECTION_URL']?>" class="<?=implode(' ', $arClassName)?>"><?=$arSection3['NAME']?></a>
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