<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("ROBOTS", "noindex");
$APPLICATION->SetTitle("1с");
?><?$APPLICATION->IncludeComponent(
	"bitrix:catalog.import.1c",
	"",
	Array(
		"DETAIL_HEIGHT" => "300",
		"DETAIL_RESIZE" => "Y",
		"DETAIL_WIDTH" => "300",
		"ELEMENT_ACTION" => "A",
		"FILE_SIZE_LIMIT" => "204800",
		"FORCE_OFFERS" => "N",
		"GENERATE_PREVIEW" => "Y",
		"GROUP_PERMISSIONS" => array("1"),
		"IBLOCK_TYPE" => "catalog",
		"INTERVAL" => "30",
		"PREVIEW_HEIGHT" => "100",
		"PREVIEW_WIDTH" => "100",
		"SECTION_ACTION" => "A",
		"SITE_LIST" => array(),
		"SKIP_ROOT_SECTION" => "N",
		"SKIP_SOURCE_CHECK" => "N",
		"TRANSLIT_CHANGE_CASE" => "L",
		"TRANSLIT_DELETE_REPEAT_REPLACE" => "Y",
		"TRANSLIT_MAX_LEN" => "100",
		"TRANSLIT_ON_ADD" => "Y",
		"TRANSLIT_ON_UPDATE" => "Y",
		"TRANSLIT_REPLACE_OTHER" => "_",
		"TRANSLIT_REPLACE_SPACE" => "_",
		"USE_CRC" => "Y",
		"USE_IBLOCK_PICTURE_SETTINGS" => "N",
		"USE_IBLOCK_TYPE_ID" => "N",
		"USE_OFFERS" => "N",
		"USE_ZIP" => "Y"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>