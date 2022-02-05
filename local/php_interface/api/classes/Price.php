<?

class Price
{
	
	function __construct()
	{
		
	}

	public static function format($price)
	{
		$length = 0;
        if (intval($price) != $price) {
            $length = 1;
        }
        return number_format($price, $length, '.', ' ');
	}
}