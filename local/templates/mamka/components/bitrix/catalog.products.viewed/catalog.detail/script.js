$(function(){
    if ($(".catalog_viewed_products_catalog_detail>*").length > 1)
    {
        $(".catalog_viewed_products_catalog_detail").owlCarousel({
            nav: true,
            loop: true,
            autoplay: false,
            margin: 20,
            navText: ["", ""],
            lazyLoad: true,
            responsive: {
                0: {
                    items: 1,
                },
                767: {
                    items: 2,
                },
                1024: {
                    items: 4,
                }
            }
        });
    }
});