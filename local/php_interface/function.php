<?php function p($sVar){ ?>
	<pre><? print_r($sVar); ?></pre>
<? } ?>
<?php
function clearURL(){
	$tmp = explode('?',$_SERVER['REQUEST_URI']);
	return $tmp[0];
}

function utf8ize($d) { if (is_array($d)) { foreach ($d as $k => $v) { $d[$k] = utf8ize($v); } } else if (is_string ($d)) { return utf8_encode($d); } return $d; } 
function console_log($data, $context = 'Debug in Console'){ // сама функция
	ob_start();
	
    $output  = 'console.info( \'' . $context . ':\' );';
	if(is_array($data) || is_object($data)){
		$output .= 'console.log(' . json_encode(print_r($data, true)) . ');';
	} else {
		$output .= 'console.log('.$data.');';
	}	
    $output  = sprintf( '<script>%s</script>', $output );

    echo $output;
}