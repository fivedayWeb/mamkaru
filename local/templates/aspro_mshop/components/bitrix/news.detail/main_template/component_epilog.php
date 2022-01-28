<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
	<?if ($arParams["SHOW_LINKED_PRODUCTS"]=="Y" && $arResult["DISPLAY_PROPERTIES"][$arParams["LINKED_PRODUCTS_PROPERTY"]]["VALUE"]):?>
		<?global $TEMPLATE_OPTIONS;?>
		<hr class="long"/>
		<div class="similar_products_wrapp">
				<?if(CSite::InDir(SITE_DIR."sale")){?>
					<h3><?=GetMessage("ACTION_PRODUCTS");?></h3>
				<?}else{?>
					<h3><?=GetMessage("ACTION_PRODUCTS_LINK");?></h3>
				<?}?>
				<?if(!$arParams["CATALOG_FILTER_NAME"]){
					$arParams["CATALOG_FILTER_NAME"]="arrProductsFilter";
				}?>
				<div class="module-products-corusel product-list-items catalog">
					<?$GLOBALS[$arParams["CATALOG_FILTER_NAME"]] = array("ID" => $arResult["DISPLAY_PROPERTIES"][$arParams["LINKED_PRODUCTS_PROPERTY"]]["VALUE"] );
					$template=($arParams["VIEW_LINKED_PRODUCTS"]=="slider" ? "products_slider" : "products_block" );?>
					<div class="wraps goods-block with-padding block ajax_load catalog">
						<?$bAjax = ((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest")  && (isset($_GET["ajax_get"]) && $_GET["ajax_get"] == "Y"));?>
						<?if($bAjax):?>
							<?$APPLICATION->RestartBuffer();?>
						<?endif;?>

						<?include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/news.detail.'.$template.'.php');?>

						<?if($bAjax):?>
							<?die();?>
						<?endif;?>
					</div>
				</div>
		</div>
	<?endif;?>
	<?if ($arParams["SHOW_SERVICES_BLOCK"]=="Y"):?>
		<div class="ask_big_block">
			<div class="ask_btn_block">
				<a class="button vbig_btn wides services_btn" data-title="<?=$arResult["NAME"];?>"><span><?=GetMessage("SERVICES_CALL")?></span></a>
			</div>
			<div class="description">
				<div class="desc">
					<?$APPLICATION->IncludeFile(SITE_DIR."include/services_block_description.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("ASK_QUESTION_DETAIL_TEXT"), ));?>
				</div>
				<?if($templateData['PRICE']):?>
					<div class="price services">
						<div class="price_new">
							<?=$templateData['PRICE'];?>
						</div>
					</div>
				<?endif;?>
			</div>
			<div class="clear"></div>
		</div>
	<?endif;?>
<?if ($arParams["SHOW_BACK_LINK"]=="Y"):?>
	<?$refer=$_SERVER['HTTP_REFERER'];
	if (strpos($refer, $arResult["LIST_PAGE_URL"])!==false) {?>
		<div class="back"><a class="back" href="javascript:history.back();"><span><?=GetMessage("BACK");?></span></a></div>
	<?}else{?>
		<div class="back"><a class="back" href="<?=$arResult["LIST_PAGE_URL"]?>"><span><?=GetMessage("BACK");?></span></a></div>
	<?}?>
<?endif;?>