<?php

header('HTTP/1.1 503 Service Unavailable', NULL, 503);
header('Retry-After: 60'); // 1 minutes in seconds
header('Content-Type: text/html');

?>

<!doctype html>
<html lang="cs">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width">
	<meta property="fb:admins" content="1625947532">
	<title>Jiří Pudil, brněnský herec s rýmy</title>
	<link rel="shortcut icon" href="/favicon-32.png">
	<link rel="alternate" type="application/rss+xml" href="/rss">
	<link rel="stylesheet" href="/static/css/new.css">
	<script src="/static/js/scripts.js" async></script>
</head>
<body>
<div class="container">
	<!-- Google Tag Manager -->
	<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-WLM5XK"
	                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<script n:syntax="off">(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-WLM5XK');</script>
	<!-- End Google Tag Manager -->

	<div class="header">
		<span class="header-intro">Ahoj, jsem</span>
		<div class="header-name">Jiří Pudil</div>
		<p class="header-motto">brněnský herec s&nbsp;rýmy</p>
	</div>

	<div class="sep cf"></div>

	<h1>Rozsypala se mi slova,<br>rychle ale hledám nová</h1>
	<p class="lede">Zrovinka na webu dělám nějaké drobné úpravy, aby byl ještě lepší. Mějte prosím minutku strpení a potom to zkuste znovu.</p>
</div>
</body>
</html>
