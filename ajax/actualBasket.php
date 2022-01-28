<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
if(!CModule::IncludeModule('sale') || !CModule::IncludeModule('aspro.mshop')){
	echo 'failure';
	die();
}

$arBasketAspro = CMShop::getBasketAspro();

// get compare from session
if(isset($_REQUEST['iblockID'])){
	$IBLOCK_ID = htmlspecialcharsbx($_REQUEST['iblockID']);
	$arBasketAspro['COMPARE'] = array_keys($_SESSION['CATALOG_COMPARE_LIST'][$IBLOCK_ID]['ITEMS']);
	if($arBasketAspro['COMPARE'])
	{
		$arBasketAspro['COMPARE'] = array_combine($arBasketAspro['COMPARE'], $arBasketAspro['COMPARE']);
	}
}
?>
<script type="text/javascript">
	var arBasketAspro = <?=CUtil::PhpToJSObject($arBasketAspro, false, true);?>;
</script>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>