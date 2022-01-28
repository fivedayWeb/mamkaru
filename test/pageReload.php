<?
if (empty($_SERVER["DOCUMENT_ROOT"])) $_SERVER["DOCUMENT_ROOT"] = '/home/bitrix/www';
require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
global $USER;

$arNav = array(
	'nTopCount' => $_REQUEST['cnt'] ?: 10,
);

$isMore = false;

$res = CIBlockElement::GetList(
	[],
	[
		'IBLOCK_ID' => \Custom\Catalog::IBLOCK_ID,
	],
	false,
	$arNav,
	[
		'ID',
		'IBLOCK_ID',
		'NAME'
	]
);
while ($item = $res->Fetch()) {
	$isMore = true;
	// START
	
	// END
}

if ($isMore):?>
	<script>
		setTimeout(function(){
			location.reload();
		}, 1000);
	</script>
<?else:?>
	<h2>Конец</h2>
<?endif;?>