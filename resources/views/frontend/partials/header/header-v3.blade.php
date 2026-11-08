@if ($basicInfo->preloader_status == 1)
    <!-- Preloader start -->
    <div id="preLoader">
        <div class="loader">
            <svg viewBox="0 0 80 80">
                <rect x="8" y="8" width="64" height="64"></rect>
            </svg>
            <div class="icon"><img src="{{ asset('assets/img/' . $basicInfo->preloader) }}"></div>
        </div>
    </div>
@endif
<!-- Preloader end -->
<div class="request-loader">
    <img src="{{ asset('assets/img/loaders.gif') }}">
</div>

<header class="header-area header-2 @if (!request()->routeIs('index')) header-static @endif">
    <!-- Start mobile menu -->
    <div class="mobile-menu">
        <div class="container">
            <div class="mobile-menu-wrapper"></div>
        </div>
    </div>
    <!-- End mobile menu -->

    <div class="main-responsive-nav">
        <div class="container">
            <!-- Mobile Logo -->
            <div class="logo">
                @if (!empty($websiteInfo->logo))
                    <a href="{{ route('index') }}">
                        <img src="{{ asset('assets/img/' . $websiteInfo->logo) }}" alt="RAFAIMMOBILIER">
                    </a>
                @endif
            </div>
            <!-- Menu toggle button -->
            <button class="menu-toggler" type="button" aria-label="{{ __('Toggle navigation') }}" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
    <div class="main-navbar">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <!-- Logo -->

                @if (!empty($websiteInfo->logo))
                    <a href="{{ route('index') }}" class="navbar-brand">
                        <img src="{{ asset('assets/img/' . $websiteInfo->logo) }}" alt="RAFAIMMOBILIER">
                    </a>
                @endif
                <!-- Navigation items -->
                <div class="collapse navbar-collapse">
                    <ul id="mainMenu" class="navbar-nav mobile-item mx-auto">
                        <li class="nav-item"><a class="nav-link" href="{{ route('frontend.properties', ['purpose' => 'rent']) }}">{{ __('Rent') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('frontend.properties', ['purpose' => 'sale']) }}">{{ __('Buy') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('frontend.projects') }}">{{ __('New developments') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">{{ __('Contact') }}</a></li>
                    </ul>
                </div>
                <div class="more-option mobile-item">
                    @include('frontend.partials.workspace-link')

                    <div class="item">
                        <div class="language">
                            <form action="{{ route('change_language') }}" method="GET">
                                <select class="nice-select" name="lang_code" aria-label="{{ __('Language') }}" onchange="this.form.submit()">
                                    @foreach ($allLanguageInfos as $languageInfo)
                                        <option value="{{ $languageInfo->code }}"
                                            {{ $languageInfo->code == $currentLanguageInfo->code ? 'selected' : '' }}>
                                            {{ $languageInfo->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="item">
                        <div class="dropdown">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                @if (!Auth::guard('web')->check())
                                    {{ __('My account') }}
                                @else
                                    {{ Auth::guard('web')->user()->username }}
                                @endif
                            </button>
                            <ul class="dropdown-menu radius-0">
                                @if (!Auth::guard('web')->check())
                                    <li><a class="dropdown-item"
                                            href="{{ route('user.login') }}">{{ __('Login') }}</a></li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('user.signup') }}">{{ __('Signup') }}</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('vendor.login') }}">{{ __('Professional login') }}</a></li>
                                @else
                                    <li><a class="dropdown-item"
                                            href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a></li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('user.logout') }}">{{ __('Logout') }}</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="item">
                        <a class="rafa-publish-link" href="{{ Auth::guard('vendor')->check() ? route('vendor.property_management.create_property') : route('vendor.signup') }}"><i class="fas fa-plus" aria-hidden="true"></i> {{ __('Publish a property') }}</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>
<!-- Header-area end -->
