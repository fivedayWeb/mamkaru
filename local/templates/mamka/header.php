<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
SiteVersion::Init();
IncludeTemplateLangFile(__FILE__);
use Bitrix\Main\Page\Asset;
?><!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
    <head>
        <meta charset="<?=SITE_CHARSET?>">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
		<meta name="facebook-domain-verification" content="far60evz96qxhiur9db6jnkuxo7lzs" />
        <title><? $APPLICATION->ShowTitle(); ?></title>
        <? $APPLICATION->ShowHead(); ?>
        <?
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/font-awesome.min.css');
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/fonts.css');

        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/jquery.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/jquery.maskedinput.js");
        // Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/notify.min.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/lazyload.min.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/additional.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/main.js");

//$APPLICATION->AddHeadString('<script src="//code.jivosite.com/widget.js" jv-id="aV6VjAZ9Fg" async></script>');
        ?>
<meta name="yandex-verification" content="9c0e9bc7e93efbdb" />
<meta name="google-site-verification" content="g2bc3LeabAGKZyAx4NMp8Q7q2bSAurE23xBkSa-5TL8" />

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-JZPYSQTN1N"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-JZPYSQTN1N');
</script>

    </head>
    <body>
        <? $APPLICATION->ShowPanel(); ?>
        <header id="header">
            <div id="top-header">
                <div class="center flexbox-block">
                    <div id="top-nav">
                        <ul>
                            <li><a href="/oplata-i-dostavka/">Оплата и Доставка</a></li>
                            <li><a href="/garantii/">Гарантии</a></li>
                            <li><a href="/news/">Новости</a></li>
                            <li><a href="/articles/">Статьи</a></li>
                            <li><a href="/contacts/">Контакты</a></li>
                        </ul>
                    </div>
                    <?if (!SiteVersion::isPhone()):?>
                        <div class="flexbox-block">
                    <?endif?>
                        <div class="phone-box flexbox-block">
                            <span id="header-tel">
                                <?$APPLICATION->IncludeFile(
                                    SITE_DIR."/local/templates/mamka/include/phone.php",
                                    Array(),
                                    Array("MODE"=>"html")
                                    );
                                ?>
                            </span>
                            <!--<button id="connect-button" data-modal="call-you-modal" class="modal-open"><span>Свяжитесь с нами</span>
                            </button>-->
							<a href="https://api.whatsapp.com/send/?phone=79613321843&text=Здравствуйте, просьба связаться со мной+&app_absent=0" target="blank" id="connect-button">Свяжитесь с нами</a>
                        </div>
                        <?$APPLICATION->IncludeComponent('saybert:small.cabinet.link','',array())?>
                    <?if (!SiteVersion::isPhone()):?>
                        </div>
                    <?endif?>
                </div>
            </div>
            <div id="mid-header">
                <div class="center flexbox-block">
                    <div class="logo">
                        <?if ($APPLICATION->GetCurPage(false) == '/'):?>
                            <span class="link">
                                <?$APPLICATION->IncludeFile(
                                    SITE_DIR."/local/templates/mamka/include/logo.php",
                                    Array(),
                                    Array("MODE"=>"html")
                                    );
                                ?>
                            </span>
                        <?else:?>
                            <a href="/" class="link">
                                <?$APPLICATION->IncludeFile(
                                    SITE_DIR."/local/templates/mamka/include/logo.php",
                                    Array(),
                                    Array("MODE"=>"html")
                                    );
                                ?>  
                            </a>
                        <?endif?>
                    </div>
                    <div id="mid-nav-block" class="flexbox-block">
                        <nav id="main-nav">
                            <? $APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"top_menu", 
	array(
		"COMPONENT_TEMPLATE" => "top_menu",
		"ROOT_MENU_TYPE" => "top",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_USE_GROUPS" => "N",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "1",
		"CHILD_MENU_TYPE" => "catalog",
		"USE_EXT" => "N",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N"
	),
	false
); ?>
                            <button id="mobile-connect-button" data-modal="call-you-modal" class="modal-open">Заказать звонок</button>
                        </nav>
                        <?$APPLICATION->IncludeComponent(
	"bitrix:search.title", 
	"mamka", 
	array(
		"COMPONENT_TEMPLATE" => "mamka",
		"NUM_CATEGORIES" => "1",
		"TOP_COUNT" => "5",
		"ORDER" => "date",
		"USE_LANGUAGE_GUESS" => "Y",
		"CHECK_DATES" => "N",
		"SHOW_OTHERS" => "N",
		"PAGE" => SITE_DIR."catalog/",
		"SHOW_INPUT" => "Y",
		"INPUT_ID" => "title-search-input",
		"CONTAINER_ID" => "search",
		"PRICE_CODE" => array(
			0 => "BASE2",
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "",
		"SHOW_PREVIEW" => "Y",
		"PREVIEW_WIDTH" => "75",
		"PREVIEW_HEIGHT" => "75",
		"CONVERT_CURRENCY" => "Y",
		"CURRENCY_ID" => "RUB",
		"CATEGORY_0_TITLE" => "Товары",
		"CATEGORY_0" => array(
			0 => "iblock_catalog",
		),
		"CATEGORY_0_iblock_1c_cat" => array(
			0 => "566",
		),
		"CATEGORY_0_iblock_catalog" => array(
			0 => "all",
		)
	),
	false
);?>
                    </div>
                    <button id="menu-button">
                        <span class="line1"></span>
                        <span class="line2"></span>
                        <span class="line3"></span>
                    </button>
                    <div id="cart-like-block" class="flexbox-block">
                        <a href="/favorite/" id="like-header-link">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 300"><defs><style>.cls-1{stroke-linecap:round;stroke-linejoin:round;stroke-width:6px;}</style></defs><title>button</title><path class="cls-1" d="M234.5 127.7c0-28-20.4-50.7-45.5-50.7-23.2 0-42.3 11.7-45 36.7-3-25-22-36.7-45.2-36.7-25 0-45.4 22.7-45.4 50.7C56 175.4 140 209 144 210.2c4-1.2 88-34.8 90.5-82.5z"/></svg>
                            <span>Избранное</span>
                        </a>
                        <?$APPLICATION->IncludeComponent('saybert:small.basket','',array() )?>
                    </div>
                </div>
            </div>
        </header>
        <?if (!SiteVersion::isMobile()):?>
            <?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"header.sections", 
	array(
		"COMPONENT_TEMPLATE" => "header.sections",
		"ROOT_MENU_TYPE" => "catalog",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_USE_GROUPS" => "N",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "1",
		"CHILD_MENU_TYPE" => "catalog",
		"USE_EXT" => "Y",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N"
	),
	false
);?>
        <?endif;?>
        <?if (!SiteVersion::isPhone() && $APPLICATION->GetCurPage(false) != '/'):?>
            <?$APPLICATION->IncludeComponent('bitrix:breadcrumb','' ,array())?>
        <?endif?>
        <main>
