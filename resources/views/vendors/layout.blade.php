<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  {{-- required meta tags --}}
  <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  {{-- csrf-token for ajax request --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- title --}}
  <title>{{ __('Vendor') . ' | ' . $websiteInfo->website_title }}</title>

  {{-- fav icon --}}
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">

  {{-- include styles --}}
  @includeIf('vendors.partials.styles')

  {{-- additional style --}}
  <link rel="stylesheet" href="{{ asset('assets/css/rafa-dashboard.css') }}">
  @yield('style')
</head>

<body class="rafa-dashboard" data-background-color="{{ Session::get('vendor_theme_version', 'light') == 'light' ? 'white2' : 'dark' }}">
  {{-- loader start --}}
  <div class="request-loader">
    <img src="{{ asset('assets/img/loader.gif') }}" alt="loader">
  </div>
  {{-- loader end --}}

  <a class="rafa-skip-link" href="#rafa-main">{{ __('Skip to content') }}</a>
  <div class="wrapper">
    {{-- top navbar area start --}}
    @includeIf('vendors.partials.top-navbar')
    {{-- top navbar area end --}}

    {{-- side navbar area start --}}
    @includeIf('vendors.partials.side-navbar')
    {{-- side navbar area end --}}

    <div class="main-panel">
      <div class="content">
        <div class="page-inner" id="rafa-main" role="main" tabindex="-1">
          @yield('content')
        </div>
      </div>

      {{-- footer area start --}}
      @includeIf('vendors.partials.footer')
      {{-- footer area end --}}
    </div>
  </div>

  {{-- include scripts --}}
  @includeIf('vendors.partials.scripts')

  {{-- additional script --}}
  <script src="{{ asset('assets/js/rafa-dashboard.js') }}"></script>
  @yield('variables')
  @yield('script')
</body>

</html>
