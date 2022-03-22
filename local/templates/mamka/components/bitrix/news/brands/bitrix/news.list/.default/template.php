<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);?>
<div class="right-content">
    <div id="brands-header">
        <input type="text" id="brands-search" placeholder="Поиск по брендам">
        <div id="brands-sort">
            Сортировать:
            <a href="#" data-block="alphabet" >по популярности</a>
            <span>/</span>
            <a href="#" class="active" data-block="logos">по алфавиту</a>
        </div>
    </div>
	<? print_r($arResult['JS']['RUSSIAN_ALPHABET']); ?>
    <div class="bx-brands brands_alphabet active">
        <div class="masonry-grid">
            <h2 class="letters-header">Русский алфавит</h2>
            <?foreach ($arResult['JS']['RUSSIAN_ALPHABET'] as $engLetter):?>
			<? echo $engLetter; ?>
                <?if (isset($arResult['ITEMS'][$engLetter]) && !empty($arResult['ITEMS'][$engLetter])):?>
                    <div class="a-z">
                        <h3 class="letter"><?=$engLetter?></h3>
                        <ul>
                            <?foreach ($arResult['ITEMS'][$engLetter] as $arItem):?>
                                <li>
                                    <a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>
                                </li>
                            <?endforeach;?>

                        </ul>
                    </div>
                <?endif;?>
            <?endforeach;?>
        </div>
        <div class="masonry-grid">
            <h2 class="letters-header">Английский алфавит</h2>

            <?foreach ($arResult['JS']['ENGLISH_ALPHABET'] as $engLetter):?>
                <?if (isset($arResult['ITEMS'][$engLetter]) && !empty($arResult['ITEMS'][$engLetter])):?>

                    <div class="a-z">
                        <h3 class="letter"><?=$engLetter?></h3>
                        <ul>

                            <?foreach ($arResult['ITEMS'][$engLetter] as $arItem):?>

                                <li>
                                    <a href="<?=$arItem['DETAIL_PAGE_URL']?>">
                                        <?=$arItem['NAME']?>
                                    </a>
                                </li>

                            <?endforeach;?>

                        </ul>
                    </div>

                <?endif;?>
            <?endforeach;?>

        </div>
    </div>

    <div id="brands-logos" class="bx-brands clear brands-logos">

        <?foreach ($arResult['COMPONENT_ITEMS'] as $arItem):?>

            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="brand-logo-card">
                <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>" />
                <div class="brand-logo-title"><?=$arItem['NAME']?></div>
            </a>

        <?endforeach;?>

    </div>
</div>