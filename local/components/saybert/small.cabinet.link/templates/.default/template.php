<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Saybert\Helpers\User;
$this->setFrameMode(true);
?>
<?if(!$arResult['IS_AUTH']):?>
    <button id="login-button" data-modal="login-modal" class="modal-open">Личный кабинет</button>
<?else:?>
    <a href="/personal/" class="login-button">Личный кабинет</a>
<?endif;?>
