<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");?>

<section id="content">
	<div class="center clear">
		<?$APPLICATION->IncludeComponent(
			"bitrix:menu",
			"personal",
			array(
				"COMPONENT_TEMPLATE" => "personal",
				"ROOT_MENU_TYPE" => "personal",
				"MENU_CACHE_TYPE" => "A",
				"MENU_CACHE_TIME" => "3600",
				"MENU_CACHE_USE_GROUPS" => "Y",
				"MENU_CACHE_GET_VARS" => array(
				),
				"MAX_LEVEL" => "1",
				"CHILD_MENU_TYPE" => "left",
				"USE_EXT" => "N",
				"DELAY" => "N",
				"ALLOW_MULTI_SELECT" => "N"
			),
			false
		);?>

		<?$APPLICATION->IncludeComponent(
	"bitrix:sale.basket.basket", 
	".default", 
	array(
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
		"COLUMNS_LIST" => array(
			0 => "NAME",
			1 => "DISCOUNT",
			2 => "WEIGHT",
			3 => "PROPS",
			4 => "DELETE",
			5 => "DELAY",
			6 => "TYPE",
			7 => "PRICE",
			8 => "QUANTITY",
			9 => "SUM",
			10 => "PROPERTY_TITLE",
			11 => "PROPERTY_KEYWORDS",
			12 => "PROPERTY_META_DESCRIPTION",
			13 => "PROPERTY_BRAND_REF",
			14 => "PROPERTY_NEWPRODUCT",
			15 => "PROPERTY_SALELEADER",
			16 => "PROPERTY_SPECIALOFFER",
			17 => "PROPERTY_ARTNUMBER",
			18 => "PROPERTY_MANUFACTURER",
			19 => "PROPERTY_MATERIAL",
			20 => "PROPERTY_COLOR",
			21 => "PROPERTY_MORE_PHOTO",
			22 => "PROPERTY_RECOMMEND",
			23 => "PROPERTY_FORUM_TOPIC_ID",
			24 => "PROPERTY_FORUM_MESSAGE_CNT",
			25 => "PROPERTY_BLOG_POST_ID",
			26 => "PROPERTY_BLOG_COMMENTS_CNT",
			27 => "PROPERTY_BACKGROUND_IMAGE",
			28 => "PROPERTY_MINIMUM_PRICE",
			29 => "PROPERTY_MAXIMUM_PRICE",
			30 => "PROPERTY_COLOR_REF",
			31 => "PROPERTY_SIZES_SHOES",
			32 => "PROPERTY_SIZES_CLOTHES",
		),
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"PATH_TO_ORDER" => "/personal/order/make/",
		"HIDE_COUPON" => "N",
		"QUANTITY_FLOAT" => "N",
		"PRICE_VAT_SHOW_VALUE" => "Y",
		"TEMPLATE_THEME" => "site",
		"SET_TITLE" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",
		"OFFERS_PROPS" => array(
		),
		"COMPONENT_TEMPLATE" => ".default",
		"USE_PREPAYMENT" => "N",
		"AUTO_CALCULATION" => "Y",
		"ACTION_VARIABLE" => "basketAction",
		"USE_GIFTS" => "N",
		"CORRECT_RATIO" => "N",
		"COLUMNS_LIST_EXT" => array(
			0 => "PREVIEW_PICTURE",
			1 => "DISCOUNT",
			2 => "DELETE",
			3 => "DELAY",
			4 => "TYPE",
			5 => "SUM",
		),
		"COMPATIBLE_MODE" => "Y",
		"ADDITIONAL_PICT_PROP_571" => "-",
		"ADDITIONAL_PICT_PROP_572" => "-",
		"BASKET_IMAGES_SCALING" => "adaptive"
	),
	false
);?>
	</div>
</section>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>