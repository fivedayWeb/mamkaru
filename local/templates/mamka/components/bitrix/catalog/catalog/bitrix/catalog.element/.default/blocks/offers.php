<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
usort($arResult['OFFERS_BY_PHOTO'], function ($a, $b) {
	if ($a['CAN_BUY'] == $b['CAN_BUY']) {
		return 0;
	}
	return ($a['CAN_BUY'] == 'Y') ? -1 : 1;
});
?>
<?if($arResult['CATALOG_TYPE'] == 3)://Если товар с торговыми предложениями?>
    <div class="offers_container">
        <div class="offers_select opened">
            <div class="offers_select_title">
                <span>Выберите торговое предложение</span>
                <img src="<?=$templateFolder?>/images/sidebar-arrow.png" />
            </div>
            <div class="offers_option_container">
                <?foreach($arResult['OFFERS_BY_PHOTO'] as $arOffer):

                    $arClass = ['offers_option'];
                    if ($arOffer['CURRENT'] && !$_GET['pid']) {
                        $arClass[] = 'selected';
                    }
                    $arPropsNames = array_map(
                        function ($arProp) {
                            return $arProp['NAME'];
                        }, 
                        $arOffer['PROPERTIES']
                    );

//print_r($arOffer['PROPERTIES']);
                    $isShowImage = in_array('Цвет', $arPropsNames);
                    if (!$isShowImage) {
                        $arClass[] = 'no_image';
                    }
                    ?>
                    <div class="<?=implode(' ', $arClass)?>" data-offer="<?=$arOffer['ID']?>">
                        <div class="offers_option_block flex">
                            <div class="offer_left flex">
                                <?if ($isShowImage):?>
                                    <div class="offers_image">
                                        <img 
                                            src="<?=$arOffer['small']?>" 
                                            data-preview="<?=$arOffer['preview']?>" 
                                            data-detail="<?=$arOffer['detail']?>"
                                            title="<?=$arOffer['NAME']?>" 
                                            alt="<?=$arOffer['NAME']?>" 
                                            />
                                    </div>
                                <?endif;?>
                                <?if (!empty($arOffer['PROPERTIES'])):?>
                                    <div class="offers_properties">
                                        <?foreach ($arOffer['PROPERTIES'] as $arProp):?>
                                            <div class="offer_prop">
                                                <span><?=$arProp['NAME']?>:</span>
                                                <span><?=$arProp['DISPLAY_VALUE']?></span>
                                            </div>
                                        <?endforeach;?>
                                    </div>
                                <?else:?>
                                    <div class="offer_name"><?=$arOffer['NAME']?></div>
                                <?endif;?>
                            </div>
                            <?
                            
                            $canByText = 'В наличии';
                            $arClass = ['offer_can_buy'];
                            if (!$arOffer['CAN_BUY']) {
                                $arClass[] = 'no';
                                $canByText = 'Под заказ';
                            }
                            ?>
                            <div class="<?=implode(' ', $arClass)?>"><?=$canByText?></div>
                        </div>
                    </div>
                <?endforeach;?>
            </div>
        </div>
    </div>
<?endif;?>