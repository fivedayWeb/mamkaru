<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
$this->setFrameMode(true);
include('functions.php');
$useVoteRating = ('Y' == $arParams['USE_VOTE_RATING']);
?>
<div class="center clear">
	<h1 id="product-header"><?=$arResult['NAME']?></h1>
	<?include('blocks/photos.php')?>
	<div id="product-content">
		<form>
			<div id="product-info">
				<div class="flex">
					<?if($useVoteRating):
						$APPLICATION->IncludeComponent(
							"bitrix:iblock.vote",
							"catalog.stars",
							array(
								"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
								"IBLOCK_ID" => $arParams['IBLOCK_ID'],
								"ELEMENT_ID" => $arResult['ID'],
								"ELEMENT_CODE" => "",
								"MAX_VOTE" => "5",
								"VOTE_NAMES" => array("1", "2", "3", "4", "5"),
								"SET_STATUS_404" => "N",
								"DISPLAY_AS_RATING" => $arParams['VOTE_DISPLAY_AS_RATING'],
								"CACHE_TYPE" => $arParams['CACHE_TYPE'],
								"CACHE_TIME" => $arParams['CACHE_TIME']
							),
							$component,
							array("HIDE_ICONS" => "Y")
						);?>
					<?endif;?>
					
					<script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
					<script src="//yastatic.net/share2/share.js"></script>
					<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,gplus,viber,whatsapp,telegram" data-limit="3"></div>
				</div>

				<?if (!empty($arResult['PROPERTIES']['SMAL_DETAIL_TEXT']['~VALUE']['TEXT'])):?>
					<p><?=$arResult['PROPERTIES']['SMAL_DETAIL_TEXT']['~VALUE']['TEXT']?></p>
				<?endif;?>
				<?include "blocks/price.php";?>

			</div>
		</form>
		<?include "blocks/akciya.php";?>
	</div>
</div>
<div class="center">
	<div id="product-tabs" class="tabs">
		<div id="product-tabs-buttons" class="tabs-buttons">
			<div class="product-tab-button tab-button active">Подробнее</div>
            <? if (!empty($arResult['PROPERTIES']['YOUTUBE_LINK']['VALUE'])): ?>
                <div class="product-tab-button tab-button data-youtube-button">Видео обзор товара</div>
            <? endif; ?>
		</div>
		<div id="product-tabs-contents" class="tabs-contents">
			<?include('blocks/bottom_prop.php')?>
		</div>
	</div>
</div>

<?include('blocks/bottom_page.php')?>

<?if(!empty($arResult['PHOTOS'])):?>
	<div class="modal_window _modal_window" style="display: none;">
		<div class="modal_bg flex _modal_bg">
			<div class="modal_content">
				<div class="modal_close _modal_close">&times;</div>
				<div class="modal_body flex">
					<div class="modal-images-container">
						<?foreach ($arResult['PHOTOS'] as $i => $arPhoto):?>
							<div class="slider-item flex">
								<img src="<?=$arPhoto['small']?>" 
									class="owl-lazy"
									data-src="<?=$arPhoto['detail']?>"
									<?if ($arPhoto['type'] == 'offer'):?>
										data-offer="<?=$arPhoto['offerId']?>"
									<?endif?>
									alt="<?=$arResult['NAME']?>"
									data-index="<?=$i?>"
									/>
							</div>
						<?endforeach?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?endif;?>

<script type="text/javascript">
	$(function(){
		jsElement = new JCCatalogElement(<?=json_encode($arResult['JS_PARAMS'])?>);
	});
</script>