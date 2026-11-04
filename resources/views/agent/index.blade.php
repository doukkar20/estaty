@extends('agent.layout')

@section('content')
    @include('partials.rafa-overview', [
        'workspace' => __('Agent'),
        'displayName' => Auth::guard('agent')->user()->username,
        'description' => __('Your properties and projects, together in one workspace.'),
        'actionUrl' => route('agent.property_management.properties', ['language' => $defaultLang->code]),
        'actionLabel' => __('Manage properties'),
    ])
    <div class="rafa-section-heading">
        <h2>{{ __('Overview') }}</h2>
        <span>{{ __('Your workspace at a glance') }}</span>
    </div>
     
    {{-- dashboard information start --}}
    <div class="row dashboard-items">
        <div class="col-sm-6 col-md-4">
            <a href="{{ route('agent.property_management.properties', ['language' => $defaultLang->code]) }}">
                <div class="card card-stats card-success card-round">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="far fa-home"></i>
                                </div>
                            </div>

                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">{{ __('Properties') }}</p>
                                    <h4 class="card-title">{{ $totalProperties }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-md-4">
            <a href="{{ route('agent.project_management.projects', ['language' => $defaultLang->code]) }}">
                <div class="card card-stats card-secondary card-round">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="far fa-city"></i>
                                </div>
                            </div>

                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">{{ __('Projects') }}</p>
                                    <h4 class="card-title">{{ $totalProjects }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
 
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ __('Monthly Property Posts') }} ({{ date('Y') }})</div>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="CarChart" role="img" aria-label="{{ __('Monthly activity chart') }}"></canvas>
                    </div>
                        <p class="rafa-chart-empty" hidden>{{ __('No activity yet this year. New activity will appear here.') }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ __('Monthly Projects Post') }} ({{ date('Y') }})</div>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="visitorChart" role="img" aria-label="{{ __('Monthly activity chart') }}"></canvas>
                    </div>
                        <p class="rafa-chart-empty" hidden>{{ __('No activity yet this year. New activity will appear here.') }}</p>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    {{-- chart js --}}
    <script type="text/javascript" src="{{ asset('assets/js/chart.min.js') }}"></script>

    <script>
        "use strict";
        const monthArr = @php echo json_encode($monthArr) @endphp;
        const totalPropertyArr = @php echo json_encode($totalPropertiesArr) @endphp;
        const totalProjectsArr = @php echo json_encode($totalProjectsArr) @endphp;
    </script>

    <script type="text/javascript" src="{{ asset('assets/js/vendor-chart-init.js') }}"></script>
@endsection
