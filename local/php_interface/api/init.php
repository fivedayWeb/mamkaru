<?
function require_once_rec($dir)
{
	foreach (glob($dir."/*") as $path)
	{
		if (preg_match('/\.php$/', $path))
		{
			require_once($path); 
		}
		elseif (is_dir($path))
		{
			require_once_rec($path);
		}
	}
}

require_once_rec(__DIR__."/classes");
require_once_rec(__DIR__."/include");
require_once("handlers.php");
