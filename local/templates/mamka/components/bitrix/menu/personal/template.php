<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true);
?>
<div id="kabinet-tabs">
    <div id="kabinet-tabs-wrapper">
        <a href="/personal/cart/" <? if(strpos ($_SERVER['REQUEST_URI'],'/personal/cart/') !== false): ?>class="active"<? endif; ?>>
            Просмотр корзины
        </a>
        <a href="/personal/order/make" <? if(strpos ($_SERVER['REQUEST_URI'],'/personal/order/make/') !== false): ?>class="active"<? endif; ?>>
            Оформление заказа
        </a>
        <a href="/personal/" <? if($_SERVER['REQUEST_URI'] == '/personal/'): ?>class="active"<? endif; ?>>
            Настройки профиля
        </a>
        <a href="/personal/orders/" <? if(strpos ($_SERVER['REQUEST_URI'],'/personal/orders/') !== false): ?>class="active"<? endif; ?>>
            История покупок
        </a>
    </div>
</div>
