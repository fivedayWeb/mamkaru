<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$strReturn = '';
if($arResult){
	CModule::IncludeModule("iblock");
	global $MShopSectionID;
	$cnt = count($arResult);
	$lastindex = $cnt - 1;
	$bShowCatalogSubsections = COption::GetOptionString("aspro.mshop", "SHOW_BREADCRUMBS_CATALOG_SUBSECTIONS", "Y", SITE_ID) == "Y";

	for($index = 0; $index < $cnt; ++$index){
		$arSubSections = array();
		$arItem = $arResult[$index];
		$title = htmlspecialcharsex($arItem["TITLE"]);
		$bLast = $index == $lastindex;
		if($MShopSectionID && $bShowCatalogSubsections){
			$arSubSections = CMShop::getChainNeighbors($MShopSectionID, $arItem['LINK']);
		}
		if($index){
			$strReturn .= '<span class="separator">-</span>';
		}
		if($arItem["LINK"] <> "" && $arItem['LINK'] != GetPagePath() && $arItem['LINK']."index.php" != GetPagePath() || $arSubSections){
			$strReturn .= '<div class="bx-breadcrumb-item'.($arSubSections ? ' drop' : '').'" id="bx_breadcrumb_'.$index.'" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
			if($arSubSections){
				if($index == ($cnt-1)){
					$strReturn .= '<a class="number" href="'.$arItem["LINK"].'" itemprop="item"><span itemprop="name" content="'.$title.'"></span><meta itemprop="position" content="'.($index + 1).'"></a>';
					$strReturn .= '<span class="number">';
					$strReturn .=($arSubSections ? '<span>'.$title.'</span><b class="space"></b><span class="separator'.($bLast ? ' cat_last' : '').'"></span>' : '<span>'.$title.'</span>');
					$strReturn .= '</span>';
				} else {
					$strReturn .= '<a class="number" href="'.$arItem["LINK"].'" itemprop="item">';
					$strReturn .=($arSubSections ? '<span itemprop="name">'.$title.'</span><b class="space"></b><span class="separator'.($bLast ? ' cat_last' : '').'"></span>' : '<span>'.$title.'</span>');
					$strReturn .= '<meta itemprop="position" content="'.($index + 1).'">';
					$strReturn .= '</a>';
				}
				$strReturn .= '<div class="dropdown_wrapp"><div class="dropdown">';
					foreach($arSubSections as $arSubSection){
						$strReturn .= '<a href="'.$arSubSection["LINK"].'">'.$arSubSection["NAME"].'</a>';
					}
				$strReturn .= '</div></div>';
				
			}
			else{
				$strReturn .= '<a href="'.$arItem["LINK"].'" title="'.$title.'" itemprop="item"><span itemprop="name">'.$title.'</span><meta itemprop="position" content="'.($index + 1).'"></a>';
			}
			$strReturn .= '</div>';
		}
		else{
			$arItem['LINK'] = ($index == ($cnt-1) ? GetPagePath() : $arItem['LINK']);
			$strReturn .= '<span><span>'.$title.'</span></span>';
			$strReturn .= '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
				<span itemprop="name" content="'.$title.'"></span>
				<meta itemprop="position" content="'.($index + 1).'">
				<a class="number" href="'.$arItem["LINK"].'" itemprop="item"></a>
			</span>';
		}
	}
	
	return '<div class="breadcrumbs" id="navigation" itemscope="" itemtype="http://schema.org/BreadcrumbList">'.$strReturn.'</div>';
}
else{
	return $strReturn;
}
?>