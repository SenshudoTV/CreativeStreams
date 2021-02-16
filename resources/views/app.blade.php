<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#" class="h-100">
    <head>
        <base href="/" />
        <link rel="preconnect" href="//www.gstatic.com" />
        <link rel="preconnect" href="//connect.facebook.net" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="fragment" content="!">
        <title>Creative Streams</title>
        <link rel="preload" href="{{ mix('/fonts/RB-L-700.woff2') }}" as="font" type="font/woff2" crossorigin>
		<link rel="preload" href="{{ mix('/fonts/RB-LE-700.woff2') }}" as="font" type="font/woff2" crossorigin>
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />
        <script src="{{ mix('/js/runtime.js') }}" defer type="text/javascript"></script>
        <script src="{{ mix('/js/vendors.js') }}" defer type="text/javascript"></script>
        <script src="{{ mix('/js/app.js') }}" defer type="text/javascript"></script>
    </head>

    <body class="h-100 app-light">
        @inertia
    </body>
</html>
