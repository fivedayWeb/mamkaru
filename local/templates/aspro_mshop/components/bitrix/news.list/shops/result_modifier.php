<?$this->__component->arResultCacheKeys = array_merge($this->__component->arResultCacheKeys, array('ID', 'IBLOCK_SECTION_ID', 'ITEMS'));?>

<?
if($arResult['ITEMS'])
{
	
	if($arParams["NO_GROUPS_STORE"] != "Y"){

		$arAllSections = CMshop::GetSections($arResult['ITEMS'], $arParams);

		if(isset($arAllSections['ALL_SECTIONS']) && $arAllSections['ALL_SECTIONS'])
		{
			foreach($arAllSections['ALL_SECTIONS'] as $key => $arSection)
			{
				$bHasChild = (isset($arSection['CHILD_IDS']) && $arSection['CHILD_IDS']); // has child sections
				foreach($arResult['ITEMS'] as $arItem)
				{
					$SID = ($arItem['IBLOCK_SECTION_ID'] ? $arItem['IBLOCK_SECTION_ID'] : 0);
					if($bHasChild)
					{
						if($arSection['CHILD_IDS'][$SID])
							$arAllSections['ALL_SECTIONS'][$key]['ITEMS'][$arItem['ID']] = $arItem;
					}
					elseif($arAllSections['ALL_SECTIONS'][$SID])
					{
						$arAllSections['ALL_SECTIONS'][$SID]['ITEMS'][$arItem['ID']] = $arItem;
					}
					else{
						$arAllSections['ALL_SECTIONS'][0]['ITEMS'][$arItem['ID']] = $arItem;
					}
				}
			}
			if(isset($arAllSections['ALL_SECTIONS'][0]) && count($arAllSections['ALL_SECTIONS'][0])>0){
				$arAllSections['ALL_SECTIONS'][0]['SECTION']["NAME"] = GetMessage("SECTION_OTHERS");
				$arAllSections['ALL_SECTIONS'][0]["SORT"] = 10000;
				$arAllSections['ALL_SECTIONS'][0]["ID"] = 999999999;
			}
			$arResult['SECTIONS'] = $arAllSections['ALL_SECTIONS'];
		}
		// else
		// {
		// 	$arResult['SECTIONS'][0]['ITEMS'] = $arResult['ITEMS'];
		// }
	}

}
?>