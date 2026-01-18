var apublication_execute = function(params){ 
var prm = {
            class_id: '.photo_line-main',

    		autoplay: params.autoplay || 0,  
    		autoplayStopOnLast: params.autoplayStopOnLast || false,	
			autoplayDisableOnInteraction: params.autoplayDisableOnInteraction || false, 
			speed: params.speed || 300, 

			fp_spaceBetween: params.fp_spaceBetween || 3,
            fp_effect: params.fp_effect || 'slide',

			tp_slidesPerView: params.tp_slidesPerView || 'auto',
            tp_spaceBetween: params.tp_spaceBetween || 10
    	}

		var galleryTop = new Swiper(prm.class_id+' .photo_line-full_image_gallery', {
			nextButton: prm.class_id+' .photo_line-full_image_gallery .swiper-button-next',
			prevButton: prm.class_id+' .photo_line-full_image_gallery .swiper-button-prev',

			speed: prm.speed,
			spaceBetween: prm.fp_spaceBetween,
			autoplay: prm.autoplay,
			autoplayStopOnLast:  prm.autoplayStopOnLast,	
			autoplayDisableOnInteraction: prm.autoplayDisableOnInteraction,
			effect: prm.fp_effect,

			onTransitionStart: function(){
				var index = galleryTop.activeIndex;
				if (index != undefined){
					galleryThumbs.slideTo(index);
					$(galleryThumbs.slides).removeClass('swiper-slide-selected');
					$(galleryThumbs.slides[index]).addClass('swiper-slide-selected');
				}
			},
		});
        
		var galleryThumbs = new Swiper(prm.class_id+' .photo_line-thumbs_gallery', {
			spaceBetween: prm.tp_spaceBetween,

			speed: prm.speed,
			slidesPerView: prm.tp_slidesPerView,

			nextButton: prm.class_id+' .photo_line-thumbs_gallery .swiper-button-next',
			prevButton: prm.class_id+' .photo_line-thumbs_gallery .swiper-button-prev',
			scrollbar: prm.class_id+' .photo_line-thumbs_gallery .swiper-scrollbar',
			scrollbarHide: false,
			scrollbarDraggable: true,

			onTap: function(){
				var index = galleryThumbs.clickedIndex;
				if (index != undefined){
					galleryTop.slideTo(index);
				}
			}

		});

		$(galleryThumbs.slides[0]).addClass('swiper-slide-selected');
}
