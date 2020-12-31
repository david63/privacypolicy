;(function($, document)
{
	'use strict';

	$(document).ready(function ()
	{
		$('link[href*="boxes.css"]').prop('disabled', true);
		$('link[href*="buttons.css"]').prop('disabled', true);
		$('link[href*="input.css"]').prop('disabled', true);
		$('link[href*="pagination.css"]').prop('disabled', true);
		$('link[href*="panels.css"]').prop('disabled', true);
	});
})(jQuery, document);
