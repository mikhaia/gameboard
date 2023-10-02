<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link rel="apple-touch-icon" sizes="128x128" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="icon" sizes="128x128" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/js/app.js', 'resources/css/app.styl'])
    @inertiaHead
  </head>
  <body>
    @inertia
  </body>
</html>