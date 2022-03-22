<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

?>
<style>
	#akciya-block {
		margin: 45px 0px 0;
		width: 100%;
	}
	#akciya-block-head {   

		border-bottom: 4px solid #d5afc3;
	}
	#akciya-block #akciya-block-head .akciya-block-title {
		background: #d5afc3;
		color: #fff; 
		position: relative;
		display: inline-block;
   		font-size: 24px;
    	line-height: 45px;
    	font-family: "FuturaRoundBold", "Roboto", sans-serif;
    	padding: 0 20px;
    	cursor: pointer;
    	transition: color .2s ease-in;
	}
	#akciya-block #akciya-block-head .akciya-block-title:before {
		width: 100%;
		height: 15px;
		position: absolute;
		top: -15px;
		left: 0;
		content: "";
		display: block;
		background: linear-gradient(to top right, #d5afc3 48%, transparent 52%), linear-gradient(to top right, transparent 50%, transparent 50%);
	}
	#akciya-block #akciya-block-content {
		padding: 30px 0;
   		color: #00104b;
	}
	#akciya-block #akciya-block-content h2 {
		font-size: 32px;
    	margin: 0px 0 10px;
    	font-family: "FuturaRoundBold", "Roboto", sans-serif;
    	color: #00104b;
	}
	#akciya-block #akciya-block-content p {
		font-size: 18px;
		overflow: hidden;
		color: #00104b;
		line-height: 22px;
		padding: 0px 0px 0;
		margin-bottom: 7px;
	}
	#akciya-block #akciya-block-content a {
		font-size: 18px;
		overflow: hidden;
		font-weight: 600;
		color: #00104b;
		line-height: 22px;
		padding: 0px 0px 0;
		margin-bottom: 7px;
		transition: all .2s ease-in;
	}
	#akciya-block #akciya-block-content a:hover {
		color: #e01d7b;
	}
	.countdown {
	  font-family: sans-serif;
	  color: #fff;
	  display: inline-block;
	  font-weight: 100;
	  text-align: center;
	  font-size: 30px;
	}
	 
	.countdown-number {
	  padding: 10px;
	  border-radius: 3px;
	  background: #d5afc3;
	  display: inline-block;
	}
	 
	.countdown-time {
	  padding: 15px;
	  border-radius: 3px;
	  background: #e01d7b;
	  display: inline-block;
	}
	 
	.countdown-text {
	  display: block;
	  padding-top: 5px;
	  font-size: 16px;
	}
</style>
<? if ($arResult['PROPERTIES']['AKCIYA']['VALUE_XML_ID'] == 'Y'): //если есть акция ?>
	<div class="flex">
		<div id="akciya-block" style="">
			<div id="akciya-block-head">
				<div class="akciya-block-title">Акция</div>
			</div>
			<div id="akciya-block-content">
				<h2>Только сегодня! Успей купить!</h2>
				<p><a href="https://mamkaru.ru/catalog/?q=пустышка+Bibs&" target="blank">Пустышка Bibs</a> в подарок!</p>
				<div id="countdown" class="countdown">
				  	<!--
					<div class="countdown-number">
						<span class="days countdown-time"></span>
						<span class="countdown-text">Days</span>
				  	</div>
					-->
					<div class="countdown-number">
						<span class="hours countdown-time"></span>
						<span class="countdown-text">часы</span>
					</div>
					<div class="countdown-number">
						<span class="minutes countdown-time"></span>
						<span class="countdown-text">минуты</span>
					</div>
						<div class="countdown-number">
							<span class="seconds countdown-time"></span>
						<span class="countdown-text">секунды</span>
					</div>
				</div>
				<!--отсчет времени 24 часа-->
			</div>
		</div>

	</div>
<script>
	function getTimeRemaining(endtime) {
	  var t = Date.parse(endtime) - Date.parse(new Date());
	  var seconds = Math.floor((t / 1000) % 60);
	  var minutes = Math.floor((t / 1000 / 60) % 60);
	  var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
		// var days = Math.floor(t / (1000 * 60 * 60 * 24));
	  return {
		'total': t,
		  //'days': days,
		'hours': hours,
		'minutes': minutes,
		'seconds': seconds
	  };
	}
	 
	function initializeClock(id, endtime) {
	  var clock = document.getElementById(id);
	  var daysSpan = clock.querySelector('.days');
	  var hoursSpan = clock.querySelector('.hours');
	  var minutesSpan = clock.querySelector('.minutes');
	  var secondsSpan = clock.querySelector('.seconds');
	 
	  function updateClock() {
		var t = getTimeRemaining(endtime);
	 
		  //daysSpan.innerHTML = t.days;
		hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
		minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
		secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);
	 
		if (t.total <= 0) {
		  clearInterval(timeinterval);
		}
	  }
	 
	  updateClock();
	  var timeinterval = setInterval(updateClock, 1000);
	}
	 
	var deadline = new Date(Date.parse(new Date())+ 20*60*60*1000);//'May 26 2021 15:00:00 GMT+0300'; // for endless timer
	initializeClock('countdown', deadline);
</script>
<!--
Указываем дату окончания работы таймера
Формат вывода даты ISO 8601:
var deadline = '2015-12-31';

Сокращенный формат:
var deadline = '31/12/2015';

Длинный формат:
var deadline = 'December 31 2015';

Вывод даты с точным временем и часовым поясом:
var deadline="January 01 2018 00:00:00 GMT+0300";

Вывод таймера для лендингов – таймер все время будет выводить, что осталось 15 дней (можно указать любое время)
var deadline = new Date(Date.parse(new Date()) + 15 * 24 * 60 * 60 * 1000);
-->
<? endif; ?>