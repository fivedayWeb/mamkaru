<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

?>
<? if ($arResult['CATALOG_TYPE'] == 3): //если есть торговые предложения?>
<? //echo $arResult['CATALOG_TYPE']; ?>
    <div class="clear">
        <? include('offers.php') ?>
        <div class="clear">
            <? foreach ($arResult['OFFERS'] as $arOffer): ?>

                <? $style = ($arOffer['ID'] != $arResult['OFFER_SELECTED']['ID']) ? 'display:none' : '' ?>

                <div class="price-size bx-prices-block" data-offer="<?= $arOffer['ID'] ?>" style="<?= $style ?>">

					<? if ($arOffer['CATALOG_QUANTITY'] > 0) : ?>

                    	<? foreach ($arOffer['PRICES'] as $code => $arPrice): ?>

							<? if (!empty($arPrice['VALUE_NOVAT']) && $arPrice['VALUE_NOVAT'] > $arPrice['DISCOUNT_VALUE_VAT']): ?>
								<s>
									<? if ($arPrice['CURRENCY'] == 'RUB'): ?>
										<?= Price::format($arPrice['VALUE_NOVAT']) ?> ₽.
									<? else: ?>
										<?= $arPrice['PRINT_VALUE_NOVAT'] ?>
									<? endif; ?>
								</s>
							<? endif; ?>
							<? if ($arPrice['CURRENCY'] == 'RUB'): ?>
								<?= Price::format($arPrice['DISCOUNT_VALUE_VAT']) ?> ₽.
							<? else: ?>
								<?= $arPrice['PRINT_DISCOUNT_VALUE_VAT'] ?>
							<? endif ?>

                    	<? endforeach; ?>
					<? else: ?>
						<span>Цену уточняйте<br>у менеджера</span>
					<? endif ?>
                </div>
            <? endforeach; ?>
        </div>
        <? include('top_prop.php'); ?>
        <div class="flex">
            <? foreach ($arResult['OFFERS'] as $arOffer):
                $style = ($arOffer['ID'] != $arResult['OFFER_SELECTED']['ID']) ? 'display:none' : ''; ?>
                <div class="bx-product" data-offer="<?= $arOffer['ID'] ?>" style="<?= $style ?>">
                    <? if ($arOffer['CAN_BUY']): ?>
                        <div class="product-count">
                            <input type="number" min="1" value="1">
                        </div>
                        <button class='bx-product-add-to-cart product-add-to-cart'
                                data-offer="<?= $arOffer['ID'] ?>">Купить
                        </button>
                    <? else: ?>
                        <a data-modal="order-you-modal" class='modal-open bx-product-add-to-order'
                           data-offer="<?= $arResult['ID'] ?>">Заказать</a>
                    <? endif; ?>
                </div>
            <? endforeach; ?>
        </div>
        <?
        $totalQuantity = 0;
        foreach ($arResult['OFFERS'] as $arOffer) {
            if ($arOffer['CATALOG_QUANTITY'] > 0) {
                $totalQuantity += $arOffer['CATALOG_QUANTITY'];
            }
        }
        foreach ($arResult['OFFERS'] as $arOffer):?>
            <? $style = ($arOffer['ID'] != $arResult['OFFER_SELECTED']['ID']) ? 'display:none' : '' ?>
            <div class="product-in-store" data-offer="<?= $arOffer['ID'] ?>" style="<?= $style ?>">
                <?
                if ($arOffer['CATALOG_QUANTITY'] > 0):?>
                    Товар на складе: <span class="green">В наличии</span>
                <? else: ?>
                    Товар на складе: <span style="font-weight: 500;color:red;">Под заказ</span>
                <? endif ?>
            </div>
        <? endforeach; ?>
    </div>
<? elseif ($arResult['CATALOG_TYPE'] == 1): ?>
<? //echo $arResult['CATALOG_TYPE']; ?>
    <div class="clear">
        <? include('offers.php') ?>
        <div class="clear">
            <div class="price-size bx-prices-block" data-offer="<?= $arResult['ID'] ?>">
                <?
                $isPrice = false;

				foreach ($arResult['PRICES'] as $code => $arPrice):
					if (!$arPrice['VALUE']) {
						continue;
					}
					$isPrice = true;
					if ($arResult['CATALOG_QUANTITY']) :
						if ($arPrice['CURRENCY'] == 'RUB'):?>
							<?= Price::format($arPrice['DISCOUNT_VALUE_VAT']) ?> ₽.
						<? else: ?>
							<?= $arPrice['PRINT_DISCOUNT_VALUE_VAT'] ?>
						<? endif ?>
					<? else: ?>
						Цену уточняйте у менеджера
					<? endif; ?>
				<? endforeach; ?>

            </div>
        </div>
        <? include('top_prop.php'); ?>
        <div class="flex">
            <? if ($arResult['CAN_BUY'] && $isPrice): ?>
                <div class="bx-product">
                    <div class="product-count">
                        <input type="number" min="1" value="1">
                    </div>
                    <button class='product-add-to-cart bx-product-add-to-cart' data-offer="<?= $arResult['ID'] ?>">
                        Купить
                    </button>
                </div>
            <? else: ?>
                <a data-modal="order-you-modal" class='modal-open bx-product-add-to-order'
                   data-offer="<?= $arResult['ID'] ?>">Заказать</a>
            <? endif ?>
        </div>
    </div>
    <div class="product-in-store" style="padding-left: 0">
        <?
        foreach ($arResult['OFFERS'] as $offer) {
            $arResult['CATALOG_QUANTITY'] += intval($offer['CATALOG_QUANTITY']);
        }

        if ($arResult['CATALOG_QUANTITY']):?>
            <? if ($arResult['CATALOG_QUANTITY'] > 5): ?>
                Товар на складе: <span class="green">В наличии</span>
            <? else: ?>
                Товар на складе: <span style="color: #f5d901;font-weight: 500;">Меньше 5</span>
            <? endif; ?>
        <? else: ?>
            Товар на складе: <span style="font-weight: 500;color:red;">Под заказ</span>
        <? endif ?>
    </div>
<? endif; ?>