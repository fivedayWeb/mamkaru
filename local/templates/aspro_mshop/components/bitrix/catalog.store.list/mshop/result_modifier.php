<?if ($arResult["STORES"]) {
	CModule::IncludeModule('catalog');
	$arStoresIDs = $arStoresByID = array();

	foreach($arResult["STORES"] as $key => $arStore){
		$arResult["STORES"][$key]['SCHEDULE'] = htmlspecialchars_decode($arStore['SCHEDULE']);
		$arResult["STORES"][$key]['KEY'] = $key;		
		$arStoresByID[$arStore['ID']] = &$arResult["STORES"][$key];
		$arStoresIDs[] = $arStore['ID'];
	}

	if($arStoresIDs){
		$dbRes = CCatalogStore::GetList(array('ID' => 'ASC'), array('ID' => $arStoresIDs, 'ACTIVE' => 'Y'), false, false, array("ID", "SORT", "TITLE", "UF_PHONES"));
		while($arStore = $dbRes->GetNext()){
			$arStoresByID[$arStore['ID']]["UF_PHONES"] = unserialize($arStore["~UF_PHONES"]);
		}
	}
}