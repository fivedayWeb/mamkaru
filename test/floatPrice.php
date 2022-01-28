<?require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');?>
<?
CModule::IncludeModule('iblock');
?>

<style type="text/css">
	#prices {
		border-collapse: collapse;
		border-spacing: 0;
	}
	#prices th,
	#prices td {
		border: 1px solid #000;
		padding: 5px;
	}
</style>

<div class="center">
	<table id="prices">
		<thead>
			<tr>
				<th>Название</th>
				<th>Цена</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td colspan="2">Товары</td>
			</tr>
			<?
			$res = CIBlockElement::GetList(
				[],
				[
					'IBLOCK_ID' => \Custom\Catalog::IBLOCK_ID,
					'ACTIVE' => 'Y',
					'CATALOG_TYPE' => 1
				],
				false,
				false,
				[
					'ID',
					'IBLOCK_ID',
					'NAME',
					'CODE',
					'DETAIL_PAGE_URL',
					'CATALOG_GROUP_3'
				]
			);
			while ($item = $res->GetNext()):
				$price = $item['CATALOG_PRICE_3'];
				if ($price == intval($price)) {
					continue;
				}
				?>
				<tr>
					<td><a href="<?=$item['DETAIL_PAGE_URL']?>"><?=$item['NAME']?></a></td>
					<td><?=$item['CATALOG_PRICE_3']?></td>
				</tr>
			<?endwhile;?>
			<tr>
				<td colspan="2">Торговые предложения</td>
			</tr>
			<?
			$res = CIBlockElement::GetList(
				[],
				[
					'IBLOCK_ID' => \Custom\Catalog::PRODUCT_IBLOCK_ID,
					'ACTIVE' => 'Y',
				],
				false,
				false,
				[
					'ID',
					'IBLOCK_ID',
					'NAME',
					'CODE',
					'PROPERTY_CML2_LINK.DETAIL_PAGE_URL',
					'CATALOG_GROUP_3'
				]
			);
			while ($item = $res->GetNext()):
				$price = $item['CATALOG_PRICE_3'];
				if ($price == intval($price)) {
					continue;
				}
				?>
				<tr>
					<td>
						<?if (!empty($item['PROPERTY_CML2_LINK_DETAIL_PAGE_URL'])):?>
							<a href="<?=$item['PROPERTY_CML2_LINK_DETAIL_PAGE_URL']?>"><?=$item['NAME']?></a></td>
						<?else:?>
							<?=$item['NAME']?></a>
						<?endif;?>
					</td>
					<td><?=$item['CATALOG_PRICE_3']?></td>
				</tr>
			<?endwhile;?>
		</tbody>
	</table>
</div>
<?require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');?>