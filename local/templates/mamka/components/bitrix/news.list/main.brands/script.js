$(function(){
	if ($('.home-brand-card').length > 6) {
		$('.home-brands-cards').owlCarousel({
            nav: true,
            loop: true,
            items: 6,
            autoplay: false,
            navText: ["", ""],
            lazyLoad: true,
            dots: false,
            responsive : {
		    0: {
		        items: 2,
		    },
		    768 : {
		    	items: 3,
		    },
		    992 : {
		    	items: 6,
		    }
		}
        })
	}
});