<?php
namespace Bitrix\Saybert\Catalog;

class Tools{
    protected $_arSkuList;
    protected $_arOffers;
    protected $_arCurrentOffer;

    public function __construct($arSkuList,$arOffers,$arCurrentOffer = false){
        $this->_arOffers = $arOffers;
        $this->_arSkuList = $arSkuList;
        if(!$arCurrentOffer)
            $this->_arCurrentOffer = $this->_arOffers[0];
    }

    public function getCurrentSku(){
        
    }

    public static function formatPrice($price){
        if(floor($price) - $price > 0)
            return number_format($price,2,'.',' ');
        return number_format($price,0,'.',' ');
    }
}