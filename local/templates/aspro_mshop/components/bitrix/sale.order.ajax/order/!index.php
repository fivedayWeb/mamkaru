<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оформление заказа");
?><?$APPLICATION->IncludeComponent("bitrix:sale.order.ajax", "shop_visual", array(
	"PAY_FROM_ACCOUNT" => "N",
	"COUNT_DELIVERY_TAX" => "Y",
	"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
	"ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
	"ALLOW_AUTO_REGISTER" => "N",
	"SEND_NEW_USER_NOTIFY" => "N",
	"DELIVERY_NO_AJAX" => "Y",
	"DELIVERY_NO_SESSION" => "N",
	"PROP_1" => array(
	),
	"PATH_TO_BASKET" => "/newsite/basket/",
	"PATH_TO_PERSONAL" => "/newsite/personal/",
	"PATH_TO_PAYMENT" => "payment.php",
	"PATH_TO_AUTH" => "/newsite/auth/",
	"SET_TITLE" => "Y"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>