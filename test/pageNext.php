<?
if (empty($_SERVER["DOCUMENT_ROOT"])) $_SERVER["DOCUMENT_ROOT"] = '/home/bitrix/www';
require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
global $USER;

$arNav = array(
	'nPageSize' => $_REQUEST['cnt'] ?: 10,
	'iNumPage' => $_REQUEST['n'] ?: 1,
	'checkOutOfRange' => true
);

$res = CIBlockElement::GetList(
	[],
	[
		'IBLOCK_ID' => [
			\Custom\Catalog::IBLOCK_ID,
			\Custom\Catalog::PRODUCT_IBLOCK_ID,
		],
	],
	false,
	$arNav,
	[
		'ID',
		'IBLOCK_ID',
		'NAME'
	]
);
$isMore = false;
while ($item = $res->Fetch()) {
	$isMore = true;
	// START
	
	// END
}

if ($isMore):
	$nextPage = '?n='.($arNav['iNumPage'] + 1).'&cnt='.$arNav['nPageSize'];
	?>
	<a href="<?=$nextPage?>">page <?=$arNav['iNumPage'] + 1?></a>
	<script>
		setTimeout(function(){
			location.href= '<?=$nextPage?>';
		}, 1000);
	</script>
<?else:?>
	<h2>Конец</h2>
<?endif;?>