<?

class Brand
{
	const IBLOCK_ID = 10;

	public static function import()
	{
		$arBrands = array();
		$resBrand = CIBlockElement::GetList(
			array(),
			array(
				'IBLOCK_ID' => self::IBLOCK_ID,
			),
			false, 
			false,
			array(
				'ID',
				'NAME',
				'CODE',
			)
		);
		while ($arBrand = $resBrand->Fetch()) {
			$arBrands[$arBrand['ID']] = $arBrand['CODE'];
		}

		$arErrors = array();
		$element = new CIBlockElement;
		$countSuccess = 0;

		$resProp = CIBlockPropertyEnum::GetList(
			array(), 
			array(
				'IBLOCK_ID' => \Custom\Catalog::IBLOCK_ID,
				'PROPERTY_ID' => \Custom\Catalog::PROPERTY_MANUFACTURER_ID,
			)
		);
		while ($arProp = $resProp->Fetch()) {
			$CODE = CUtil::translit($arProp['VALUE'], 'ru');
			if (in_array($CODE, $arBrands)) {
				Log::add([$CODE, $arProp], 'brand/import', 'skip');
				continue;
			}

			$arFields = array(
				'IBLOCK_ID' => self::IBLOCK_ID,
				'NAME' => $arProp['VALUE'],
				'SORT' => $arProp['SORT'],
				'CODE' => $CODE,
				'PROPERTY_VALUES' => array(
					'PROPERTY_ID' => $arProp['ID'],
				),
			);
			$ID = $element->Add($arFields);
			if ($ID) {
				$arBrands[$ID] = $arFields['CODE'];
				$countSuccess++;
			} else {
				$arErrors[] = array(
					'error' => $element->LAST_ERROR,
					'arFields' => $arFields,
				);
			}
		}

		if ($countSuccess) {
			Log::add($countSuccess, 'brand/import', 'success');
		}
		if (!empty($arErrors)) {
			Log::add($arErrors, 'brand/import', 'error');
		}

		return __METHOD__ . '();';
	}
	
}