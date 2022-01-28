<?if($arResult["STORES"]):?>

	<?
	CModule::IncludeModule('catalog');
	$arStoresIDs = $arStoresByID = array();

	foreach($arResult["STORES"] as $key => $arStore){
		$arResult["STORES"][$key]['SCHEDULE'] = htmlspecialchars_decode($arStore['SCHEDULE']);
		$arResult["STORES"][$key]['KEY'] = $key;		
		$arStoresByID[$arStore['ID']] = &$arResult["STORES"][$key];
		$arStoresIDs[] = $arStore['ID'];
	}

	if($arStoresIDs){
		$dbRes = CCatalogStore::GetList(array('ID' => 'ASC'), array('ID' => $arStoresIDs, 'ACTIVE' => 'Y'), false, false, array("ID", "SORT", "TITLE", "IMAGE_ID", "ADDRESS", "EMAIL", "DESCRIPTION", "UF_METRO", "UF_PHONES", "UF_STORE_GROUP"));
		while($arStore = $dbRes->GetNext()){			
			if(isset($arParams["SECTION_FILTER"]) && strlen($arParams["SECTION_FILTER"]) && $arStore["UF_STORE_GROUP"]!=$arParams["SECTION_FILTER"]){
				unset($arResult["STORES"][$arStoresByID[$arStore['ID']]["KEY"]]);			
				continue;
			}
			$arStoresByID[$arStore['ID']]["SORT"] = $arStore["SORT"];
			$arStoresByID[$arStore['ID']]["IMAGE"] = CFile::ResizeImageGet($arStore["IMAGE_ID"], array('width' => 100, 'height' => 69), BX_RESIZE_IMAGE_PROPORTIONAL);
			$arStoresByID[$arStore['ID']]["TITLE"] = htmlspecialchars_decode($arStore["TITLE"]);
			$arStoresByID[$arStore['ID']]["ADDRESS"] = htmlspecialchars_decode($arStore["ADDRESS"]);
			$arStoresByID[$arStore['ID']]["ADDRESS"] = $arStoresByID[$arStore['ID']]["TITLE"].((strlen($arStoresByID[$arStore['ID']]["TITLE"]) && strlen($arStoresByID[$arStore['ID']]["ADDRESS"])) ? ', ' : '').$arStoresByID[$arStore['ID']]["ADDRESS"];
			$arStoresByID[$arStore['ID']]["EMAIL"] = htmlspecialchars_decode($arStore["EMAIL"]);
			$arStoresByID[$arStore['ID']]["DESCRIPTION"] = htmlspecialchars_decode($arStore['DESCRIPTION']);
			$arStoresByID[$arStore['ID']]["METRO_PLACEMARK_HTML"] = '';
			if($arStoresByID[$arStore['ID']]["METRO"] = unserialize($arStore['~UF_METRO'])){
				foreach($arStoresByID[$arStore['ID']]['METRO'] as $metro){
					$arStoresByID[$arStore['ID']]["METRO_PLACEMARK_HTML"] .= '<div class="metro"><i></i>'.$metro.'</div>';
				}
			}
			$arStoresByID[$arStore['ID']]["UF_STORE_GROUP"] = $arStore["UF_STORE_GROUP"];
			$arStoresByID[$arStore['ID']]["UF_PHONES"] = unserialize($arStore["~UF_PHONES"]);
		}
	}

	if(!function_exists('_sort_stores_by_SORT')){
		function _sort_stores_by_SORT($a, $b){
			return ($a['SORT'] == $b['SORT'] ? ($a['KEY'] < $b['KEY'] ? -1 : 1) : ($a['SORT'] > $b['SORT'] ? 1 : -1));
		}
	}

	usort($arResult["STORES"], '_sort_stores_by_SORT');
	?>

	<?if($arParams["NO_GROUPS_STORE"] != "Y"):?>
		<?
		$arAllSections = $arAllSectionsIds = array();
		$arAllSectionsIds = array_column($arResult["STORES"], 'UF_STORE_GROUP');
		$arAllSectionsIds = array_unique($arAllSectionsIds);

		global $USER_FIELD_MANAGER;
		$arFields = $USER_FIELD_MANAGER->GetUserFields("CAT_STORE");
		$obEnum = new CUserFieldEnum;
		$rsEnum = $obEnum->GetList(array(), array("USER_FIELD_ID" => $arFields["UF_STORE_GROUP"]["ID"], "ID" => $arAllSectionsIds));
		while($arEnum = $rsEnum->GetNext()){
		   $arAllSections[$arEnum["ID"]] = $arEnum;
		   $arAllSections[$arEnum["ID"]]['SECTION']['NAME'] = $arEnum["VALUE"];
		}


		if(isset($arAllSections) && count($arAllSections)>0)
		{
			foreach($arAllSections as $key => $arSection)
			{
				foreach($arResult['STORES'] as $arItem)
				{
					$SID = ($arItem['UF_STORE_GROUP'] ? $arItem['UF_STORE_GROUP'] : 0); //var_dump($SID);
					if($arAllSections[$SID])
					{
						$arAllSections[$SID]['ITEMS'][$arItem['ID']] = $arItem;
					} else {
						$arAllSections[0]['ITEMS'][$arItem['ID']] = $arItem;
					}
				}
			}

			if(isset($arAllSections[0]) && count($arAllSections[0])>0){
				$arAllSections[0]['SECTION']["NAME"] = GetMessage("SECTION_OTHERS");
				$arAllSections[0]["SORT"] = 10000;
				$arAllSections[0]["ID"] = 999999999;
			}

			$arResult['SECTIONS'] = $arAllSections;

			if(!function_exists('_sort_stores_sectons_by_SORT')){
				function _sort_stores_sectons_by_SORT($a, $b){
					return ($a['SORT'] == $b['SORT'] ? ($a['ID'] < $b['ID'] ? -1 : 1) : ($a['SORT'] > $b['SORT'] ? 1 : -1));
				}
			}

			usort($arResult["SECTIONS"], '_sort_stores_sectons_by_SORT');

		}
		// else
		// {
		// 	$arResult['SECTIONS'][0]['ITEMS'] = $arResult['STORES'];
		// }

		?>
	<?endif;?>

<?else:?>
	<?LocalRedirect(SITE_DIR.'contacts/');?>
<?endif;?>