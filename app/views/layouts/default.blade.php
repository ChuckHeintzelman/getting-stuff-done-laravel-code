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
      <script type="text/javascript">
        gsd.defaultList = "{{ $default_list }}";
      </script>
    @show
  </head>
  <body>
    @include("partials.topnavbar")
    @include("partials.notifications")

    <div class="container">
      <div class="row">
        <div class="col-md-3">

          @include('partials.sidebar')

        </div>
        <div class="col-md-9">
          @yield('content')
        </div>
      </div>
    </div>
    @include('partials.taskmodal')
    @include('partials.listmodal')
  </body>
</html>