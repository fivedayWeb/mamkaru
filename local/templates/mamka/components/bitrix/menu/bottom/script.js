$(document).ready(function () {
	$('#menu-toggle').click(function(){
			$(this).toggleClass("active");
			$('.menu-bottom').slideToggle(400);
	});
	$("#menu-toggle").click(function () {
        $("#menu-toggle").toggleClass("active-span")
    });
    $(".navigation ul li a").click(function () {
        $(this).toggleClass("active");
    });
});