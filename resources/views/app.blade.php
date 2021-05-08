<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#" class="h-100">
    <head>
        <base href="/" />
        <link rel="preconnect" href="//www.gstatic.com" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Creative Streams</title>
        <link rel="canonical" href="https://www.creativestreams.tv" />
        <meta name="description" content="Twitch Creative Streams Hashtags are back! Find and be found on Twitch with whatever hashtags you care to use." />
		<meta name="keywords" content="Creative Streams,Creative,Streams,Twitch,Art,Beauty & Body Art, Food & Drink,Markers & Crafting,Music & Performing Arts,Science & Technology,Talk Shows & Podcasts" />
		<meta name="robots" content="INDEX, FOLLOW">
		<meta name="author" content="{{ config('app.name') }}" />
        <meta property="fb:pages" content="150154848416324" />
        <meta property="og:site_name" content="{{ config('app.name') }}"/>
		<meta property="og:locale" content="en_GB"/>
		<meta property="og:title" content="Creative Streams"/>
		<meta property="og:description" content="Twitch Creative Streams Hashtags are back! Find and be found on Twitch with whatever hashtags you care to use."/>
		<meta property="og:url" content="https://www.creativestreams.tv"/>
		<meta property="og:image" content="https://cdn.creativestreams.tv/images/CS.jpg" />
		<meta name="twitter:title" content="Creative Streams">
		<meta name="twitter:description" content="Twitch Creative Streams Hashtags are back! Find and be found on Twitch with whatever hashtags you care to use.">
		<meta name="twitter:image" content="https://cdn.creativestreams.tv/images/CS.jpg">
        <meta property="og:type" content="website" />
		<meta name="twitter:card" content="summary">
        <link rel="preload" href="{{ asset('/fonts/RB-L-700.woff2') }}" as="font" type="font/woff2" crossorigin>
		<link rel="preload" href="{{ asset('/fonts/RB-LE-700.woff2') }}" as="font" type="font/woff2" crossorigin>
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />
        <script src="{{ mix('/js/manifest.js') }}" defer type="text/javascript"></script>
        <script src="{{ mix('/js/vendor.js') }}" defer type="text/javascript"></script>
        <script src="{{ mix('/js/app.js') }}" defer type="text/javascript"></script>
    </head>

    <body class="h-100 app-light">
        @inertia
    </body>
</html>
