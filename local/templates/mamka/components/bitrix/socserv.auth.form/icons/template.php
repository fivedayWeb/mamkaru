<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<div class="bx-auth-serv-icons">
    <?foreach($arParams["~AUTH_SERVICES"] as $service):?>
        <?$class = false;
        if($service['NAME'] == 'Google+')
            $class='google';
        elseif($service['NAME'] == 'Google+')
            $class = '';
        elseif($service['NAME'] == 'Google+')
            $class = '';?>
        <a <?if($class):?>class="<?=$class?>"<?endif;?> title="<?=htmlspecialcharsbx($service["NAME"])?>" href="javascript:void(0)" onclick="BxShowAuthFloat('<?=$service["ID"]?>', '<?=$arParams["SUFFIX"]?>')">
            Войти с помощью <?=$service['NAME']?>
        </a>
    <?endforeach?>
</div>
