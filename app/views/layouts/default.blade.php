<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
      @section('title')
        Getting Stuff Done
      @show
    </title>
    @section('styles')
      {{ HTML::style('/css/bootstrap.min.css') }}
      {{ HTML::style('/css/bootstrap-theme.min.css') }}
      {{ HTML::style('/css/gsd.css') }}
    @show
    @section('scripts')
      {{ HTML::script('/js/jquery.js') }}
      {{ HTML::script('/js/bootstrap.min.js') }}
      {{ HTML::script('/js/gsd.js') }}
    @show
  </head>
  <body>
    @include("partials.notifications")
    @yield('content')
  </body>
</html>