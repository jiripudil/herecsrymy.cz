$ ->
	# nette.ajax.js
	$.nette.ext 'spinner',
		start: (xhr, settings) ->
			if settings.nette and settings.nette.el.is '[data-ajax-spinner]'
				settings.nette.el.parents('.spinnerWrap').append('<div class="spinnerOverlay"></div><div class="spinner"></div>')
			if settings.spinner
				$(settings.spinner).parents('.spinnerWrap').append('<div class="spinnerOverlay"></div><div class="spinner"></div>')
		complete: (xhr, status, settings) ->
			if settings.nette && settings.nette.el.is '[data-ajax-spinner]'
				settings.nette.el.parents('.spinnerWrap').find('.spinnerOverlay, .spinner').remove()
			if settings.spinner
				$(settings.spinner).parents('.spinnerWrap').find('.spinnerOverlay, .spinner').remove()

	$.nette.init()

	# confirm dialog
	$(document).on 'click', '[data-confirm]', (e) ->
		if ! confirm $(this).data 'confirm'
			e.preventDefault()

	# webfont loader
	window.WebFontConfig =
		google:
			families: [
				'PT Sans:400,700:latin,latin-ext'
				'Lora:400,700:latin,latin-ext'
			]
		custom:
			families: 'FontAwesome'
			urls: ['https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css']
		timeout: 2000

	((d) ->
		wf = d.createElement 'script'
		s = d.scripts[0]
		wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1.5.18/webfont.js'
		s.parentNode.insertBefore wf, s
		return
	) document

	return
