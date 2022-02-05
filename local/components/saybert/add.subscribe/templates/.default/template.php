<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Saybert\Helpers\User;
$this->setFrameMode(true);
?>
<div id="footer-subscribe" class="clear bx-news-subscribe-form">
    <div>Подписка на новости</div>
    <form method="POST">
        <input type="email" name="email" placeholder="Email">
        <input type="hidden" name="action" value="addsubscriber">
        <input type="submit">
    </form>
</div>