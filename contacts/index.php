<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
?>
<div class="contacts_map">
	<?$APPLICATION->IncludeComponent(
	"bitrix:map.yandex.view", 
	"map", 
	array(
		"INIT_MAP_TYPE" => "MAP",
		"MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:47.20898694643164;s:10:\"yandex_lon\";d:39.61238614713663;s:12:\"yandex_scale\";i:17;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:3:\"LON\";d:39.61274556314466;s:3:\"LAT\";d:47.20895952998276;s:4:\"TEXT\";s:35:\"ул. Малиновского, 9Б\";}}}",
		"MAP_WIDTH" => "100%",
		"MAP_HEIGHT" => "400",
		"CONTROLS" => array(
			0 => "SMALLZOOM",
		),
		"OPTIONS" => array(
			0 => "ENABLE_DBLCLICK_ZOOM",
			1 => "ENABLE_DRAGGING",
		),
		"MAP_ID" => "",
		"ZOOM_BLOCK" => array(
			"POSITION" => "right center",
		),
		"COMPONENT_TEMPLATE" => "map",
		"API_KEY" => ""
	),
	false
);?>
</div>
<div class="wrapper_inner">
	<div class="contacts_left clearfix">
		<div class="store_description">
			<div class="store_property">
				<div class="title">Адрес</div>
				<div class="value">
					<?$APPLICATION->IncludeFile(SITE_DIR."include/address.php", Array(), Array("MODE" => "html", "NAME" => "Адрес"));?>
				</div>
			</div>
			<div class="store_property">
				<div class="title">Телефон</div>
				<div class="value">
					<?$APPLICATION->IncludeFile(SITE_DIR."include/phone.php", Array(), Array("MODE" => "html", "NAME" => "Телефон"));?>
				</div>
			</div>
			<div class="store_property">
				<div class="title">Email</div>
				<div class="value">
					<?$APPLICATION->IncludeFile(SITE_DIR."include/email.php", Array(), Array("MODE" => "html", "NAME" => "Email"));?>
				</div>
			</div>
			<div class="store_property">
				<div class="title">Режим работы</div>
				<div class="value">
					<?$APPLICATION->IncludeFile(SITE_DIR."include/schedule.php", Array(), Array("MODE" => "html", "NAME" => "Время работы"));?>
				</div>
			</div>
			<div class="store_property">
				<div class="title">Социальные сети</div>
				<div class="value">
					<?$APPLICATION->IncludeFile(SITE_DIR."include/socseti.php", Array(), Array("MODE" => "html", "NAME" => "Социальные сети"));?>
				</div>
			</div>
		</div>
	</div>
	<div class="contacts_right clearfix">
		<blockquote><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts_text.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("CONTACTS_TEXT")));?></blockquote>
		<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("form-feedback-block");?>
		<?$APPLICATION->IncludeComponent("bitrix:form.result.new", "inline",
			Array(
				"WEB_FORM_ID" => "4",
				"IGNORE_CUSTOM_TEMPLATE" => "N",
				"USE_EXTENDED_ERRORS" => "Y",
				"SEF_MODE" => "N",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "3600000",
				"LIST_URL" => "",
				"EDIT_URL" => "",
				"SUCCESS_URL" => "?send=ok",
				"CHAIN_ITEM_TEXT" => "",
				"CHAIN_ITEM_LINK" => "",
				"VARIABLE_ALIASES" => Array(
					"WEB_FORM_ID" => "WEB_FORM_ID",
					"RESULT_ID" => "RESULT_ID"
				)
			)
		);?>
		<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("form-feedback-block", "");?>
	</div>
</div>
<div class="clearboth"></div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>