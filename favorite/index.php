<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Любимые товары");?>

<section id="content">
    <div class="center clear">
        <h1 class="content-header"><?=$APPLICATION->GetTitle()?></h1>
        <?$APPLICATION->IncludeComponent(
            "saybert:favorite.products",
            "template",
            array()
        );?>
    </div>
</section>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>