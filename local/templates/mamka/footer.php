<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
		</main>
		<footer id="footer">
			<div class="top-footer">
				<div class="center flexbox-block">
					<div id="company-info">
						<div class="footer-contacts-block">
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
						</div>
					</div>
					<div id="footer-nav" class="flexbox-block">
						<?$APPLICATION->IncludeComponent(
							"bitrix:menu", 
							"bottom", 
							array(
								"ALLOW_MULTI_SELECT" => "N",
								"CHILD_MENU_TYPE" => "catalog",
								"COMPONENT_TEMPLATE" => "bottom",
								"DELAY" => "N",
								"MAX_LEVEL" => "2",
								"MENU_CACHE_GET_VARS" => array(
								),
								"MENU_CACHE_TIME" => "3600",
								"MENU_CACHE_TYPE" => "A",
								"MENU_CACHE_USE_GROUPS" => "Y",
								"ROOT_MENU_TYPE" => "bottom",
								"USE_EXT" => "Y"
							),
							false
						);?>
					</div>
				</div>
			</div>
			<div class="middle-footer">
				<div class="center flexbox-block">
					<div class="footer-phone">
						<button id="footer-call-you" data-modal="call-you-modal" class="modal-open">Заказать звонок</button>
						<span id="footer-tel">
							<?$APPLICATION->IncludeFile(
			                    SITE_DIR."/local/templates/mamka/include/phone.php",
			                    Array(),
			                    Array("MODE"=>"html")
			                    );
			                ?>
						</span>
					</div>
				</div>
			</div>
			<div id="bottom-footer">
				<div class="center clear">
					<div class="metrics">
					</div>
					<div class="made-in">
						Сайт разработан в студии <a href="https://fiveday.ru">Пятница</a>
					</div>
				</div>
			</div>
		</footer>
		<div id="modal-backing">
		</div>
		<div id="call-you-modal" class="modal">
			<form>
				<div class="call-you-header">
					 Заказать звонок
				</div>
		 		<input type="text" name="name" placeholder="Ваше Имя">
		 		<input type="text" name="phone" placeholder="+7 (ХХХ) ХХХ-ХХ-ХХ">
		 		<input type="submit" value="Оставить заявку">
			</form>
			<button class="close-modal"></button>
		</div>
		<div id="order-you-modal" class="modal">
		    <form>
		        <div class="call-you-header">Заказать товар</div>
		        <input type="text" name="name" placeholder="Ваше Имя">
		        <input type="text" name="phone" placeholder="+7 (ХХХ) ХХХ-ХХ-ХХ">
		        <input type="submit" value="Оставить заявку">
		    </form>
		    <button class="close-modal"></button>
		</div>
		<?$APPLICATION->IncludeComponent(
	"bitrix:main.register", 
	"modal.header", 
	array(
		"AUTH" => "N",
		"REQUIRED_FIELDS" => array(
			0 => "EMAIL",
			1 => "NAME",
		),
		"SEF_FOLDER" => "/",
		"SEF_MODE" => "N",
		"SET_TITLE" => "N",
		"SHOW_FIELDS" => array(
			0 => "EMAIL",
			1 => "NAME",
		),
		"SUCCESS_PAGE" => "",
		"USER_PROPERTY" => array(
		),
		"USER_PROPERTY_NAME" => "",
		"USE_BACKURL" => "Y",
		"PRODUCT_ID" => "1",
		"COMPONENT_TEMPLATE" => "modal.header",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:system.auth.form",
			"modal.header",
			Array(
				"FORGOT_PASSWORD_URL" => "/auth/",
				"PROFILE_URL" => "profile.php",
				"REGISTER_URL" => "register.php",
				"SHOW_ERRORS" => "Y"
			)
		);?>
		<div id="thx" class="modal">
			<div id="thx-header">
				 Спасибо, за регистрацию!
			</div>
			<button class="close-modal"></button>
		</div>
		<div class="error-wrapper">
            <div id="error-block">
                <div class="error-block">
                    <div class="error-block-content">
                        <div class="error-block-text"></div>
                    </div>
                    <div class="clearfix">
                    </div>
                </div>
            </div>
        </div>
		<div class="added-wrapper">
			<div id="added-block">
				<div class="add-cart-block">
		 			<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="image" />
					<div class="add-cart-block-content">
						<div class="add-cart-block-title"></div>
						<div class="add-cart-block-price"></div>
					</div>
					<div class="clearfix">
					</div>
				</div>
			</div>
		</div>
		<!--VK Pixel --> <script type="text/javascript">!function(){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src="https://vk.com/js/api/openapi.js?169",t.onl oad=function(){VK.Retargeting.Init("VK-RTRG-968309-aPfR1"),VK.Retargeting.Hit()},document.head.appendChild(t)}();</script><noscript><img src="https://vk.com/rtrg?p=VK-RTRG-968309-aPfR1" style="position:fixed; left:-999px;" alt=""/></noscript> <!--VK pixel -->
		<!-- Facebook Pixel Code --> <script> !function(f,b,e,v,n,t,s) {if(f.fbq)return;n=f.fbq=function(){n.callMethod? n.callMethod.apply(n,arguments):n.queue.push(arguments)}; if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0'; n.queue=[];t=b.createElement(e);t.async=!0; t.src=v;s=b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t,s)}(window, document,'script', 'https://connect.facebook.net/en_US/fbevents.js'); fbq('init', '892352444883857'); fbq('track', 'PageView'); </script> <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=892352444883857&ev=PageView&noscript=1" /></noscript> <!-- End Facebook Pixel Code -->
		<!-- Yandex.Metrika counter -->
		<script type="text/javascript" >
		   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
		   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
		   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
		
		   ym(76677813, "init", {
				clickmap:true,
				trackLinks:true,
				accurateTrackBounce:true,
				webvisor:true
		   });
		</script>
		<noscript><div><img src="https://mc.yandex.ru/watch/76677813" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
		<!-- /Yandex.Metrika counter -->
	</body>
</html>

