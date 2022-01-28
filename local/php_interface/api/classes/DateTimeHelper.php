<?

class DateTimeHelper
{
	protected $timestamp = null;
	public function __construct($timestamp = null)
	{
		if (is_null($timestamp)) $timestamp = time();
		if (!is_numeric($timestamp)) throw new InvalidArgumentException("timestamp");
		$this->timestamp = $timestamp;
	}

	public function getTimestamp()
	{
		return $this->timestamp;
	}

	public static function getMonthsRus()
	{
		return array(
			'01' => array(
				'I' => 'январь',
				'R' => 'января',
			),
			'02' => array(
				'I' => 'февраль',
				'R' => 'февраля',
			),
			'03' => array(
				'I' => 'март',
				'R' => 'марта',
			),
			'04' => array(
				'I' => 'апрель',
				'R' => 'апреля',
			),
			'05' => array(
				'I' => 'май',
				'R' => 'мая',
			),
			'06' => array(
				'I' => 'июнь',
				'R' => 'июня',
			),
			'07' => array(
				'I' => 'июль',
				'R' => 'июля',
			),
			'08' => array(
				'I' => 'август',
				'R' => 'августа',
			),
			'09' => array(
				'I' => 'сентябрь',
				'R' => 'сентября',
			),
			'10' => array(
				'I' => 'октябрь',
				'R' => 'октября',
			),
			'11' => array(
				'I' => 'ноябрь',
				'R' => 'ноября',
			),
			'12' => array(
				'I' => 'декабрь',
				'R' => 'декабря',
			),
		);
	}

	public function getMonth()
	{
		return self::getMonthsRus()[date('m', $this->getTimestamp())];
	}

	public function format($format)
	{
		$arReplacement = array(
			'MONTH_RUS_I' => $this->getMonth()['I'],
			'MONTH_RUS_R' => $this->getMonth()['R'],
			'MONTH_RUS' => $this->getMonth()['I'],
		);
		$formatReplaced = str_replace(
			array_keys($arReplacement), 
			array_values($arReplacement), 
			$format
		);
		return date($formatReplaced, $this->getTimestamp());
	}
}