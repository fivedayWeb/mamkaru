<?define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");?>
<?define('STOP_STATISTICS', true);
define('PUBLIC_AJAX_MODE', true);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
CModule::IncludeModule("aspro.mshop");
$form_id = isset($_REQUEST["form_id"]) ? $_REQUEST["form_id"] : 1;
CMShop::GetValidFormIDForSite($form_id);
?>
<?if($form_id == 'fast_view'):?>
	<?include('fast_view.php');?>
<?elseif($form_id == 'subscribe'):?>
	<?include('subscribe.php');?>
<?elseif(isset($_REQUEST['form-name']) && $_REQUEST['form-name'] == 'review-detail'):?>
	<?include('review.php');?>
<?elseif($form_id != 'one_click_buy'):?>
	<?
	$APPLICATION->IncludeComponent(
		"bitrix:form",
		"popup",
		Array(
			"AJAX_MODE" => "Y",
			"SEF_MODE" => "N",
			"WEB_FORM_ID" => $form_id,
			"START_PAGE" => "new",
			"SHOW_LIST_PAGE" => "N",
			"SHOW_EDIT_PAGE" => "N",
			"SHOW_VIEW_PAGE" => "N",
			"SUCCESS_URL" => "",
			"SHOW_ANSWER_VALUE" => "N",
			"SHOW_ADDITIONAL" => "N",
			"SHOW_STATUS" => "N",
			"EDIT_ADDITIONAL" => "N",
			"EDIT_STATUS" => "Y",
			"NOT_SHOW_FILTER" => "",
			"NOT_SHOW_TABLE" => "",
			"CHAIN_ITEM_TEXT" => "",
			"CHAIN_ITEM_LINK" => "",
			"IGNORE_CUSTOM_TEMPLATE" => "N",
			"USE_EXTENDED_ERRORS" => "Y",
			"CACHE_TYPE" => "N",
			"CACHE_TIME" => "3600",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N",
			"VARIABLE_ALIASES" => Array(
				"action" => "action"
			)
		)
	);?>
<?endif;?>