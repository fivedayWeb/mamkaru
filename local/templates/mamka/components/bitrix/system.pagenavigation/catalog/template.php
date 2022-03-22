<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false)) return;
}

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
?>


<div id="pagination">
	<?if($arResult['NavPageNomer']  > 1):?>
		<a href="<?=$arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]-1)?>">&lt;</a>
	<?else:?>
		<a href="javascript:void(0)">&lt;</a>
	<?endif;?>
	<?if($arResult["NavPageCount"] < 5):?>
		<?while($arResult["nStartPage"] <= $arResult["NavPageCount"]):?>
			<?if ($arResult['nStartPage'] == 1):?>
				<?if ($arResult['NavPageNomer'] == 1):?>
					<a href="<?=$arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["nStartPage"])?>" class="active"><?=$arResult["nStartPage"]?></a>
				<?else:?>
					<a href="<?=$arResult["sUrlPath"].(!empty($strNavQueryString) ? '?'.$strNavQueryString : '')?>"><?=$arResult["nStartPage"]?></a>
				<?endif?>
			<?else:?>
				<a href="<?=$arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["nStartPage"])?>" <?if($arResult["nStartPage"] == $arResult['NavPageNomer']) echo 'class="active"';?>>
					<?=$arResult["nStartPage"]?>
				</a>
			<?endif?>
			<?$arResult["nStartPage"]++?>
		<?endwhile?>
	<?else:
		$i = 1;
		$current = $arResult['NavPageNomer'];
		$last = $arResult["NavPageCount"];
		while ($i <= $last):
			if ($i < 2):?>
				<a href="<?=$arResult["sUrlPath"].(!empty($strNavQueryString) ? '?'.$strNavQueryString : '')?>" <?if($i == $arResult['NavPageNomer']) echo 'class="active"';?>><?=$i?></a>
				<?$dotBool = true;?>
			<?elseif ($i > $last-1):?>
				<a href="<?=$arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult['NavNum'].'='.($i)?>" <?if($i == $arResult['NavPageNomer']) echo 'class="active"';?>>
					<?=$i?>
				</a>
				<?
				$dotBool = true;
			else:
				if ($i == $current-1 || $i == $current || $i == $current+1):?>
					<a href="<?=$arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult['NavNum'].'='.($i)?>" <?if($i == $arResult['NavPageNomer']) echo 'class="active"';?>><?=$i?></a>
					<?
					$dotBool = true;
				elseif($dotBool):
					$dotBool=false;
					?>
					<a>...</a>
				<?else:
					$i++;
					continue;
				endif;
			endif;
			$i++;
		endwhile;
	endif;

	if ($arResult['NavPageNomer']  < $arResult['NavPageCount']):?>
		<a href="<?=$arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]+1)?>">&gt;</a>
	<?else:?>
		<a href="javascript:void(0)">&gt;</a>
	<?endif;?>
</div>
