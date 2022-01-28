<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("О магазине");
?><?$APPLICATION->IncludeComponent(
	"NGrishchenko:about",
	"",
	Array(
		"AG_IMG" => "/local/templates/mamka/i/delivery.png",
		"AG_INCLUDE_1" => "/include/logistics.php",
		"AG_INCLUDE_2" => "/include/ag-address.php"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>