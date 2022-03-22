<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<nav class="navigation">
	<button id="menu-toggle">
		<span class="span1"></span>
		<span class="span2"></span>
		<span class="span3"></span>
	</button>
	<ul class="menu-bottom">
		<?foreach ($arResult as $arItem):?>
		<li class="menu__item">
		<a href="<?=$arItem['LINK']?>" class="menu__link" title="<?=$arItem['TEXT']?>">
				<?=$arItem['TEXT']?>
			</a>
		</li>
		<?endforeach;?>
	</ul>
</nav>