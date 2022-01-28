<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?
global $TEMPLATE_OPTIONS;
$useLongBanners = true;
$templateData = array(
    'EMPTY_ITEMS' => !count($arResult['ITEMS']),
);
?>
<?if($arResult["ITEMS"]):?>
	<div class="top_slider_wrapp">
		<?//$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.flexslider-min.js',true)?>
		<div class="flexslider">
			<ul class="slides">
				<?
				$bShowH1 = false;
				$bBannerLight = false;
				?>
				<?foreach($arResult["ITEMS"] as $i => $arItem):?>
					<?
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					$background = is_array($arItem["DETAIL_PICTURE"]) ? $arItem["DETAIL_PICTURE"]["SRC"] : $this->GetFolder()."/images/background.jpg";
					$target = $arItem["PROPERTIES"]["TARGETS"]["VALUE_XML_ID"];
					$bH1 = (isset($arItem['PROPERTIES']['TITLE_H1']) && $arItem['PROPERTIES']['TITLE_H1']['VALUE_XML_ID'] == 'Y' ? true : false);
					
					if(!$i && ($arItem["PROPERTIES"]["DARK_MENU_COLOR"]["VALUE"] != "Y"))
						$bBannerLight = true;
					
					?>
					<li class="box<?=($arItem["PROPERTIES"]["TEXTCOLOR"]["VALUE_XML_ID"] ? " ".$arItem["PROPERTIES"]["TEXTCOLOR"]["VALUE_XML_ID"] : "");?><?=($arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"] ? " ".$arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"] : " left");?>" data-nav_color="<?=($arItem["PROPERTIES"]["NAV_COLOR"]["VALUE_XML_ID"] ? $arItem["PROPERTIES"]["NAV_COLOR"]["VALUE_XML_ID"] : "");?>" data-text_color="<?=($arItem["PROPERTIES"]["DARK_MENU_COLOR"]["VALUE"] != "Y" ? "light" : "");?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>" style="background-image: url('<?=$background?>') !important;">
						<?if($arItem["PROPERTIES"]["URL_STRING"]["VALUE"]):?>
							<a class="target" href="<?=$arItem["PROPERTIES"]["URL_STRING"]["VALUE"]?>" <?=(strlen($target) ? 'target="'.$target.'"' : '')?>></a>
						<?endif;?>
							<div class="wrapper_inner">
								<?
								$position = "center left";
								if($arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"]){
									if($arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"] == "left"){
										$position = "center right";
									}elseif($arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"] == "right"){
										$position = "center left";
									}elseif($arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"] == "center"){
										$position = "center center";
									}elseif($arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"] == "image"){
										$position = "center";
									}
								}
								?>
								<table class="slider_table" <?if($arItem["PREVIEW_PICTURE"]):?>style="background: url(<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>) <?=$position;?> no-repeat"<?endif;?>>
									<tbody><tr>
										<?if($arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"] != "image"):?>
											<?ob_start();?>
												<td class="text <?=$arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"];?>">

													<?if($arItem["NAME"]):?>
														<div class="banner_title_wrap">
															<?if($bH1 && !$bShowH1):?>
																<h1 class="banner_title"><?=$arItem["~NAME"]?></h1>
																<?$bShowH1 = true;?>
															<?else:?>
																<?if($arItem["PROPERTIES"]["URL_STRING"]["VALUE"]):?>
																	<a href="<?=$arItem["PROPERTIES"]["URL_STRING"]["VALUE"]?>" <?=(strlen($target) ? 'target="'.$target.'"' : '')?>>
																<?endif;?>
																<span class="banner_title"><span><?=strip_tags($arItem["~NAME"], "<br><br/>");?></span></span>
																<?if($arItem["PROPERTIES"]["URL_STRING"]["VALUE"]):?>
																	</a>
																<?endif;?>
															<?endif;?>
														</div>
													<?endif;?>
													<?if($arItem["PREVIEW_TEXT"]):?>
														<div class="banner_text"><?=$arItem["PREVIEW_TEXT"];?></div>
													<?endif;?>
													<?if((!empty($arItem["PROPERTIES"]["BUTTON2TEXT"]["VALUE"]) && !empty($arItem["PROPERTIES"]["BUTTON2LINK"]["VALUE"])) || (!empty($arItem["PROPERTIES"]["BUTTON1TEXT"]["VALUE"]) && !empty($arItem["PROPERTIES"]["BUTTON1LINK"]["VALUE"]))):?>
														<div class="banner_buttons">
															<?if(trim($arItem["PROPERTIES"]["BUTTON1TEXT"]["VALUE"]) && trim($arItem["PROPERTIES"]["BUTTON1LINK"]["VALUE"])):?>
																<a href="<?=$arItem["PROPERTIES"]["BUTTON1LINK"]["VALUE"]?>" class="<?=!empty($arItem["PROPERTIES"]["BUTTON1CLASS"]["VALUE"]) ? $arItem["PROPERTIES"]["BUTTON1CLASS"]["VALUE"] : "button wide"?>" <?=(strlen($target) ? 'target="'.$target.'"' : '')?>>
																	<?=$arItem["PROPERTIES"]["BUTTON1TEXT"]["VALUE"]?>
																</a>
															<?endif;?>
															<?if(!empty($arItem["PROPERTIES"]["BUTTON2TEXT"]["VALUE"]) && !empty($arItem["PROPERTIES"]["BUTTON2LINK"]["VALUE"])):?>
																<a href="<?=$arItem["PROPERTIES"]["BUTTON2LINK"]["VALUE"]?>" class="<?=!empty( $arItem["PROPERTIES"]["BUTTON2CLASS"]["VALUE"]) ? $arItem["PROPERTIES"]["BUTTON2CLASS"]["VALUE"] : "button wide grey"?>" <?=(strlen($target) ? 'target="'.$target.'"' : '')?>>
																	<?=$arItem["PROPERTIES"]["BUTTON2TEXT"]["VALUE"]?>
																</a>
															<?endif;?>
														</div>
													<?endif;?>
												</td>
											<?$text = ob_get_clean();?>
										<?endif;?>
										<?ob_start();?>
											<td class="img" >
												<?if($arItem["PREVIEW_PICTURE"]):?>
													<?if(!empty($arItem["PROPERTIES"]["URL_STRING"]["VALUE"])):?>
														<a href="<?=$arItem["PROPERTIES"]["URL_STRING"]["VALUE"]?>" <?=(strlen($target) ? 'target="'.$target.'"' : '')?>>
													<?endif;?>
													<?if(!empty($arItem["PROPERTIES"]["URL_STRING"]["VALUE"])):?>
														</a>
													<?endif;?>
												<?endif;?>
											</td>
										<?$image = ob_get_clean();?>
										<?
										if($arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"]){
											if($arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"] == "left"){
												echo $text.$image;
											}
											elseif($arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"] == "right"){
												echo $image.$text;
											}
											elseif($arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"] == "center"){
												echo $text;
											}
											elseif($arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"] == "image"){
												echo $image;
											}
										}
										else{
											echo $text.$image;
										}
										?>
									</tr></tbody>
								</table>
							</div>
					</li>
				<?endforeach;?>
			</ul>
		</div>
	</div>
<?endif;?>

<?if($bBannerLight)
{
    $templateData["BANNER_LIGHT"] = true;
}?>