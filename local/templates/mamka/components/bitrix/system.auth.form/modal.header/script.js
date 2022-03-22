$(function(){
	$(".login-to-registration button").click(function (e) {
        e.preventDefault();
        $("#login-modal").fadeOut(300);
        $("#registration").fadeIn(300);
    });
})