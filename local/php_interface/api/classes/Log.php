<?

class Log
{
	const DIR = '/log';
	const DATE_FORMAT = 'Y_m_d';
	const TIME_FORMAT = 'H:i:s';

	public static function add($data, $path = '', $suffix = '')
	{
	    $pathname = $_SERVER['DOCUMENT_ROOT'] . self::DIR;
	    if (!empty($path))
	    {
	        $pathname .= DIRECTORY_SEPARATOR.$path;
	        if(!is_dir($pathname))
	        {
	            mkdir($pathname, 0777, true);
	        }
	    }

	    $filename = date(self::DATE_FORMAT);
	    if ($suffix)
	    {
	        $filename .= '_'.$suffix;
	    }

	    file_put_contents(
	        $pathname.DIRECTORY_SEPARATOR.$filename.'.log',
	        implode(
	        	PHP_EOL, 
	        	array(
	        		'',
	        		date(self::TIME_FORMAT),
	        		'REQUEST_URI: ' . $_SERVER['REQUEST_URI'],
	        		print_r($data, true),
	        		'',
	        	)
	        ),
	        FILE_APPEND
	    );
	}

	public static function clear($time = '30 day')
	{
		$timestamp = strtotime('-' . $time);
		foreach (FileSystem::getFiles($_SERVER['DOCUMENT_ROOT'].self::DIR) as $file) {
			$timestampModify = filemtime($file);
			if ($timestampModify < $timestamp) {
				if (!unlink($file)) {
					Log::add('unlink ' . $file, 'Log/error');
				}
			}
		}
		foreach (FileSystem::getDirectories($_SERVER['DOCUMENT_ROOT'].self::DIR) as $dir) {
			if (!is_dir($dir)) {
				continue;
			}

			if (empty(FileSystem::getFiles($dir))) {
				if (!FileSystem::deleteDir($dir)) {
					Log::add('deleteDir ' . $dir, 'Log/error');
				}
			}
		}
		return __METHOD__ . "('" . $time . "');";
	}
}