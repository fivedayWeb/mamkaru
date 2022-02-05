							<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") die();?>
							<?IncludeTemplateLangFile(__FILE__);?>
							<?if(CSite::InDir(SITE_DIR.'help/') || CSite::InDir(SITE_DIR.'company/') || CSite::InDir(SITE_DIR.'info/')):?>
								</div>
							<?endif;?>
			<?if(!$isFrontPage && !$isContactsPage):?>
							</div>
						</div>
					</section>
				</div>
			<?endif;?>
		</div><?// <div class="wrapper">?>
		<footer id="footer" <?=($isFrontPage ? 'class="main"' : '')?>>
			<div class="footer_inner">
				<div class="wrapper_inner">
					<div class="footer_top">
						<div class="wrap_md">
							<div class="iblock sblock">
								<?$APPLICATION->IncludeComponent(
									"bitrix:subscribe.form", 
									"mshop", 
									array(
										"AJAX_MODE" => "N",
										"SHOW_HIDDEN" => "N",
										"ALLOW_ANONYMOUS" => "Y",
										"SHOW_AUTH_LINKS" => "N",
										"CACHE_TYPE" => "A",
										"CACHE_TIME" => "86400",
										"CACHE_NOTES" => "",
										"SET_TITLE" => "N",
										"AJAX_OPTION_JUMP" => "N",
										"AJAX_OPTION_STYLE" => "Y",
										"AJAX_OPTION_HISTORY" => "N",
										"AJAX_OPTION_ADDITIONAL" => "",
										"LK" => "Y",
										"COMPONENT_TEMPLATE" => "mshop",
										"USE_PERSONALIZATION" => "Y",
										"PAGE" => SITE_DIR."personal/subscribe/",
									),
									false
								);?>
							</div>
							<div class="iblock phones">
								<div class="wrap_md">
									<div class="empty_block iblock"></div>
									<div class="phone_block iblock">
										<span class="phone_wrap">
											<span class="icons"></span>
											<span><?$APPLICATION->IncludeFile(SITE_DIR."include/phone.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("PHONE")));?></span>
										</span>
										<span class="order_wrap_btn">
											<span class="callback_btn"><?=GetMessage('CALLBACK')?></span>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="footer_bottom">
						<div class="wrap_md">
							<div class="iblock menu_block">
								<div class="wrap_md">
									<div class="iblock copy_block">
										<div class="copyright">
											<?$APPLICATION->IncludeFile(SITE_DIR."include/copyright.php", Array(), Array("MODE" => "html", "NAME"  => GetMessage("COPYRIGHT")));?>
										</div>
										<span class="pay_system_icons">
											<?$APPLICATION->IncludeFile(SITE_DIR."include/pay_system_icons.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("PHONE")));?>
										</span>
									</div>
									<div class="iblock all_menu_block">
										<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu_top", array(
											"ROOT_MENU_TYPE" => "bottom",
											"MENU_CACHE_TYPE" => "Y",
											"MENU_CACHE_TIME" => "86400",
											"MENU_CACHE_USE_GROUPS" => "N",
											"MENU_CACHE_GET_VARS" => array(),
											"MAX_LEVEL" => "1",
											"USE_EXT" => "N",
											"DELAY" => "N",
											"ALLOW_MULTI_SELECT" => "N"
											),false
										);?>
										<div class="wrap_md">
											<div class="iblock submenu_block">
												<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
													"ROOT_MENU_TYPE" => "bottom_company",
													"MENU_CACHE_TYPE" => "Y",
													"MENU_CACHE_TIME" => "86400",
													"MENU_CACHE_USE_GROUPS" => "N",
													"MENU_CACHE_GET_VARS" => array(),
													"MAX_LEVEL" => "1",
													"USE_EXT" => "N",
													"DELAY" => "N",
													"ALLOW_MULTI_SELECT" => "N"
													),false
												);?>
											</div>
											<div class="iblock submenu_block">
												<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
													"ROOT_MENU_TYPE" => "bottom_info",
													"MENU_CACHE_TYPE" => "Y",
													"MENU_CACHE_TIME" => "86400",
													"MENU_CACHE_USE_GROUPS" => "N",
													"MENU_CACHE_GET_VARS" => array(),
													"MAX_LEVEL" => "1",
													"USE_EXT" => "N",
													"DELAY" => "N",
													"ALLOW_MULTI_SELECT" => "N"
													),false
												);?>
											</div>
											<div class="iblock submenu_block">
												<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
													"ROOT_MENU_TYPE" => "bottom_help",
													"MENU_CACHE_TYPE" => "Y",
													"MENU_CACHE_TIME" => "86400",
													"MENU_CACHE_USE_GROUPS" => "N",
													"MENU_CACHE_GET_VARS" => array(),
													"MAX_LEVEL" => "1",
													"USE_EXT" => "N",
													"DELAY" => "N",
													"ALLOW_MULTI_SELECT" => "N"
													),false
												);?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="iblock social_block">
								<div class="wrap_md">
									<div class="empty_block iblock"></div>
									<div class="social_wrapper iblock">
										<div class="social">
											<?include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/social.info.mshop.default.php');?>
										</div>
									</div>
								</div>
								<div id="bx-composite-banner"></div>
							</div>
						</div>
					</div>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/bottom_include1.php", Array(), Array("MODE" => "text", "NAME" => GetMessage("ARBITRARY_1"))); ?>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/bottom_include2.php", Array(), Array("MODE" => "text", "NAME" => GetMessage("ARBITRARY_2"))); ?>
				</div>					
			</div>	
		</footer>		
		<?
		if(!CSite::inDir(SITE_DIR."index.php")){
			if(strlen($APPLICATION->GetPageProperty('title')) > 1){
				$title = $APPLICATION->GetPageProperty('title');
			} 
			else{
				$title = $APPLICATION->GetTitle();
			}
			$APPLICATION->SetPageProperty("title", $title.(COption::GetOptionString('aspro.mshop', 'HIDE_SITE_NAME_IN_TITLE', '', SITE_ID) == 'Y' ? '' : ' - ' .$arSite['SITE_NAME']) );
		}
		else{
			if(strlen($APPLICATION->GetPageProperty('title')) > 1){
				$title =  $APPLICATION->GetPageProperty('title');
			}
			else{
				$title =  $APPLICATION->GetTitle();
			}
			if(!empty($title)){
				$APPLICATION->SetPageProperty("title", $title);
			}
			else{
				$APPLICATION->SetPageProperty("title", $arSite['SITE_NAME']);
			}
		}
		?>
		<div id="content_new"></div>
			<?CMShop::footerAction();?>
	<!-- Rating Mail.ru counter -->
	<script type="text/javascript">
	var _tmr = window._tmr || (window._tmr = []);
	_tmr.push({id: "3222799", type: "pageView", start: (new Date()).getTime(), pid: "USER_ID"});
	(function (d, w, id) {
	  if (d.getElementById(id)) return;
	  var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
	  ts.src = "https://top-fwz1.mail.ru/js/code.js";
	  var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
	  if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
	})(document, window, "topmailru-code");
	</script><noscript><div>
	<img src="https://top-fwz1.mail.ru/counter?id=3222799;js=na" style="border:0;position:absolute;left:-9999px;" alt="Top.Mail.Ru" />
	</div></noscript>
	<!-- //Rating Mail.ru counter -->
	<!-- Facebook Pixel Code -->
	<script>
	!function(f,b,e,v,n,t,s)
	{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};
	if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
	n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];
	s.parentNode.insertBefore(t,s)}(window, document,'script',
	'https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '892352444883857');
	fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none"
	src="https://www.facebook.com/tr?id=892352444883857&ev=PageView&noscript=1"
	/></noscript>
	<!-- End Facebook Pixel Code -->
	<script type="text/javascript">!function(){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src="https://vk.com/js/api/openapi.js?169",t.onload=function(){VK.Retargeting.Init("VK-RTRG-968309-aPfR1"),VK.Retargeting.Hit()},document.head.appendChild(t)}();</script><noscript><img src="https://vk.com/rtrg?p=VK-RTRG-968309-aPfR1" style="position:fixed; left:-999px;" alt=""/></noscript>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-HV0ZT27N56"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	
	  gtag('config', 'G-HV0ZT27N56');
	</script>
	<!-- Yandex.Metrika counter -->
	<script type="text/javascript" >
	   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
	   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
	   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
	
	   ym(76677813, "init", {
			clickmap:true,
			trackLinks:true,
			accurateTrackBounce:true,
			webvisor:true,
			ecommerce:"dataLayer"
	   });
	</script>
	<noscript><div><img src="https://mc.yandex.ru/watch/76677813" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- /Yandex.Metrika counter -->
</body>
</html>