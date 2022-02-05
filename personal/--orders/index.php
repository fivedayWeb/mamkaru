<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "История покупок");
$APPLICATION->SetPageProperty("description", "История покупок");
$APPLICATION->SetTitle("История покупок");
?>
<section id="content">
	<div class="left_block">
		<?$APPLICATION->IncludeComponent("bitrix:menu", "left_menu", array(
			"ROOT_MENU_TYPE" => "left",
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
	</div>
	<div class="right_block">
	<?$APPLICATION->IncludeComponent(
		"bitrix:sale.personal.order.list", 
		".default", 
		array(
			"PATH_TO_DETAIL" => "",
			"PATH_TO_CANCEL" => "",
			"PATH_TO_CATALOG" => $arParams["PATH_TO_CATALOG"],
			"PATH_TO_COPY" => "",
			"PATH_TO_BASKET" => "",
			"PATH_TO_PAYMENT" => "",
			"SAVE_IN_SESSION" => "N",
			"ORDERS_PER_PAGE" => "",
			"SET_TITLE" => "N",
			"ID" => "",
			"NAV_TEMPLATE" => "",
			"ACTIVE_DATE_FORMAT" => "j F Y",
			"HISTORIC_STATUSES" => array(
				0 => "F",
				1 => "N",
				2 => "OP",
				3 => "P",
			),
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "3600",
			"CACHE_GROUPS" => "N",
			"COMPONENT_TEMPLATE" => ".default",
			"STATUS_COLOR_F" => "gray",
			"STATUS_COLOR_N" => "green",
			"STATUS_COLOR_P" => "yellow",
			"STATUS_COLOR_PSEUDO_CANCELLED" => "red",
			"DISALLOW_CANCEL" => "N",
			"RESTRICT_CHANGE_PAYSYSTEM" => array(
				0 => "P",
			),
			"REFRESH_PRICES" => "N",
			"DEFAULT_SORT" => "STATUS",
			"ALLOW_INNER" => "N",
			"ONLY_INNER_FULL" => "N",
			"COMPOSITE_FRAME_MODE" => "A",
			"COMPOSITE_FRAME_TYPE" => "AUTO"
		),
		$component
	);?>
	</div>
</section>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>