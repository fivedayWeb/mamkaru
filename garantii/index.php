<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Гарантии");
?><?$APPLICATION->IncludeComponent(
	"NGrishchenko:garantii",
	".default",
	Array(
		"AG_IMG" => "",
		"AG_INCLUDE_1" => "/include/garantii.php",
		"AG_INCLUDE_2" => "",
		"COMPONENT_TEMPLATE" => ".default"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>