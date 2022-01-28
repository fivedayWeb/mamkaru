<div class="wrapper_inner">
	<table class="middle-h-row">
		<tr>
			<td  class="center_block">
				<div class="main-nav">
					<?if(CMShop::nlo('menu-mobile-fixed')):?>
					<!-- noindex -->
					<?include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/menu.top_general_multilevel_fixed.php');?>
					<!-- /noindex -->
					<?endif;?>
					<?CMShop::nlo('menu-mobile-fixed');?>
				</div>
			</td>
			<td class="logo_wrapp">
				<div class="logo nofill_<?=strtolower(\Bitrix\Main\Config\Option::get('aspro.mshop', 'NO_LOGO_BG', 'N'));?>">
					<?CMShop::ShowLogo();?>
				</div>
			</td>
			<td class="width-100"></td>
			<td class="basket_wrapp custom_basket_class <?=CMShop::getCurrentPageClass()?>">
				<div class="wrapp_all_icons">
					<div class="header-compare-block icon_block iblock" id="compare_line_fixed_mobile">
						<?include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/catalog.compare.list.compare_top.php');?>
					</div>
					<div class="header-cart" id="basket_line_fixed_mobile">
						<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("header-cart");?>
						<?//CSaleBasket::UpdateBasketPrices(CSaleBasket::GetBasketUserID(), SITE_ID);?>
						<?if($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"] === "FLY" && !$isBasketPage && !CSite::InDir(SITE_DIR."order/")):?>
							<script type="text/javascript">
							$(document).ready(function(){
								$.ajax({
									url: arMShopOptions["SITE_DIR"] + "ajax/basket_fly.php",
									type: "post",
									success: function(html){
										$("#basket_line_fixed_mobile").append(html);
									}
								});
							});
							</script>
						<?else:?>
							<?$APPLICATION->IncludeFile(SITE_DIR."include/basket_top.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("BASKET_TOP")));?>
						<?endif;?>
						<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("header-cart", "");?>
					</div>
				</div>
				<div class="clearfix"></div>
			</td>
		</tr>
	</table>
</div>