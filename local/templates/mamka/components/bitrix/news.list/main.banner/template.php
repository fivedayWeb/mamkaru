<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>
<div id="home-banner">
	<div class="center clear">
		<div id="home-banner-slider">
			<div class="home-banner-slider-wrapper">
				<?foreach($arResult['ITEMS'] as $arItem):?>
					<?if($arItem['PREVIEW_PICTURE'] && $arItem['PREVIEW_PICTURE']['SRC']):
						$href = false;
						if($arItem['PROPERTIES']['URL']['VALUE']) $href = $arItem['PROPERTIES']['URL']['VALUE'];?>
						<a <?if($href):?>href="<?=$href?>"<?endif;?> class="slide">
							<img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>" />
							<div class="slide-title"><?=$arItem['NAME']?></div>
						</a>
					<?endif;?>
				<?endforeach;?>
			</div>
		</div>
	</div>
</div>