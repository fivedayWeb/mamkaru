<?

class FileSystem
{
	public static function getFiles($dir)
	{
		$arFiles = array();
		foreach (glob($dir . DIRECTORY_SEPARATOR . '*') as $path) {
			if (is_dir($path)) {
				$arFiles = array_merge($arFiles, self::getFiles($path));
			} else {
				$arFiles[] = $path;
			}
		}
		return $arFiles;
	}

	public static function getDirectories($dir)
	{
		$arDirectories = array();
		foreach (glob($dir . DIRECTORY_SEPARATOR . '*') as $path) {
			if (is_dir($path)) {
				$arDirectories[] = $path;
				$arDirectories = array_merge($arDirectories, self::getDirectories($path));
			}
		}
		return $arDirectories;
	}

	public static function isEmptyDir($dir)
	{
		return empty(glob($dir . DIRECTORY_SEPARATOR . '*'));
	}

	public static function deleteDir($dir)
	{
		foreach (self::getFiles($dir) as $file) {
			if (!unlink($file)) {
				Log::add('unlink ' . $file, 'FileSystem/error');
				return false;
			}
		}
		while (!self::isEmptyDir($dir)) {
			foreach (self::getDirectories($dir) as $directory) {
				if (self::isEmptyDir($directory)) {
					if (!rmdir($directory)) {
						Log::add('rmdir ' . $directory, 'FileSystem/error');
						return false;
					}
				}
			}
		}
		if (!rmdir($dir)) {
			Log::add('rmdir ' . $dir, 'FileSystem/error');
			return false;
		}
		return true;
	}
}