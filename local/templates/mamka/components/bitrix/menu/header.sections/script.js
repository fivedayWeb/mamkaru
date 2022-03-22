$(document).ready(function () {
    var t;
    $(".dropdown-show").mouseenter(function () {
        clearTimeout(t);
        t = setTimeout(function () {
            $(".dropdown-menu").addClass("active")
        }, 200);
    }); 
    $(".dropdown-menu").mouseenter(function () {
        clearTimeout(t);
        t = setTimeout(function () {
            $(".dropdown-menu").addClass("active")
        }, 200);
    }); 
    $(".dropdown-menu").mouseleave(function () {
        clearTimeout(t);
        t = setTimeout(function () {
            $(".dropdown-menu").removeClass("active")
        }, 200);
    }); 
    $(".dropdown-show").mouseleave(function () {
        clearTimeout(t);
        t = setTimeout(function () {
            $(".dropdown-menu").removeClass("active")
        }, 1000);
    }); 
});