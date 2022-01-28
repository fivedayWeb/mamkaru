<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$this->setFrameMode(true);

if (isset($arResult['REQUEST_ITEMS']) || isset($arParams['REQUEST_ITEMS']))
{
	CJSCore::Init(array('ajax'));
	$injectId = 'sale_gift_product_'.rand();

	// component parameters
	$signer = new \Bitrix\Main\Security\Sign\Signer;
	$signedTemplate = $signer->sign($arResult['RCM_TEMPLATE'] ? $arResult['RCM_TEMPLATE'] : $arParams['RCM_TEMPLATE'], 'bx.sale.prediction.product.detail');

	$arParams['INJECT_ID'] = $arResult['_ORIGINAL_PARAMS']['INJECT_ID'] = $injectId;
	unset($arParams['REQUEST_ITEMS'], $arParams['RCM_TEMPLATE'], $arResult['_ORIGINAL_PARAMS']['REQUEST_ITEMS'], $arResult['_ORIGINAL_PARAMS']['RCM_TEMPLATE']);

	$signedParameters = $signer->sign(
		base64_encode(serialize($arResult['_ORIGINAL_PARAMS'])),
		'bx.sale.prediction.product.detail'
	);

	$frame = $this->createFrame()->begin("");
	?>
	<span id="<?=$injectId?>" class="sale_prediction_product_detail_container"></span>
	<script>
	if(typeof obMShopPredictions === 'undefined'){
		var CMShopPredictions = function(){
			this.arData = {};
			this.bindEvents();
		}

		CMShopPredictions.prototype.bindEvents = function(){
			var _this = this;

			BX.ready(function(){
				BX.addCustomEvent('onHasNewPrediction', function(html, injectId){
					//console.log('onHasNewPrediction', injectId, html);

					//$('#simple-prediction').remove();
					if(BX.PopupWindowManager.isPopupExists('simple-prediction')){
						BX.PopupWindowManager.getPopupById('simple-prediction').destroy();
					}	
					predictionWindow = false;
					if(predictionHideTimeout){
						clearTimeout(predictionHideTimeout);
						predictionHideTimeout = false;
					}

					if(_this.arData && typeof _this.arData[injectId] !== 'undefined'){
						_this.arData[injectId].html = html;
						if(html.length){
							_this.show(injectId);
						}
						else{
							_this.hide(injectId);
						}
					}
				});

				$(document).on('mouseenter', '#simple-prediction', function(){
					if(predictionHideTimeout){
						clearTimeout(predictionHideTimeout);
						predictionHideTimeout = false;
					}
				});

				$(document).on('mouseleave', '#simple-prediction', function(){
					//$(this).remove();
					if(BX.PopupWindowManager.isPopupExists('simple-prediction')){
						BX.PopupWindowManager.getPopupById('simple-prediction').destroy();
					}					
					predictionWindow = false;
					if(predictionHideTimeout){
						clearTimeout(predictionHideTimeout);
						predictionHideTimeout = false;
					}
				});
			});
		}

		CMShopPredictions.prototype.add = function(data){
			if(typeof data === 'object' && typeof data.injectId === 'string' && typeof data.giftAjaxData === 'object'){
				this.arData[data.injectId] = {
					injectId: data.injectId,
					giftAjaxData: data.giftAjaxData,
					html: ''
				}

				//console.log('added prediction', data.injectId);

				return data.injectId;
			}

			return false;
		}

		CMShopPredictions.prototype.remove = function(i){
			if(this.arData && typeof this.arData[i] !== 'undefined'){
				this.hide(i);
				delete(this.arData[i]);

				//console.log('removed prediction', i);
			}
		}

		CMShopPredictions.prototype.removeAll = function(){
			if(this.arData){
				var keys = Object.keys(this.arData);
				for(i in keys){
					this.remove(keys[i]);
				}
			}

			this.arData = {};
		}

		CMShopPredictions.prototype.get = function(i){
			if(this.arData && typeof this.arData[i] !== 'undefined'){
				return this.arData[i];
			}
		}

		CMShopPredictions.prototype.getAll = function(i){
			return this.arData;
		}

		CMShopPredictions.prototype.show = function(i){
			var _this = this;

			if(this.arData && typeof this.arData[i] !== 'undefined'){
				var $inject = $('#' + i);

				if($inject.length && _this.arData[i].html.length){
					var $element = $inject.closest('.catalog_detail');
					if($element.length){
						//console.log('show prediction', i);

						var bFastView = $element.closest('#fast_view_item').length > 0;
						if(!bFastView){
							$('#headerfixed .button.has_prediction').removeClass('has_prediction');
						}
						$element.find('.has_prediction').removeClass('has_prediction');

						var $buttons = bFastView ? $element.find('.counter_wrapp .button_block .button.to-cart,.counter_wrapp .button_block .button.in-cart') : ($element.find('.offers_table').length > 0 ? $element.find('.offers_table .buy .counter_wrapp .button.to-cart,.offers_table .buy .counter_wrapp .button.in-cart,.info_item .middle_info .buy_block .slide_offer,#headerfixed .button.more') : $element.find('.info_item .middle_info .buy_block .button_block .button.to-cart,.info_item .middle_info .buy_block .button_block .button.in-cart,#headerfixed .button.to-cart,#headerfixed .button.in-cart'));

						if($buttons){
							$buttons.addClass('has_prediction');

							$buttons.unbind('mouseenter');
							$buttons.unbind('mouseleave');
							$buttons.mouseenter(function(){
								//$('#simple-prediction').remove();
								if(BX.PopupWindowManager.isPopupExists('simple-prediction')){
									BX.PopupWindowManager.getPopupById('simple-prediction').destroy();
								}	
								predictionWindow = false;
								if(predictionHideTimeout){
									clearTimeout(predictionHideTimeout);
									predictionHideTimeout = false;
								}

								
								predictionWindow = new BX.PopupWindow('simple-prediction', this, {
									offsetLeft: 40,
									offsetTop: -5,
									bindOptions: {
										position: 'top',
									},
									content:
									'<div class="catalog-element-popup-inner">' +
									_this.arData[i].html +
									'</div>',
									closeIcon: false,
									closeByEsc: false,
									angle: {
										position: 'bottom'
									}
								});
								
								predictionWindow.show();
							}).mouseleave(function(){
								if(predictionWindow){
									if(predictionHideTimeout){
										clearTimeout(predictionHideTimeout);
										predictionHideTimeout = false;
									}

									predictionHideTimeout = setTimeout(function(){
										//$('#simple-prediction').remove();
										if(BX.PopupWindowManager.isPopupExists('simple-prediction')){
											BX.PopupWindowManager.getPopupById('simple-prediction').destroy();
										}	
										predictionWindow = false;
									}, 500);
								}
							});
						}
					}
				}
				else{
					this.hide(i);
				}
			}
		}

		CMShopPredictions.prototype.showAll = function(){
			if(this.arData){
				var keys = Object.keys(this.arData);
				for(i in keys){
					this.show(keys[i]);
				}
			}
		}

		CMShopPredictions.prototype.hide = function(i){
			if(this.arData && typeof this.arData[i] !== 'undefined'){
				var $inject = $('#' + i);

				if($inject.length){
					var $element = $inject.closest('.catalog_detail');
					if($element.length){
						var bFastView = $element.closest('#fast_view_item').length > 0;
						if(!bFastView){
							$('#headerfixed .button.has_prediction').unbind('mouseenter');
							$('#headerfixed .button.has_prediction').unbind('mouseleave');
							$('#headerfixed .button.has_prediction').removeClass('has_prediction');
						}
						$element.find('.has_prediction').unbind('mouseenter');
						$element.find('.has_prediction').unbind('mouseleave');
						$element.find('.has_prediction').removeClass('has_prediction');
					}
				}

				//console.log('hided prediction', i);
			}
		}

		CMShopPredictions.prototype.hideAll = function(){
			if(this.arData){
				var keys = Object.keys(this.arData);
				for(i in keys){
					this.hide(keys[i]);
				}
			}
		}

		CMShopPredictions.prototype.update = function(i){
			if(this.arData && typeof this.arData[i] !== 'undefined'){
				var $inject = $('#' + i);

				if($inject.length){
					bx_sale_prediction_product_detail_load(
						this.arData[i].injectId,
						this.arData[i].giftAjaxData
					);

					//console.log('sended prediction', i);
				}
				else{
					this.remove(i);
				}
			}
		}

		CMShopPredictions.prototype.updateAll = function(){
			if(this.arData){
				var keys = Object.keys(this.arData);
				for(i in keys){
					this.update(keys[i]);
				}
			}
		}

		var obMShopPredictions = new CMShopPredictions();
		var predictionWindow = false;
		var predictionHideTimeout = false;
		var showPredictions = function(){
			obMShopPredictions.showAll();
		}
		var updatePredictions = function(){
			obMShopPredictions.updateAll();
		}
	}

	BX.ready(function(){
		var injectId = '<?=CUtil::JSEscape($injectId)?>';
		var giftAjaxData = {
			'parameters':'<?=CUtil::JSEscape($signedParameters)?>',
			'template': '<?=CUtil::JSEscape($signedTemplate)?>',
			'site_id': '<?=CUtil::JSEscape($component->getSiteId())?>'
		};

		obMShopPredictions.add({
			injectId: injectId,
			giftAjaxData: giftAjaxData,
		});

		obMShopPredictions.update(injectId);
	});
	</script>
	<?
	$frame->end();
	return;
}
else
{
	{ ?>
		<script>
		BX.ready(function () {
			BX.onCustomEvent('onHasNewPrediction', ['<?=(!empty($arResult['PREDICTION_TEXT']) ? \CUtil::JSEscape($arResult['PREDICTION_TEXT']) : '')?>', '<?=CUtil::JSEscape($arParams['INJECT_ID'])?>']);
		});
		</script>
		<?
	}
}