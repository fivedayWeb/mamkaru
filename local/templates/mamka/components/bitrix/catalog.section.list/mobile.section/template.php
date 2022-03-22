<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>

<div id="mobile-categories">
    <?foreach ($arResult['SECTIONS'] as $arSection):
    	$picture = \CFile::ResizeImageGet($arSection['UF_MENU_ICON'], array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL);
    	?>
	    <a href="<?=$arSection['SECTION_PAGE_URL']?>" class="active">
	    	<img src="<?=$picture['src']?>" class="mobile-categories-icon" alt="<?=$arSection['NAME']?>" />
	        <span><?=$arSection['NAME']?></span>
	    </a>
    <?endforeach;?>
</div>