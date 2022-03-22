<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $arResult
 * @var SaleOrderAjax $component
 */

$component = $this->__component;
$component::scaleImages($arResult['JS_DATA'], $arParams['SERVICES_IMAGES_SCALING']);

if (CUser::IsAuthorized()) {
	$arUser = CUser::GetByID(CUser::GetID())->Fetch();
	$arUser['FULL_NAME'] = implode(
		' ', 
		array_filter(
			[
				$arUser['LAST_NAME'], 
				$arUser['NAME'], 
				$arUser['SECOND_NAME']
			]
		)
	);

	// ORDER_PROP - USER_PROP
	$arMap = [
		'FIO' => 'FULL_NAME',
		'EMAIL' => 'EMAIL',
		'PHONE' => 'PERSONAL_PHONE',
	];
	foreach ($arResult['JS_DATA']['ORDER_PROP']['properties'] as &$arProp) {
		$value = $arUser[$arMap[$arProp['CODE']]];
		if (!empty($value)) {
			$arProp['VALUE'][0] = $value;
		}
	}
	unset($arProp);
}

$currencyDefault = ' руб.';
$currency = substr($arResult['JS_DATA']['TOTAL']['ORDER_PRICE_FORMATED'], -1 * strlen($currencyDefault));
if ($currency == $currencyDefault) {
	$arResult['JS_DATA']['TOTAL']['ORDER_PRICE_FORMATED'] = Price::format($arResult['JS_DATA']['TOTAL']['ORDER_PRICE']) . $currency;
	$arResult['JS_DATA']['TOTAL']['ORDER_TOTAL_PRICE_FORMATED'] = Price::format($arResult['JS_DATA']['TOTAL']['ORDER_TOTAL_PRICE']) . $currency;
	$arResult['JS_DATA']['TOTAL']['DISCOUNT_PRICE_FORMATED'] = Price::format($arResult['JS_DATA']['TOTAL']['DISCOUNT_PRICE']) . $currency;

	foreach ($arResult['JS_DATA']['GRID']['ROWS'] as &$item) {
		$item['data']['PRICE_FORMATED'] = Price::format($item['data']['PRICE']) . $currency;
		$item['data']['BASE_PRICE_FORMATED'] = Price::format($item['data']['BASE_PRICE']) . $currency;

		$item['data']['SUM'] = Price::format($item['data']['SUM_NUM']) . $currency;
		$item['data']['SUM_BASE_FORMATED'] = Price::format($item['data']['SUM_BASE']) . $currency;
	}
	unset($item);

	foreach ($arResult['BASKET_ITEMS'] as $item) {
		$item['PRICE_FORMATED'] = Price::format($item['PRICE']) . $currency;
		$item['BASE_PRICE_FORMATED'] = Price::format($item['BASE_PRICE']) . $currency;

		$item['SUM'] = Price::format($item['SUM_NUM']) . $currency;
		$item['SUM_BASE_FORMATED'] = Price::format($item['SUM_BASE']) . $currency;

	}
	unset($item);
}

// pre($arResult);