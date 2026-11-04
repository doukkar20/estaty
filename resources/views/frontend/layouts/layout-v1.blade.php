<!DOCTYPE html>
<html lang="{{ $currentLanguageInfo->code }}" dir="{{ $currentLanguageInfo->direction == 1 ? 'rtl' : '' }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="KreativDev">

    <meta name="keywords" content="@yield('metaKeywords')">
    <meta name="description" content="@yield('metaDescription')">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta property="og:type" content="website">
    @yield('og:tag')
    {{-- title --}}
    <title>@yield('pageHeading') {{ '| ' . $websiteInfo->website_title }}</title>
    {{-- fav icon --}}
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">
@includeIf('frontend.partials.styles.styles-v1')
    @include('frontend.partials.brand-colors')
    <link rel="stylesheet" href="{{ asset('assets/css/rafa-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/rafa-home.css') }}">
</head>


<body class="rafa-frontend {{ request()->routeIs('index') ? 'rafa-home-page' : '' }} {{ $basicInfo->whatsapp_status == 1 ? 'rafa-has-chat' : '' }}" dir="{{ $currentLanguageInfo->direction == 1 ? 'rtl' : '' }}">
    @includeIf('frontend.partials.header.header-v1')

    @if (request()->routeIs('index'))
    @endif

    @yield('breadcrumb')

    @yield('content')

    @includeIf('frontend.partials.popups')
    @includeIf('frontend.partials.footer.footer-v1')
    {{-- cookie alert --}}
    @if (!is_null($cookieAlertInfo) && $cookieAlertInfo->cookie_alert_status == 1)
        @include('cookie-consent::index')
    @endif




    <!-- WhatsApp Chat Button -->
    <div id="WAButton"></div>

    @include('frontend.partials.back-to-top')
    @includeIf('frontend.partials.scripts.scripts-v1')
    @includeIf('frontend.partials.toastr')
    <script src="{{ asset('assets/js/rafa-navigation.js') }}"></script>
</body>

</html>
