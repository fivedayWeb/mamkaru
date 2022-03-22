$(function(){
    $('#brands-header #brands-sort a').on('click',function(){
        if($(this).hasClass('active')) return false;
        $('.bx-brands').toggleClass('active');
        $('#brands-header #brands-sort a').toggleClass('active');
        return false;
    });

    $('#brands-search').on('keyup',function(){
        var searchStr = $(this).val().toLowerCase();
        $('.brands_alphabet li a').each(function () {
            var text = $(this).html().toLowerCase();
            if(text.indexOf(searchStr) == -1)
                $(this).hide();
            else
                $(this).show();

        });
        $('.brands-logos a .brand-logo-title').each(function () {
            var text = $(this).html().toLowerCase();
            if(text.indexOf(searchStr) == -1)
                $(this).parent().hide();
            else
                $(this).parent().show();

        })
        return false;
    });
    $("#sidebar-categories-open").click(function () {
        $(this).toggleClass("active").next().slideToggle(300);
    });
})