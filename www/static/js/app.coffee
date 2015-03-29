$ ->
	# nette.ajax.js
	$.nette.ext 'spinner',
		start: (xhr, settings) ->
			if settings.nette and settings.nette.el.is '[data-ajax-spinner]'
				settings.nette.el.parents('.spinnerWrap').append('<div class="spinnerOverlay"></div><div class="spinner"></div>')
		complete: (xhr, status, settings) ->
			if settings.nette && settings.nette.el.is '[data-ajax-spinner]'
				settings.nette.el.parents('.spinnerWrap').find('.spinnerOverlay, .spinner').remove()
				return

	$.nette.init()

	# confirm dialog
	$(document).on 'click', '[data-confirm]', (e) ->
		if ! confirm $(this).data 'confirm'
			e.preventDefault()

	return
