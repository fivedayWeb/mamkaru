<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>

<div id="home-cards">
    <div class="center clear tabs">
        <div id="home-cards-buttons" class="clear">
            <?foreach($arResult['SECTIONS'] as $index => $arSection):?>
                <div class="home-cards-button tab-button<?if($index == 0):?> active <?endif;?>">
                    <?=$arSection['NAME']?>
                </div>
            <?endforeach;?>
        </div>
        <div class="tabs-contents">
            <?foreach($arResult['SECTIONS'] as $index => $arSection):?>
                <div class="home-cards-tab tab-content<?if($index == 0):?> active <?endif;?>">
                    <div class="flex">
                        <?foreach($arSection['ELEMENTS'] as $arElement):?>
                            <?$APPLICATION->IncludeComponent('saybert:catalog.element.card','',
                                array(
                                    'ELEMENT_ID' => $arElement['PROPERTIES']['PRODUCT_ID']['VALUE']
                                )
                            );?>
                        <?endforeach;?>
                    </div>
                </div>
            <?endforeach;?>
        </div>
    </div>
</div>
