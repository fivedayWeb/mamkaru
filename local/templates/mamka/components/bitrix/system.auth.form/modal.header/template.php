<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>
<div id="login-modal" class="clear modal bx-auth-float">
    <div class="login-right">
        <img src="<?=SITE_TEMPLATE_PATH?>/i/login.png" />
    </div>

    <form class="login-left" name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
        <?foreach ($arResult["POST"] as $key => $value):?>
            <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
        <?endforeach?>
        <input type="hidden" name="AUTH_FORM" value="Y" />
        <input type="hidden" name="TYPE" value="AUTH" />

        <div class="login-header">Войти</div>
        <?if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'])
            ShowMessage($arResult['ERROR_MESSAGE']);
        ?>
        <input type="text" name="USER_LOGIN" placeholder="Email">
        <input type="password" name="USER_PASSWORD" placeholder="Пароль">
        <p class="link">
            <noindex>
                <a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow" class="forgot_password">Забыли пароль?</a>
            </noindex>
        </p>
        <label class="checkbox-ui">
            <input type="checkbox" name="USER_REMEMBER" value="Y">
            <span class="d-block checkbox-item"></span>
            <span class="d-block">Запомнить меня</span>
        </label>
        <input type="submit" value="Авторизоваться">
        <div class="login-to-registration">Не зарегистрированы?
            <button>Зарегистрироваться</button>
        </div>
    </form>
    <button class="close-modal"></button>
</div>

<?if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR']):?>

    <script>
        $(function(){
            $('#login-modal').fadeToggle(300), $("#modal-backing").fadeToggle(300)
        })
    </script>
<?endif;?>
