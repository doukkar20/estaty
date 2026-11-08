@php
    $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
    {{ __('Dashboard') }}
@endsection


@section('content')
<div class="user-dashboard pt-100 pb-60">
        <div class="container">
            @include('partials.rafa-overview', [
                'workspace' => __('My account'),
                'displayName' => $authUser->name ?: $authUser->username,
                'description' => __('Keep your favorite properties close and find your next place with confidence.'),
                'actionUrl' => route('frontend.properties'),
                'actionLabel' => __('Explore properties'),
            ])
            <div class="row gx-xl-5">
                @includeIf('frontend.user.side-navbar')
                <div class="col-lg-9">
                    @include('partials.rafa-quick-start', ['audience' => 'customer'])
                    @php
                        $profileFields = ['name', 'email', 'phone', 'city', 'country', 'address'];
                        $completedFields = collect($profileFields)->filter(function ($field) use ($authUser) {
                            return filled($authUser->{$field});
                        })->count();
                    @endphp
                    <div class="rafa-account-summary">
                        <a class="rafa-summary-card" href="{{ route('user.wishlist') }}">
                            <i class="fas fa-heart" aria-hidden="true"></i>
                            <div><strong>{{ $wishlistContents->count() }}</strong><span>{{ __('Saved properties') }}</span></div>
                        </a>
                        <a class="rafa-summary-card" href="{{ route('user.edit_profile') }}">
                            <i class="fas fa-user" aria-hidden="true"></i>
                            <div><strong>{{ round($completedFields / count($profileFields) * 100) }}%</strong><span>{{ __('Profile complete · Edit profile') }}</span></div>
                        </a>
                    </div>
                    <div class="user-profile-details mb-30">
                        <div class="account-info radius-md">
                            <div class="title">
                                <h4>{{ __('Account Information') }}</h4>
                            </div>
                            <div class="main-info">
                                <ul class="list">
                                    <li><span>{{ __('Name') . ':' }}</span> <span>{{ $authUser->name ?: __('Not provided') }}</span></li>
                                    <li><span>{{ __('Username') . ':' }}</span> <span>{{ $authUser->username }}</span></li>
                                    <li><span>{{ __('Email') . ':' }}</span> <span>{{ $authUser->email }}</span></li>
                                    <li><span>{{ __('Phone') . ':' }}</span> <span>{{ $authUser->phone ?: __('Not provided') }}</span></li>
                                    <li><span>{{ __('City') . ':' }}</span> <span>{{ $authUser->city ?: __('Not provided') }}</span></li>
                                    <li><span>{{ __('Country') . ':' }}</span> <span>{{ $authUser->country ?: __('Not provided') }}</span></li>
                                    <li><span>{{ __('State') . ':' }}</span> <span>{{ $authUser->state ?: __('Not provided') }}</span></li>
                                    <li><span>{{ __('Zip Code') . ':' }}</span> <span>{{ $authUser->zip_code ?: __('Not provided') }}</span>
                                    </li>
                                    <li><span>{{ __('Address') . ':' }}</span> <span>{{ $authUser->address ?: __('Not provided') }}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="account-info radius-md mb-40">
                        <div class="title">
                            <h4>{{ __('Wishlists') }}</h4>
                        </div>
                        <div class="main-info">
                            <div class="main-table">
                                <div class="table-responsive">
                                    <table id="myTable" class="table table-striped w-100">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Serial') }}</th>
                                                <th>{{ __('Property title') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach ($wishlists as $item)
                                                @php
                                                    $content = $wishlistContents->get($item->property_id);
                                                @endphp

                                                @if ($content)
                                                    <tr>
                                                        <td>#{{ $i }}</td>
                                                        <td><a
                                                                href="{{ route('frontend.property.details', ['slug' => $content->slug]) }}">{{ $content->title }}</a>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('frontend.property.details', ['slug' => $content->slug]) }}"
                                                                class="btn"><i class="fas fa-eye"></i>
                                                                {{ __('View') }}</a>
                                                            <a href="{{ route('remove.wishlist', $item->property_id) }}"
                                                                class="btn"><i class="fas fa-times"></i>
                                                                {{ __('Remove') }}</a>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $i++;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            @if ($i === 1)
                                                <tr><td colspan="3">
                                                    <div class="rafa-empty-state">
                                                        <i class="far fa-heart" aria-hidden="true"></i>
                                                        <strong>{{ __('Your next home starts here') }}</strong>
                                                        <p>{{ __('Save properties you love and compare them here whenever you are ready.') }}</p>
                                                        <a class="rafa-action" href="{{ route('frontend.properties') }}">{{ __('Explore properties') }}</a>
                                                    </div>
                                                </td></tr>
                                            @endif


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
