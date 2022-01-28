<?

class ConsoleTime
{
    public static $time = null;
    public static function saveTime()
    {
        self::$time = microtime(true);
    }

    public static function getDiff()
    {
        if (is_null(self::$time)) {
            self::saveTime();
        }

        $diff = microtime(true) - self::$time;

        self::saveTime();

        return $diff;
    }

    public static function show($file = '', $line = '', $param = '')
    {
        ob_start();

        $file = str_replace($_SERVER['DOCUMENT_ROOT'], '', $file);
        $str = $file . ':' . $line . ' - ' . self::getDiff();
        if (!empty($param)) {
            $str .= ' - '.$param;
        }

        ?><script>console.log(`<?=$str?>`)</script><?
        
        return ob_get_clean();
    }
}   