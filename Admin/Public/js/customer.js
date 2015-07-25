function errorTipFadeIn(tip) {
	if (tip == null) {
		tip = "";
	}
	$('.error-tip').text(tip);
	$('.error-tip').fadeIn(500, errorTipAutoFadeOut);
}

function errorTipAutoFadeOut() {
	setTimeout(function() {
		$('.error-tip').fadeOut(500);
	}, 2000);
}