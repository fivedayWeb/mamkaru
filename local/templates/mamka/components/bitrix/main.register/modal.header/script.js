$(function(){
    $(".registration-to-login button").click(function (e) {
        e.preventDefault();
        $("#registration").fadeOut(300);
        $("#login-modal").fadeIn(300);
    });
});