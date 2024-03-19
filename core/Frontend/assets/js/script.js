jQuery(document).ready(function ($) {
	if ($('.sp-smart-brand-carousel-wrapper').length > 0) {
		$('.sp-smart-brand-carousel-wrapper').each(function () {
			var brand_container = $(this);
			var brand_container_id = brand_container.attr("id"),
				brand_Wrapper_ID = '#' + brand_container_id,
				brandSwiper,
				brandCarousel = $(brand_Wrapper_ID + " .sp-smart-brand-carousel"),
				brandCarouselData = brandCarousel.data("carousel");

			brandSwiper = new Swiper(brand_Wrapper_ID + " .sp-smart-brand-carousel:not(.swiper-initialized)", {
				autoplay: brandCarouselData.autoplay ? {
					delay: brandCarouselData.autoplay_speed
				} : false,
				speed: brandCarouselData.sliding_speed,
				loop: brandCarouselData.infinite_loop,
				freeMode: brandCarouselData.free_mode,
				spaceBetween: brandCarouselData.spaceBetween,
				grabCursor: true,
				breakpoints: {
					320: {
						slidesPerView: brandCarouselData.slidesPerView.mobile,
						slidesPerGroup: brandCarouselData.slideToScroll.mobile,
					},
					600: {
						slidesPerView: brandCarouselData.slidesPerView.tablet,
						slidesPerGroup: brandCarouselData.slideToScroll.tablet
					},
					768: {
						slidesPerView: brandCarouselData.slidesPerView.laptop,
						slidesPerGroup: brandCarouselData.slideToScroll.laptop
					},
					992: {
						slidesPerView: brandCarouselData.slidesPerView.desktop,
						slidesPerGroup: brandCarouselData.slideToScroll.desktop
					},
					1200: {
						slidesPerView: brandCarouselData.slidesPerView.large_desktop,
						slidesPerGroup: brandCarouselData.slideToScroll.large_desktop,
					}
				},
				navigation: {
					nextEl: brand_Wrapper_ID + ' .swiper-button-next',
					prevEl: brand_Wrapper_ID + ' .swiper-button-prev',
				},
				pagination: {
					el: brand_Wrapper_ID + ' .swiper-pagination',
					type: 'bullets',
					clickable: true
				},
			});

			if (brandCarouselData.autoplay && brandCarouselData.pause_on_hover) {
				$(brandCarousel).on({
					mouseenter: function () {
						(this).swiper.autoplay.stop();
					},
					mouseleave: function () {
						(this).swiper.autoplay.start();
					}
				});
			}
		});
	}
	/* Preloader js */
	$('.sp-smart-brand-carousel-wrapper').each(function () {
		var brand_container_id = $(this).attr("id");
		$('#' + brand_container_id + " .sp-smart-brand-section-preloader").css(
			"visibility", "hidden",
			"backgroundImage", "none",
		);
	})
	$(document).find(".wp-block .sp-smart-brand-wrapper").addClass("sp-smart-brand-wrapper-loaded");
});