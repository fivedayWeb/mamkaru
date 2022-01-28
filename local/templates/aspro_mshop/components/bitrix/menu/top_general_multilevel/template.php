<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if($arResult):?>
	<ul class="menu adaptive">
		<li class="menu_opener">
			<a href="javascript:void(0)"><?=GetMessage('MENU_NAME')?></a>
			<i class="icon"></i>
			<div class="search_wrapper"><i class="svg inline  svg-inline-search-top" aria-hidden="true"><svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M13.6989 13.6989C13.5966 13.802 13.475 13.8838 13.3409 13.9397C13.2069 13.9955 13.0631 14.0243 12.9179 14.0243C12.7727 14.0243 12.6289 13.9955 12.4949 13.9397C12.3608 13.8838 12.2392 13.802 12.1369 13.6989L9.4029 10.9649C8.16747 11.811 6.66059 12.1653 5.17756 11.9583C3.69452 11.7514 2.34223 10.998 1.38567 9.84599C0.42911 8.69394 -0.0627673 7.22621 0.00642194 5.7304C0.0756111 4.23459 0.700884 2.81853 1.75971 1.75971C2.81854 0.700881 4.23459 0.0756111 5.7304 0.00642192C7.2262 -0.0627673 8.69394 0.429112 9.84599 1.38567C10.998 2.34223 11.7514 3.69453 11.9583 5.17756C12.1653 6.66059 11.811 8.16746 10.9649 9.4029L13.6989 12.1369C13.802 12.2392 13.8838 12.3608 13.9397 12.4949C13.9955 12.6289 14.0243 12.7727 14.0243 12.9179C14.0243 13.0631 13.9955 13.2069 13.9397 13.3409C13.8838 13.475 13.802 13.5966 13.6989 13.6989ZM6.0159 2.0159C5.22477 2.0159 4.45141 2.25049 3.79362 2.69002C3.13582 3.12954 2.62313 3.75426 2.32038 4.48516C2.01763 5.21607 1.93842 6.02033 2.09276 6.79626C2.2471 7.57218 2.62806 8.28491 3.18747 8.84432C3.74688 9.40373 4.45961 9.7847 5.23553 9.93904C6.01146 10.0934 6.81572 10.0142 7.54663 9.71142C8.27753 9.40866 8.90225 8.89597 9.34178 8.23818C9.78131 7.58038 10.0159 6.80702 10.0159 6.0159C10.0159 4.95503 9.59447 3.93761 8.84433 3.18747C8.09418 2.43732 7.07676 2.0159 6.0159 2.0159Z" fill="white"></path></svg></i></div>
		</li>
	</ul>
	<ul class="menu full">
		<?
		$arTmpParams = explode(',', $arParams["IBLOCK_CATALOG_ID"]);
		$iblockID = $arTmpParams[0];?>
		<?foreach($arResult as $arItem):?>
			<li class="menu_item_l1 <?=($arItem["SELECTED"] ? ' current' : '')?><?=($arItem["LINK"] == $arParams["IBLOCK_CATALOG_DIR"] ? ' catalog' : '')?>">
				<a href="<?=$arItem["LINK"]?>">
					<span><?=$arItem["TEXT"]?></span>
				</a>
				<?if($arItem["IS_PARENT"] == 1):?>
					<div class="child submenu line">
						<div class="child_wrapp">
							<?foreach($arItem["CHILD"] as $arSubItem):?>
								<a class="<?=($arSubItem["SELECTED"] ? ' current' : '')?>" href="<?=$arSubItem["LINK"]?>"><?=$arSubItem["TEXT"]?></a>
							<?endforeach;?>
						</div>
					</div>
				<?endif;?>
				<?global $HIDE_CATALOG_MULTILEVEL, $TEMPLATE_OPTIONS;
				$showSubCatalog = ($HIDE_CATALOG_MULTILEVEL || $arParams['FIXED'] == 'Y');
				?>				
				<?if($arItem["LINK"] == $arParams["IBLOCK_CATALOG_DIR"] && $showSubCatalog):?>
					<?$APPLICATION->IncludeComponent(
						"bitrix:catalog.section.list",
						"top_menu",
						Array(
							"IBLOCK_TYPE" => $arParams["IBLOCK_CATALOG_TYPE"],
							"IBLOCK_ID" => $iblockID,
							"SECTION_ID" => "",
							"SECTION_CODE" => "",
							"COUNT_ELEMENTS" => "N",
							"TOP_DEPTH" => "2",
							"SECTION_FIELDS" => array(0 => "",1 => "",),
							"SECTION_USER_FIELDS" => array(0 => "",1 => "",),
							"SECTION_URL" => "",
							"CACHE_TYPE" => "A",
							"CACHE_TIME" => "86400",
							"URL" => $_SERVER["REQUEST_URI"],
							"CACHE_GROUPS" => "N",
							"ADD_SECTIONS_CHAIN" => "N",
							"SHOW_SECTION_ICONS" => $TEMPLATE_OPTIONS['SHOW_CATALOG_SECTIONS_ICONS']['CURRENT_VALUE'],
						)
					);?>
				<?endif;?>
			</li>
		<?endforeach;?>
		<li class="stretch"></li>
		<?$fixed = (isset($arParams['FIXED']) && $arParams['FIXED'] == 'Y' ? '_fixed' : '');?>
		<li class="search_row">
			<?$APPLICATION->IncludeComponent("bitrix:search.form", "top", array(
				"PAGE" => $arParams["IBLOCK_CATALOG_DIR"],
				"USE_SUGGEST" => "N",
				"USE_SEARCH_TITLE" => "Y",
				"INPUT_ID" => "title-search-input4".$fixed,
				"CONTAINER_ID" => "title-search4"
				), false
			);?>
		</li>
	</ul>
	<?global $TEMPLATE_OPTIONS;?>
	<div class="search_middle_block">
		<?$APPLICATION->IncludeComponent("bitrix:search.title", ( strToLower($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"])=="fly" ? "catalog" : "mshop"), array(
			"NUM_CATEGORIES" => "1",
			"TOP_COUNT" => "5",
			"ORDER" => "date",
			"USE_LANGUAGE_GUESS" => "Y",
			"CHECK_DATES" => "Y",
			"SHOW_OTHERS" => "Y",
			"PAGE" => $arParams["IBLOCK_CATALOG_DIR"],
			"CATEGORY_0_TITLE" => GetMessage("CATEGORY_PRODU�TCS_SEARCH_NAME"),
			"CATEGORY_0" => array(
				0 => 'iblock_'.$arParams["IBLOCK_CATALOG_TYPE"],
			),
			"CATEGORY_0_iblock_".$arParams["IBLOCK_CATALOG_TYPE"] => array(
				0 => (count($arTmpParams) > 1 ? $arTmpParams[1] : $arParams["IBLOCK_CATALOG_ID"]),
			),
			"SHOW_INPUT" => "Y",
			"INPUT_ID" => "title-search-input2".$fixed,
			"CONTAINER_ID" => "title-search2",
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"PRICE_VAT_INCLUDE" => "Y",
			"SHOW_ANOUNCE" => "N",
			"PREVIEW_TRUNCATE_LEN" => "50",
			"SHOW_PREVIEW" => "Y",
			"PREVIEW_WIDTH" => "38",
			"PREVIEW_HEIGHT" => "38",
			"CONVERT_CURRENCY" => "N",
			),

			false,
			array(
			"HIDE_ICONS" => "Y"
			)
		);?>
	</div>
	<div class="search_block">
		<span class="icon"></span>
	</div>
	<script type="text/javascript">
	$(document).ready(function() {
		
		
		$(".main-nav .menu > li:not(.current):not(.menu_opener) > a").click(function(){
			$(this).parents("li").siblings().removeClass("current");
			$(this).parents("li").addClass("current");
		});
		
		$(".main-nav .menu .child_wrapp a").click(function(){
			$(this).siblings().removeClass("current");
			$(this).addClass("current");
		});
	});
	</script>
<?endif;?>