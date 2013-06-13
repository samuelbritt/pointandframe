/* Loads the PhotoSwipe functionality for all galleries and each post */
jQuery(document).ready(function($) {
	options = {
		captionAndToolbarShowEmptyCaptions: false,
		enableKeyboard: true,
		enableMouseWheel: true,
		imageScaleMethod: 'fitNoUpscale',
		loop: true,
		nextPreviousSlideSpeed: 250,
		slideshowDelay: 3000,
		swipeThreshold: 30,
		swipeTimeThreshold: 400
	};

	var imgfilter = function() {
		return /jpe?g$|png$/i.test(this.href);
	};

	if ($(".gallery a").length ) {
		$(".gallery").each(function(){
			$(this).find(".gallery-item a").photoSwipe(options);
		});
	} else if ($(".post").length) {
		$(".post").each(function(index) {
			// add any images with a caption, and any bare images being aligned by wordpress
			var images = $(this).find('.wp-caption a').filter(imgfilter)
				.add($(this).find('a').filter(imgfilter).has('img[class^=align]'));
			if (images.length > 0) {
				images.photoSwipe(options);
			}
		});
	}
});
