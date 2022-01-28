<? 
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
?>

<form method="GET" action="">
	<input type="text" name="q" value="<?=$_GET['q']?>" placeholder="Function name or Class name" size="30" autofocus>
	<input type="submit">
</form>

<?
try {
	$reflector = new ReflectionClass($_GET['q']);
	echo 'class: <br>';
	echo '<h3>'.$reflector->getFileName().':'.$reflector->getStartLine().'</h3><br>';
} catch (Exception $e) {
	$not_found_class = true;
}

try {
	$reflector = new ReflectionFunction($_GET['q']);
	echo 'function: <br>';
	echo '<h3>'.$reflector->getFileName().':'.$reflector->getStartLine().'</h3><br>';
} catch (Exception $e) {
	$not_found_function = true;
}

if(!empty($_GET['q']) && $not_found_class && $not_found_function) {
	echo 'not found';
}