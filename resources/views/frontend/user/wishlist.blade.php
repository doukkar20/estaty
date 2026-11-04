@php
    $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
    {{ __('Wishlist') }}
@endsection


@section('content')
    @includeIf('frontend.partials.breadcrumb', [
        'breadcrumb' => $bgImg->breadcrumb ?? '',
        'title' => !empty($pageHeading) ? $pageHeading->wishlist_page_title : __('Wishlist'),
        'subtitle' => __('Wishlist'),
    ])


    <!--====== Start Dashboard Section ======-->
    <div class="user-dashboard pt-100 pb-60">
        <div class="container">
            <div class="row gx-xl-5">
                @includeIf('frontend.user.side-navbar')
                <div class="col-lg-9">
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
                                            @foreach ($wishlists as $item)
                                                @php
                                                    $content = $wishlistContents->get($item->property_id);
                                                @endphp
                                                @if (!is_null($content))
                                                    <tr>
                                                        <td>#{{ $loop->iteration }}</td>
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
                                                @endif
                                            @endforeach
                                            @if ($wishlistContents->isEmpty())
                                                <tr><td colspan="3">
                                                    <div class="rafa-empty-state">
                                                        <i class="far fa-heart" aria-hidden="true"></i>
                                                        <strong>{{ __('No saved properties yet') }}</strong>
                                                        <p>{{ __('Explore listings and save your favorites to find them here.') }}</p>
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
    <!--====== End Dashboard Section ======-->
@endsection
