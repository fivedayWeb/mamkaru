$(function(){
	$("#search-button").click(function () {
        $("#search-form input").toggleClass("active");
        $("#main-nav li").toggleClass("removed");
        $(this).toggleClass("active");
        focusTimeout = setTimeout(function () {
            $("#search-form input").focus();
        }, 150);
    });
});