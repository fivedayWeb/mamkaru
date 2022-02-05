<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<? $bAjax = $_REQUEST['ajax_get'] == 'Y'; ?>
<hr class="long"/>
<?if($bAjax) {$APPLICATION->RestartBuffer();}?>
<ul class="brands_list ajax_pagination_wrapper">
	<?foreach( $arResult["ITEMS"] as $arItem ){
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>
		<li class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
			<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
				<?if( is_array($arItem["PREVIEW_PICTURE"]) ){?>
					<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"]?$arItem["PREVIEW_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"]?$arItem["PREVIEW_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
				<?}elseif( is_array($arItem["DETAIL_PICTURE"]) ){?>
					<img src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="<?=($arItem["DETAIL_PICTURE"]["ALT"]?$arItem["DETAIL_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=($arItem["DETAIL_PICTURE"]["TITLE"]?$arItem["DETAIL_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
				<?}else{?>
					<span><?=$arItem["NAME"]?></span>
				<?}?>
			</a>
		</li>
	<?}?>
<?if(!$bAjax):?>
	</ul>
<?endif;?>
<?if($arResult["NAV_STRING"]):?>
	<div class="bottom_nav" data-pagination-items=".item">
		<?=$arResult["NAV_STRING"]?>
	</div>
<?endif;?>
<?if($bAjax) {die();}?>