; (function ($) {
	const hash = window.location.hash.replace('#', '');
	// Help page tab menu script.
	$('.sp-smart-brand-help').on('click', '.spsbf-header-nav-menu a', function(e) {
		if( $(this).hasClass('active') ) {
			return;
		}
		let tabId = $(this).attr('data-id');
		$('.spsbf-header-nav-menu a').each((i, item) => {
			$(item).removeClass('active');
			$('#' + $(item).attr('data-id')).css('display', 'none');
		})
		$(this).addClass('active');

		$('#' + tabId).css('display', 'block');

		if('recommended-tab' === tabId){
			$('#menu-posts-smart_brand_sc ul li').each((i, item) => {
				$(item).removeClass('current');
			})
			$('#menu-posts-smart_brand_sc ul li a[href="edit.php?post_type=smart_brand_sc&page=brand_help#recommended"]').parent().addClass('current');
		}
		if('lite-to-pro-tab' === tabId){
			$('#menu-posts-smart_brand_sc ul li').each((i, item) => {
				$(item).removeClass('current');
			})
			$('#menu-posts-smart_brand_sc ul li a[href="edit.php?post_type=smart_brand_sc&page=brand_help#lite-to-pro"]').parent().addClass('current');
		}
		if(('get-start-tab' === tabId || 'about-us-tab' === tabId)){
			$('#menu-posts-smart_brand_sc ul li').each((i, item) => {
				$(item).removeClass('current');
			})
			$('#menu-posts-smart_brand_sc ul li:last-child').addClass('current');
		}
	})

	$('#menu-posts-smart_brand_sc').on('click', 'ul li a', (e) => {
		var element = e.target.closest('a');

		if( 'edit.php?post_type=smart_brand_sc&page=brand_help' === $(element).attr('href') ) {
			$(element).attr('href', '#get-start');
		}

		setTimeout(() => {
			var hashValue = window.location.hash.replace('#', '');
			if(hashValue) {
				$('#menu-posts-smart_brand_sc ul li').each((i, item) => {
					$(item).removeClass('current');
				})
			}
			if( 'recommended' === hashValue ) {
				$('.sp-smart-brand-help .spsbf-header-nav-menu a[data-id="recommended-tab"]').trigger('click');
				$(element).parent().addClass('current')
			}
			if( 'lite-to-pro' === hashValue ) {
				$('.sp-smart-brand-help .spsbf-header-nav-menu a[data-id="lite-to-pro-tab"]').trigger('click');
				$(element).parent().addClass('current')
			}
			if( 'get-start' === hashValue ) {
				$('.sp-smart-brand-help .spsbf-header-nav-menu a[data-id="get-start-tab"]').trigger('click');
				$(element).parent().addClass('current')
			}
		}, 0);
	})

	if( 'recommended' === hash ) {
		$('.sp-smart-brand-help .spsbf-header-nav-menu a[data-id="recommended-tab"]').trigger('click');
		$('#menu-posts-smart_brand_sc ul li:last-child').removeClass('current');
		$('#menu-posts-smart_brand_sc ul li a[href="edit.php?post_type=smart_brand_sc&page=brand_help#recommended"]').parent().addClass('current');
	}
	if( 'lite-to-pro' === hash ) {
		$('.sp-smart-brand-help .spsbf-header-nav-menu a[data-id="lite-to-pro-tab"]').trigger('click');
		$('#menu-posts-smart_brand_sc ul li:last-child').removeClass('current');
		$('#menu-posts-smart_brand_sc ul li a[href="edit.php?post_type=smart_brand_sc&page=brand_help#lite-to-pro"]').parent().addClass('current');
	}
	if( 'about-us' === hash ) {
		$('.sp-smart-brand-help .spsbf-header-nav-menu a[data-id="about-us-tab"]').trigger('click');
	}
	
	$('body').on('click', '.install-now', function(e) {
		var _this = $(this);
		var _href = _this.attr('href');
	
		_this.addClass('updating-message').html('Installing...');
	
		$.get(_href, function(data) {
		  location.reload();
		});
	
		e.preventDefault();
	});

})(jQuery);