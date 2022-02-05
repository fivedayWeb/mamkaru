<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 * @var $APPLICATION CMain
 */

if ($arParams["SET_TITLE"] == "Y")
{
	$APPLICATION->SetTitle(Loc::getMessage("SOA_ORDER_COMPLETE"));
}
?>

<? if (!empty($arResult["ORDER"])): ?>

	<table class="sale_order_full_table">
		<tr>
			<td>
				<?=Loc::getMessage("SOA_ORDER_SUC", array(
					"#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"]->toUserTime()->format('d.m.Y H:i'),
					"#ORDER_ID#" => $arResult["ORDER"]["ACCOUNT_NUMBER"]
				))?>
				<? if (!empty($arResult['ORDER']["PAYMENT_ID"])): ?>
					<?=Loc::getMessage("SOA_PAYMENT_SUC", array(
						"#PAYMENT_ID#" => $arResult['PAYMENT'][$arResult['ORDER']["PAYMENT_ID"]]['ACCOUNT_NUMBER']
					))?>
				<? endif ?>
				<? if ($arParams['NO_PERSONAL'] !== 'Y'): ?>
					<br /><br />
					<?=Loc::getMessage('SOA_ORDER_SUC1', ['#LINK#' => $arParams['PATH_TO_PERSONAL']])?>
				<? endif; ?>
			</td>
		</tr>
	</table>

	<?
	if ($arResult["ORDER"]["IS_ALLOW_PAY"] === 'Y')
	{
		if (!empty($arResult["PAYMENT"]))
		{
			foreach ($arResult["PAYMENT"] as $payment)
			{
				if ($payment["PAID"] != 'Y')
				{
					if (!empty($arResult['PAY_SYSTEM_LIST'])
						&& array_key_exists($payment["PAY_SYSTEM_ID"], $arResult['PAY_SYSTEM_LIST'])
					)
					{
						$arPaySystem = $arResult['PAY_SYSTEM_LIST_BY_PAYMENT_ID'][$payment["ID"]];

						if (empty($arPaySystem["ERROR"]))
						{
							?>
							<br /><br />

							<table class="sale_order_full_table">
								<tr>
									<td class="ps_logo">
										<div class="pay_name"><?=Loc::getMessage("SOA_PAY") ?></div>
										<?=CFile::ShowImage($arPaySystem["LOGOTIP"], 100, 100, "border=0\" style=\"width:100px\"", "", false) ?>
										<div class="paysystem_name"><?=$arPaySystem["NAME"] ?></div>
										<br/>
									</td>
								</tr>
								<tr>
									<td>
										<? if ($arPaySystem["ACTION_FILE"] <> '' && $arPaySystem["NEW_WINDOW"] == "Y" && $arPaySystem["IS_CASH"] != "Y"): ?>
											<?
											$orderAccountNumber = urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]));
											$paymentAccountNumber = $payment["ACCOUNT_NUMBER"];
											?>
											<script>
												window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=$orderAccountNumber?>&PAYMENT_ID=<?=$paymentAccountNumber?>');
											</script>
										<?=Loc::getMessage("SOA_PAY_LINK", array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".$orderAccountNumber."&PAYMENT_ID=".$paymentAccountNumber))?>
										<? if (CSalePdf::isPdfAvailable() && $arPaySystem['IS_AFFORD_PDF']): ?>
										<br/>
											<?=Loc::getMessage("SOA_PAY_PDF", array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".$orderAccountNumber."&pdf=1&DOWNLOAD=Y"))?>
										<? endif ?>
										<? else: ?>
											<?=$arPaySystem["BUFFERED_OUTPUT"]?>
										<? endif ?>
									</td>
								</tr>
							</table>

							<?
						}
						else
						{
							?>
							<span style="color:red;"><?=Loc::getMessage("SOA_ORDER_PS_ERROR")?></span>
							<?
						}
					}
					else
					{
						?>
						<span style="color:red;"><?=Loc::getMessage("SOA_ORDER_PS_ERROR")?></span>
						<?
					}
				}
			}
		}
	}
	else
	{
		?>
		<br /><strong><?=$arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR']?></strong>
		<?
	}

	// new
	define("SHOP_ID", "622");
	global $kasses;
	$kasses = ['4'=>0,'1'=>0,'2'=>0,'3'=>0];
	function getToCart(){
		$basket = \Bitrix\Sale\Basket::loadItemsForFUser(
		    \Bitrix\Sale\Fuser::getId(), 
		    \Bitrix\Main\Context::getCurrent()->getSite()
		);
		$basketItems = $basket->getBasketItems();
		$items = $pids = [];
		foreach ($basketItems as $item)	{
			$itemdata = [];
			foreach ($item->getAvailableFields() as $fieldcode){
				$itemdata[$fieldcode] = $item->getField($fieldcode);
			}
			$productInfoBySKUId = \CCatalogSku::GetProductInfo($itemdata['PRODUCT_ID']);
			if (is_array($productInfoBySKUId)){
				//echo ' - ID товара = '.$mxResult2['ID'] .'<br>';
				$itemdata['PRODUCT_ID'] = $productInfoBySKUId['ID'];
			}
			$itemdata['discprice'] = $item->getDiscountPrice();
			$pids[$itemdata['PRODUCT_ID']] = 1;
			foreach ($item->getPropertyCollection() as $property) {
				$itemdata['PROPS'][$property->getField('CODE')] = [
					'NAME'=>$property->getField('NAME'),
					'CODE'=>$property->getField('CODE'),
					'VALUE'=>$property->getField('VALUE'),
					'SORT'=>$property->getField('SORT'),
					'XML_ID'=>$property->getField('XML_ID')
				];
			}
			$items[$itemdata['PRODUCT_ID']] = $itemdata;
		}
		if(!empty($items)){
			global $kasses;
			$pics = $sects = [];
			$res = CIblockElement::GetList([],['IBLOCK_ID'=>SHOP_ID,'ID'=>array_keys($pids)],false,false,['IBLOCK_ID','ID','DETAIL_PICTURE','DETAIL_PAGE_URL','IBLOCK_SECTION_ID']);
			while($tob=$res->GetNextElement()){
				$ob = $tob->GetFields();
				$ob['PROPS'] = $tob->GetProperties();

				$mxResult = \CCatalogSku::GetProductInfo($ob['ID']);
				if (is_array($mxResult)){
					//echo 'ID товара = '.$mxResult['ID'];
					$ob['ID'] = $mxResult['ID'];
				}

				if(!empty($ob['DETAIL_PICTURE'])){
					$pics[$ob['ID']]['PICTURE'] = CFile::ResizeImageGet($ob['DETAIL_PICTURE'], array('width'=>120, 'height'=>'120'), BX_RESIZE_IMAGE_EXACT, true)['src'];
				}
				$pics[$ob['ID']]['URL'] = $ob['DETAIL_PAGE_URL'];
				if(!empty($ob['IBLOCK_SECTION_ID'])){
					$sects[$ob['IBLOCK_SECTION_ID']] = $ob['IBLOCK_SECTION_ID'];
					$items[$ob['ID']]['IBLOCK_SECTION_ID'] = $ob['IBLOCK_SECTION_ID'];
				}
				$items[(int)$ob['ID']]['KASSA'] = '4';
			}
			foreach ($items as $key => $value) {
				$items[$key]['PRICE'] = floatval($items[$key]['PRICE']);
				$items[$key]['QUANTITY'] = floatval($items[$key]['QUANTITY']);
				if(!empty($pics[$items[$key]['PRODUCT_ID']]['NAME'])){
					$items[$key]['NAME'] = $pics[$items[$key]['PRODUCT_ID']]['NAME'];
				}
				if(!empty($pics[$items[$key]['PRODUCT_ID']]['URL'])){
					$items[$key]['URL'] = $pics[$items[$key]['PRODUCT_ID']]['URL'];
				}
				if(!empty($pics[$items[$key]['PRODUCT_ID']]['PICTURE'])){
					$items[$key]['PICTURE'] = $pics[$items[$key]['PRODUCT_ID']]['PICTURE'];
				}
			}
			if(!empty($sects)){
				$res = CIblockSection::GetList([],['IBLOCK_ID'=>SHOP_ID,'ID'=>$sects],false,['IBLOCK_ID','ID','NAME','UF_2IP']);
				while($ob = $res->GetNext()){
					if(!empty($ob['UF_2IP'])){
						$sects[$ob['ID']] = '2';
					}
					else {
						$sects[$ob['ID']] = '4';
					}
				}
			}
			foreach ($items as $id => $item) {
				if(!empty($sects[$item['IBLOCK_SECTION_ID']])){
					$items[(int)$id]['KASSA'] = $sects[$item['IBLOCK_SECTION_ID']];
				}
				else {
					$items[(int)$id]['KASSA'] = '4';
				}
				$kasses[$items[(int)$id]['KASSA']]++;
			}
		}
		return $items;
	}
	$cart = getToCart();
	if (!empty($kasses)) {
		$i=0;foreach ($kasses as $kassa => $kassa_q) {
			if(empty($kassa_q)){
				continue;
			}
			$i++;
			if($i==1){?>
				<div class="col-12">
					<h1 style="padding-top: 30px;">В вашей корзине остались товары</h1>
				</div>
			<?}
			?>
			<h2>Заказ #<?=$i?><?=($kassa!=4?'. Товары партнера':'')?></h2><div class="cartrow"><?
			$sum = 0;
			foreach ($cart as $id => $item) {
				if($item['KASSA']!=$kassa){
					continue;
				}
				$sum += $item['PRICE']*$item['QUANTITY'];
			?>
				<div class="col-12" style="padding: 10px 0;">
					<div class="row align-items-center">
						<div class="col-3 col-md-1">
							<img src="<?=$item['PICTURE']?>" alt="">
						</div>
						<div class="col-9 col-md-4">
							<a href="<?=$item['URL']?>" style="font-size: 16px;"><?=$item['NAME']?></a>
						</div>
						<div class="col-4 col-md-2" style="text-align: center;font-size: 18px;">
							<b><?=$item['PRICE']?> руб.</b><br>
							<span style="font-size: 14px;">Цена за 1 <?=$item['MEASURE_NAME']?></span>
						</div>
						<div class="col-4 col-md-2">
							<div class="product-ves fln">
	                            <div class="ves-minus intovar JSAD_CartRemove" data-min="1" data-id="<?=$id?>" data-step="1"></div>
	                            <div class="ves-input">
	                                <input data-id="<?=$id?>" data-max="1000" data-price="<?=$item['PRICE']?>" data-step="1" data-min="1" type="text" class="ves-input intovar JSAD_CartInput active" value="<?=$item['QUANTITY']?>">
	                            </div>
	                            <div class="ves-plus intovar JSAD_CartAdd" data-id="<?=$id?>" data-step="1" data-max="1000000"></div>
	                        </div>
						</div>
						<div class="col-4 hidden-xs col-md-2" style="text-align: center;font-size: 18px;">
							<b><?=$item['PRICE']*$item['QUANTITY']?> руб.</b>
						</div>
						<div class="col-4 col-md-1" style="text-align: center;">
							<img class="delete-cart JSAD_CartDelete" data-id="<?=$id?>" src="https://tvoisadrus.ru/img/del.png" alt="">
						</div>
					</div>
				</div>
			<?}?>
			</div>
			<div class="col-12" style="padding-bottom: 40px;padding-top: 20px">
				<div class="row align-items-center" style="text-align: right;">
					<div class="col-6 col-sm-9 col-md-10">
						<b class="cartsum" style="font-size: 16px"><?=$sum?> руб.</b>
					</div>
					<div class="col-6 col-sm-3 col-md-2 bx-basket bx-blue">
						<a href="/personal/order/make/?SITE_ID=<?=$kassa?>" class="btn btn-lg btn-default waves-effect ">Оформить заказ</a>
					</div>
				</div>
			</div>
		<?}
	}
	?>

	<style>
	.body .ves-input {
	    height: 30px;
	    width: 30px;
	    text-align: center;
	    float: left;
	}
	.body .ves-input input {
	    height: 30px;
	    width: 30px;
	    text-align: center;
	    font-size: 18px;
	    border: none;
	    color: #333;
	    font-family: 'Bebas Neue';
	}
	.body .ves-minus {
	    width: 25px;
	    height: 30px;
	    cursor: pointer;
	    position: relative;
	    float: left;
	}
	.body .ves-plus {
	    width: 25px;
	    height: 30px;
	    cursor: pointer;
	    position: relative;
	    float: left;
	}
	.body .product-ves {
	    border: solid 1px #ccc;
	    height: 32px;
	    float: left;
	    border-radius: 2px;
	    display: inline-block;
	}
	.body .delete-cart {
	    height: 25px;
	    cursor: pointer;
	    opacity: 0.7;
	}
	.ves-minus::before {
	    content: '';
	    position: absolute;
	    display: block;
	    width: 9px;
	    height: 3px;
	    top: 14px;
	    background-color: #ccc;
	    left: 10px;

	}
	.ves-plus::before {
	    content: '';
	    position: absolute;
	    display: block;
	    width: 9px;
	    height: 3px;
	    top: 14px;
	    background-color: #ccc;
	    left: 10px;

	}
	.ves-plus::after {
	    content: '';
	    position: absolute;
	    display: block;
	    width: 3px;
	    height: 9px;
	    top: 11px;
	    background-color: #ccc;
	    left: 13px;

	}
	</style>
	<script>
		function ADJS_CartUpdate() {
		  $.post('/cart/ajax.php',{"method":"update"},function(data){
		    if(data.status!='ok') {
		      alertMess('Ошибка');
		      return false;
		    }
		    $('.JSAD_CartArea').html(data.html);
		  });
		}
		function ADJS_CartAdd(id,quantity,mess) {
		  $.post('/cart/ajax.php',{"method":"add","id":id,"quantity":quantity},function(data){
		    if(data.status!='ok') {
		      alertMess('Ошибка');
		      return false;
		    }
		    try {
		      $('.JSAD_CartInput[data-id='+id+']').val(data.cart['items'][id]['IN_CART']);
		      $('.ADJS_CartSum').html(data.cart['sum']);
		      if(data.cart['sum']<1000) {
		        $('.cartbtn').hide();
		        $('.cartalert').show();
		      }
		      else {
		        $('.cartbtn').show();
		        $('.cartalert').hide();
		      }
		    }
		    catch (e) {
		      console.log('Что то не так: '+e);
		    }
		    if(mess===true){
		      ADJS_CartAddMess(
		        $('.ADJS_ToCartButton[data-id='+id+']').attr('data-name'),
		        $('.ADJS_ToCartButton[data-id='+id+']').attr('data-img')
		      );
		    }
		  });
		}
		function ADJS_CartSet(id,quantity) {
		  $.post('/cart/ajax.php',{"method":"set","id":id,"quantity":quantity},function(data){
		    if(data.status!='ok') {
		      alertMess('Ошибка');
		      return false;
		    }
		    $('.JSAD_CartInput[data-id='+id+']').val(data.cart['items'][id]['IN_CART']);
		    $('.ADJS_CartSum').html(data.cart['sum']);
		    if(data.cart['sum']<1000) {
		      $('.cartbtn').hide();
		      $('.cartalert').show();
		    }
		    else {
		      $('.cartbtn').show();
		      $('.cartalert').hide();
		    }
		  });
		  $('.cartrow').each(function(){
		  	var inputs = $(this).find('.JSAD_CartInput');
		  	if(inputs.length<1){
		  		$(this).next().remove();
		  		$(this).prev().remove();
		  		$(this).remove();
		  	}
		  	else {
		  		var sum = 0;
		  		inputs.each(function(){
		  			sum += $(this).val() * $(this).attr('data-price');
		  			$(this).parent().parent().parent().next().find('b').text(($(this).val() * $(this).attr('data-price'))+' руб.');
		  		});
		  		$(this).next().find('.cartsum').text(sum+' руб.');
		  	}
		  });
		}
		function ADJS_CartRemove(id,quantity) {
		  $.post('/cart/ajax.php',{"method":"remove","id":id,"quantity":quantity},function(data){
		    if(data.status!='ok') {
		      alertMess('Ошибка');
		      return false;
		    }
		    $('.JSAD_CartInput[data-id='+id+']').val(data.cart['items'][id]['IN_CART']);
		    $('.ADJS_CartSum').html(data.cart['sum']);
		    if(data.cart['sum']<1000) {
		      $('.cartbtn').hide();
		      $('.cartalert').show();
		    }
		    else {
		      $('.cartbtn').show();
		      $('.cartalert').hide();
		    }
		  });   
		}
		function ADJS_CartDelete(id) {
		  $.post('/cart/ajax.php',{"method":"delete","id":id},function(data){
		    if(data.status!='ok') {
		      alertMess('Ошибка');
		      return false;
		    }
		    if(data.cart['sum']<1000) {
		      $('.cartbtn').hide();
		      $('.cartalert').show();
		    }
		    else {
		      $('.cartbtn').show();
		      $('.cartalert').hide();
		    }
		    $('.cartrow').each(function(){
		    	var inputs = $(this).find('.JSAD_CartInput');
		    	if(inputs.length<1){
		    		$(this).next().remove();
		    		$(this).prev().remove();
		    		$(this).remove();
		    	}
		    	else {
		    		var sum = 0;
		    		inputs.each(function(){
		    			sum += $(this).val() * $(this).attr('data-price');
		    			$(this).parent().parent().parent().next().find('b').text(($(this).val() * $(this).attr('data-price'))+' руб.');
		    		});
		    		$(this).next().find('.cartsum').text(sum+' руб.');
		    	}
		    });
		  });
		}
		$(document).on('click','.JSAD_CartAdd',function(e){
		  var id = parseInt($(this).attr('data-id')),
		    max = parseInt($(this).attr('data-max')),
		    step = parseInt($(this).attr('data-step')),
		    val = parseInt($('.JSAD_CartInput[data-id='+$(this).attr('data-id')+']').val());
		    $('.JSAD_CartInput[data-id='+$(this).attr('data-id')+']').val(val+1);
		    ADJS_CartSet(id,val+1);
		});
		$(document).on('click','.JSAD_CartRemove',function(e){
		  var id = parseInt($(this).attr('data-id')),
		    min = parseInt($(this).attr('data-min')),
		    step = parseInt($(this).attr('data-step')),
		    val = parseInt($('.JSAD_CartInput[data-id='+$(this).attr('data-id')+']').val());
		    if(val<2) val=2;
		    $('.JSAD_CartInput[data-id='+$(this).attr('data-id')+']').val(val-1);
		    ADJS_CartSet(id,val-1);
		});
		$(document).on('click','.JSAD_CartDelete',function(e){
		  ADJS_CartDelete(parseInt($(this).attr('data-id')));
		  $(this).parent().parent().parent().remove();
		});
		$(document).on('change','.JSAD_CartInput',function(e){
		  $(this).val(Math.ceil($(this).val()/parseInt($(this).attr('data-step')))*parseInt($(this).attr('data-step')));
		  var id = parseInt($(this).attr('data-id')),
		    min = parseInt($(this).attr('data-min')),
		    max = parseInt($(this).attr('data-max')),
		    step = parseInt($(this).attr('data-step')),
		    val = parseInt($(this).val());
		  if(max<val+step)
		    ADJS_CartSet(id,(Math.floor(max/step)*step));
		  else 
		    if(min>=val-step)
		      ADJS_CartSet(id,min);
		    else
		      ADJS_CartSet(id,val);
		});
		function alertMess(mess) {
			$('body').append('<div style="position: fixed;left: 0;top: 0;right: 0;bottom: 0;z-index: 10;"><div style="position: absolute;left: 0;top: 0;right: 0;bottom: 0;background-color: #000;opacity: 0.5;" onclick="this.parentNode.parentNode.removeChild(this.parentNode);"></div><div id="modler" style="width: 500px;max-width:100%;position: absolute;left: 50%;top: 50%;transform:translate(-50%,-50%);background-color: #fff;border: solid 1px #ccc;box-shadow: 0 0 40px -6px #555;text-align: center;"><div onclick="this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);" style="position: absolute;right: 0;top: 0;z-index: 2;font-size: 4em;width: 1em;height: 1em;line-height: 33px;color: #333;transform: rotate(45deg);cursor: pointer;">+</div><br><br><br><br><p style="text-align: center;left: 0;right: 0;font-size: 24px;font-family: \'Bebas Neue\';">'+mess+'</p><br><br><br><br></div></div>');
		}
	</script>
<? else: ?>

	<b><?=Loc::getMessage("SOA_ERROR_ORDER")?></b>
	<br /><br />

	<table class="sale_order_full_table">
		<tr>
			<td>
				<?=Loc::getMessage("SOA_ERROR_ORDER_LOST", ["#ORDER_ID#" => htmlspecialcharsbx($arResult["ACCOUNT_NUMBER"])])?>
				<?=Loc::getMessage("SOA_ERROR_ORDER_LOST1")?>
			</td>
		</tr>
	</table>

<? endif;

	use Bitrix\Main\UI\Extension;
	Extension::load('ui.bootstrap4');?>