<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<h1 class="content-header"><?=$arResult['NAME']?></h1>
<div class="promo-block clear">
	<?if($arResult['DETAIL_PICTURE']['SRC']):?>
		<div class="promo-item mobile-hide">
			<img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" alt="<?=$arResult['NAME']?>" />
		</div>
	<?endif;?>
	<?=$arResult['DETAIL_TEXT']?>
</div>
<?php echo "news/brands/bitrix/news.detail/.default/template.php"; ?>



