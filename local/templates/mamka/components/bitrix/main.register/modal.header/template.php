<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if ($USER->IsAuthorized()):
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_REQUEST["register_submit_button"])) {
        LocalRedirect($APPLICATION->GetCurPage());
    }
    else {
        return false;
    }
endif;

$showModal = false;
?>
<div id="registration" class="clear modal">
    <div class="registration-left">
        <img src="<?=$templateFolder?>/images/registration.png" />
    </div>
    <form class="registration-right" method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data">
        <?if ($arResult["BACKURL"] <> ''):?>
            <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
        <?endif;?>
        <div class="registration-header">Зарегистрироваться</div>
        <?if (count($arResult["ERRORS"]) > 0):?>
            <div class="error">
                <?foreach ($arResult["ERRORS"] as $key => $error):
                    if (intval($key) == 0 && $key !== 0) {
                        echo str_replace("#FIELD_NAME#", "&quot;" . GetMessage("REGISTER_FIELD_" . $key) . "&quot;", $error);
                        echo "<br>";
                    }else
                        echo $error;?>
                <?endforeach;?>
            </div>
            <?$showModal = true;?>
        <?endif;?>
        <?foreach ($arResult["SHOW_FIELDS"] as $FIELD):?>
            <?if($FIELD == "AUTO_TIME_ZONE" && $arResult["TIME_ZONE_ENABLED"] == true):?>
                <select name="REGISTER[AUTO_TIME_ZONE]" onchange="this.form.elements['REGISTER[TIME_ZONE]'].disabled=(this.value != 'N')">
                    <option value=""><?echo GetMessage("main_profile_time_zones_auto_def")?></option>
                    <option value="Y"<?=$arResult["VALUES"][$FIELD] == "Y" ? " selected=\"selected\"" : ""?>><?echo GetMessage("main_profile_time_zones_auto_yes")?></option>
                    <option value="N"<?=$arResult["VALUES"][$FIELD] == "N" ? " selected=\"selected\"" : ""?>><?echo GetMessage("main_profile_time_zones_auto_no")?></option>
                </select>
                <select name="REGISTER[TIME_ZONE]"<?if(!isset($_REQUEST["REGISTER"]["TIME_ZONE"])) echo 'disabled="disabled"'?>>
                    <?foreach($arResult["TIME_ZONE_LIST"] as $tz=>$tz_name):?>
                        <option value="<?=htmlspecialcharsbx($tz)?>"<?=$arResult["VALUES"]["TIME_ZONE"] == $tz ? " selected=\"selected\"" : ""?>><?=htmlspecialcharsbx($tz_name)?></option>
                    <?endforeach?>
                </select>
            <?else:?>
                <?switch ($FIELD)
                    {
                        case "PASSWORD":?>
                            <input type="password" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" class="bx-auth-input" placeholder="Пароль" />
                            <?break;
                        case "CONFIRM_PASSWORD":?>
                            <input type="password" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" placeholder="Повторите пароль"/>
                                <?break;
                        case "PERSONAL_GENDER":?>
                            <select name="REGISTER[<?=$FIELD?>]">
                                <option value=""><?=GetMessage("USER_DONT_KNOW")?></option>
                                <option value="M"<?=$arResult["VALUES"][$FIELD] == "M" ? " selected=\"selected\"" : ""?>><?=GetMessage("USER_MALE")?></option>
                                <option value="F"<?=$arResult["VALUES"][$FIELD] == "F" ? " selected=\"selected\"" : ""?>><?=GetMessage("USER_FEMALE")?></option>
                            </select>
                            <?break;?>
                        <?case "EMAIL":?>
                            <input size="30" type="text" class="bx_reg_email" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" placeholder="Email"/>
                            <?break?>
                        <?case "LOGIN":?>
                            <input type="hidden" class="bx_reg_login" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" />
                            <?break?>
                        <?case "NAME":?>
                            <input size="30" type="text"  name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" placeholder="Ваше Имя"/>
                            <?break?>
                        <?default:?>
                            <?if ($FIELD == "PERSONAL_BIRTHDAY"):?>
                                <small><?=$arResult["DATE_FORMAT"]?></small><br />
                            <?endif;?>
                            <input size="30" type="text" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" placeholder="Email" />
                            <?if ($FIELD == "PERSONAL_BIRTHDAY")
                                $APPLICATION->IncludeComponent(
                                    'bitrix:main.calendar',
                                    '',
                                    array(
                                        'SHOW_INPUT' => 'N',
                                        'FORM_NAME' => 'regform',
                                        'INPUT_NAME' => 'REGISTER[PERSONAL_BIRTHDAY]',
                                        'SHOW_TIME' => 'N'
                                    ),
                                    null,
                                    array("HIDE_ICONS"=>"Y")
                                );
                }?>
            <?endif?>
        <?endforeach?>
        <?/* CAPTCHA */
        if ($arResult["USE_CAPTCHA"] == "Y"):?>
            <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
            <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" class="captcha _captcha" />
            <a href="#" class="_reload_captcha">
                <img src="<?=$templateFolder?>/images/reload.png" width="40" height="40" title="Обновить код" alt="Обновить код" />
            </a>
            <input type="text" name="captcha_word" maxlength="50" value="" />
        <?endif;?>
        <label class="checkbox-ui">
            <input type="checkbox" name="check_subscribe">
            <span class="d-block checkbox-item"></span>
            <span class="d-block">Я хочу получать новости об акциях</span>
        </label>
        <input type="submit" value="Зарегистрироваться"  name="register_submit_button">
        <div class="registration-to-login">Уже зарегистрированы?
            <button>Войти</button>
        </div>
    </form>
    <button class="close-modal" ></button>
</div>

<script>
    <?if($showModal):?>
        var bx_register_show_modal = true;
    <?endif;?>
    if(bx_register_show_modal){
        $('#registration').fadeToggle(300), $("#modal-backing").fadeToggle(300)
    }
    $('.bx_reg_email').change('on',function () {
        $('.bx_reg_login').val($(this).val());
    });

    $('._reload_captcha').on('click', function() {
        var $block = $(this).closest('#registration');
         $.getJSON('<?=$templateFolder?>/reload_captcha.php', function(data) {
            $block.find('._captcha').attr('src','/bitrix/tools/captcha.php?captcha_sid=' + data);
            $block.find('[name=captcha_sid]').val(data);
         });
         return false;
    });
</script>
