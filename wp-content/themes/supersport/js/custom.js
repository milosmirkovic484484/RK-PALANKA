(function ($) {
	'use strict';

	var $gutter = 0, supersport = {

		initReady: function() {


			this.searchShow();
			this.fbLikeBox();

		},

		searchShow: function() {
			$('#trigger-overlay').on('click', function(e) {
				e.preventDefault();
				e.stopPropagation();
				$('.overlay-slideleft').toggleClass('show').find('input').focus();
			});
			$('.overlay-close').on('click', function(e){
				e.preventDefault();
				e.stopPropagation();
				$('.overlay-slideleft').removeClass('show');
			});
			$(document).on('click', function(e){
				$('.overlay-slideleft').removeClass('show');
			});
			$('.overlay-slideleft').click(function(e){
				e.preventDefault();
				e.stopPropagation();
			});

		},
		fbLikeBox: function() {

			(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.9&appId=1615727725316685";
			fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
			
		}
	}

	$(document).ready(function () {
		supersport.initReady();
	});

})(jQuery);