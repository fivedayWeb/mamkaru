<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$this->setFrameMode(true);?>
<?if(isset($_POST["AJAX_REQUEST_INSTAGRAM"]) && $_POST["AJAX_REQUEST_INSTAGRAM"] == "Y"):
	$inst=new CInstargramMshop($arParams["TOKEN"], \Bitrix\Main\Config\Option::get("aspro.mshop", "INSTAGRAMM_ITEMS_COUNT", 8));
	$arInstagramPosts=$inst->getInstagramPosts();
	//$arInstagramUser=$inst->getInstagramUser();
	?>
	<?if($arInstagramPosts["error"]["message"] && $USER->IsAdmin()):?>
        <br>
        <div class="alert alert-danger">
            <strong>Error: </strong><?=$arInstagramPosts["error"]["message"]?>
        </div>
    <?else:?>
		<?if($arInstagramPosts && !$arInstagramPosts["error"]["message"]):?>
			<?$userName = $arInstagramPosts['data'][0]['username'];?>
			<?$obParser = new CTextParser;
			$text_length = \Bitrix\Main\Config\Option::get("aspro.mshop", "INSTAGRAMM_TEXT_LENGTH", 400);?>
			<?$index = 0;?>
			<?$countItems = $arParams['INSTAGRAMM_ITEMS_VISIBLE'];?>
			<div class="item-views front blocks count_<?=$countItems;?>">
				<div class="top_block">
					<h3 class="title_block"><?=($arParams["TITLE"] ? $arParams["TITLE"] : GetMessage("TITLE"));?></h3>
					<a href="https://www.instagram.com/<?=$userName?>/" target="_blank"><?=GetMessage('INSTAGRAM_ALL_ITEMS');?></a>
				</div>
				<div class="instagram clearfix">
					<div class="items clearfix">
						<?foreach ($arInstagramPosts['data'] as $arItem):?>
							<?++$index;?>
							<?
							if($index > $arParams['INSTAGRAMM_ITEMS_VISIBLE']){
								break;
							}
							$arItem['LINK'] = $arItem['thumbnail_url'] ? $arItem['thumbnail_url'] : $arItem['media_url'];
							?>
							<div class="item">
								<div class="image" style="background:url(<?=$arItem['LINK'];?>) center center/cover no-repeat;"></div>
								<div class="title">
									<div class="date font_xs font_upper">
										<span><?=FormatDate('d F', strtotime($arItem['timestamp']), 'SHORT');?></span>
									</div>
									<div class="text"><?=($obParser->html_cut($arItem['caption'], $text_length));?></div>
									<a href="<?=$arItem['permalink']?>" target="_blank"></a>
								</div>
							</div>
						<?endforeach;?>
					</div>
				</div>
			</div>
		<?endif;?>
	<?endif;?>
<?else:?>
	<div class="instagram_wrapper wide_<?=\Bitrix\Main\Config\Option::get("aspro.mshop", "INSTAGRAMM_WIDE_BLOCK", "N");?>">
		<div class="maxwidth-theme">
			<div class="instagram_ajax loader_circle"></div>
		</div>
	</div>
<?endif;?>
<script>
$(document).ready(function(){
	$('.instagram_ajax .instagram .item .title').mCustomScrollbar();
});
</script>