<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>

<?if($arResult["SECTIONS"]):?>
	<div class="similar_products_sections_wrapp">
		<div class="articles-list sections wrap_md">
			<h3><?=$arParams['TITLE']?></h3>
			<?foreach($arResult["SECTIONS"] as $arItem):?>
				<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				$arItem["SECTION_PAGE_URL"] .= "filter/brand-is-".$arParams['BRAND_CODE']."/apply/";
				?>
				<div class="item iblock section_item_inner" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<?if($arParams["SHOW_SECTION_LIST_PICTURES"] != "N"):?>	
						<?if($arItem["PICTURE"]["SRC"]):?>
							<?$img = CFile::ResizeImageGet($arItem["PICTURE"]["ID"], array( "width" => 120, "height" => 120 ), BX_RESIZE_IMAGE_EXACT, true );?>
							<div class="img">
								<a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=($arItem["PICTURE"]["ALT"] ? $arItem["PICTURE"]["ALT"] : $arItem["NAME"])?>" title="<?=($arItem["PICTURE"]["TITLE"] ? $arItem["PICTURE"]["TITLE"] : $arItem["NAME"])?>" /></a>
							</div>
						<?elseif($arItem["~PICTURE"]):?>
							<?$img = CFile::ResizeImageGet($arItem["~PICTURE"], array( "width" => 120, "height" => 120 ), BX_RESIZE_IMAGE_EXACT, true );?>
							<div class="img">
								<a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=($arItem["PICTURE"]["ALT"] ? $arItem["PICTURE"]["ALT"] : $arItem["NAME"])?>" title="<?=($arItem["PICTURE"]["TITLE"] ? $arItem["PICTURE"]["TITLE"] : $arItem["NAME"])?>" /></a>
							</div>
						<?else:?>
							<div class="img">
								<a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" height="90" /></a>
							</div>
						<?endif;?>
					<?endif;?>
					<div class="section_info <?=( $arParams["SHOW_SECTION_LIST_PICTURES"] == "N" ? "no_img" : "")?>">
						<div class="item-title">
							<a href="<?=$arItem["SECTION_PAGE_URL"]?>"><span><?=$arItem["NAME"]?>&nbsp;</span><?=($arItem["ELEMENT_CNT"] ? '<span class="grey">('.$arItem["ELEMENT_CNT"].')</span>' : '')?></a>
						</div>
						<?if($arItem["SECTIONS"]){?>
							<ul>
								<?foreach( $arItem["SECTIONS"] as $arSubItem ){?>
									<li class="sect"><a href="<?=$arSubItem["SECTION_PAGE_URL"]?>"><?=$arSubItem["NAME"]?><? echo $arSubItem["ELEMENT_CNT"]?'&nbsp;('.$arSubItem["ELEMENT_CNT"].')':'';?></a></li>
								<?}?>
							</ul>
						<?}?>
					</div>
				</div>
			<?endforeach;?>
		</div>
		<?if($arParams["ADD_SECTIONS_CHAIN"] == "Y"):?>
			<?if($arResult["SECTION"]["DESCRIPTION"]):?>
				<div class="main_description"><?=$arResult["SECTION"]["DESCRIPTION"]?></div>
			<?else:?>
				<?$arSection = CIBlockSection::GetList(array(), array( "IBLOCK_ID" => $arResult["SECTION"]["IBLOCK_ID"], "ID" => $arResult["ID"] ), false, array( "ID", "UF_SECTION_DESCR"))->GetNext();?>
				<?if ($arSection["UF_SECTION_DESCR"]):?>
					<div class="main_description"><?=$arSection["UF_SECTION_DESCR"]?></div>
				<?endif;?>
			<?endif;?>
		<?else:?>
		<?endif;?>
	</div>
<?endif;?>