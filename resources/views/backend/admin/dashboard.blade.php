@extends('backend.layout')

@section('content')
    @include('partials.rafa-overview', [
        'workspace' => __('Admin'),
        'displayName' => $authAdmin->first_name ?: $authAdmin->username,
        'description' => __('Your business at a glance. Manage your community and track activity in one place.'),
        'actionUrl' => route('admin.edit_profile'),
        'actionLabel' => __('Edit profile'),
    ])
    <div class="rafa-section-heading">
        <h2>{{ __('Overview') }}</h2>
        <span>{{ __('Your workspace at a glance') }}</span>
    </div>

    {{-- dashboard information start --}}
    @php
        if (!is_null($roleInfo)) {
            $rolePermissions = json_decode($roleInfo->permissions);
        }
    @endphp

    <div class="row dashboard-items rafa-admin-metrics">
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Total Earning', $rolePermissions)))
            <div class="col-md-3">
                <a href="{{ route('admin.payment-log.index') }}">
                    <div class="card card-stats card-primary card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-money-check-alt"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">{{ __('Payment Logs') }}</p>
                                        <h4 class="card-title">{{ $payment_log }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif




        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Blog Management', $rolePermissions)))
            <div class="col-sm-6 col-md-3">
                <a href="{{ route('admin.blog_management.blogs', ['language' => $defaultLang->code]) }}">
                    <div class="card card-stats card-info card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fal fa-blog"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">{{ __('Blog') }}</p>
                                        <h4 class="card-title">{{ $totalBlog }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Vendors Management', $rolePermissions)))
            <div class="col-md-3">
                <a href="{{ route('admin.vendor_management.registered_vendor') }}">
                    <div class="card card-stats card-secondary card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">{{ __('Vendors') }}</p>
                                        <h4 class="card-title">
                                            {{ $vendors }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('User Management', $rolePermissions)))
            <div class="col-sm-6 col-md-3">
                <a href="{{ route('admin.user_management.registered_users') }}">
                    <div class="card card-stats card-orchid card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="la flaticon-users"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">{{ __('Users') }}</p>
                                        <h4 class="card-title">{{ $totalUser }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('User Management', $rolePermissions)))
            <div class="col-sm-6 col-md-3">
                <a href="{{ route('admin.user_management.subscribers') }}">
                    <div class="card card-stats card-dark card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fal fa-bell"></i>
                                    </div>
                                </div>

                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">{{ __('Subscribers') }}</p>
                                        <h4 class="card-title">{{ $totalSubscriber }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ __('Monthly Package Purchase') }} ({{ date('Y') }})</div>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="packagePurchaseChart" role="img" aria-label="{{ __('Monthly activity chart') }}"></canvas>
                    </div>
                        <p class="rafa-chart-empty" hidden>{{ __('No activity yet this year. New activity will appear here.') }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ __('Month wise registered users') }} ({{ date('Y') }})</div>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="userChart" role="img" aria-label="{{ __('Monthly activity chart') }}"></canvas>
                    </div>
                        <p class="rafa-chart-empty" hidden>{{ __('No activity yet this year. New activity will appear here.') }}</p>
                </div>
            </div>
        </div>
    </div>
    {{-- dashboard information end --}}
@endsection

@section('script')
    {{-- chart js --}}
    <script type="text/javascript" src="{{ asset('assets/js/chart.min.js') }}"></script>

    <script>
        "use strict";
        const monthArr = @php echo json_encode($monthArr) @endphp;
        const packagePurchaseIncomesArr = @php echo json_encode($packagePurchaseIncomesArr) @endphp;
        const totalUsersArr = @php echo json_encode($totalUsersArr) @endphp;
    </script>

    <script type="text/javascript" src="{{ asset('assets/js/chart-init.js') }}"></script>
@endsection
