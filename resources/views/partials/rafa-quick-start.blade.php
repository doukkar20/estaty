@php
    $isCustomer = $audience === 'customer';
    $quickActions = $isCustomer
        ? [
            [route('frontend.properties'), 'fas fa-search', __('Find a property'), __('Search by city and budget.')],
            [route('user.wishlist'), 'fas fa-heart', __('My saved properties'), __('Find the properties you liked.')],
            [route('user.edit_profile'), 'fas fa-user', __('Complete my profile'), __('Keep your contact details up to date.')],
        ]
        : [
            [route($audience . '.property_management.create_property'), 'fas fa-plus', __('Publish a property'), __('Add photos, a price and a location.')],
            [route($audience . '.property_management.' . 'properties', ['language' => $defaultLang->code]), 'fas fa-home', __('Manage my listings'), __('Review and update your properties.')],
            [route($audience . '.property_message.index'), 'fas fa-envelope', __('Read inquiries'), __('Follow up with interested clients.')],
        ];
@endphp
@if ($audience !== 'admin' || is_null($roleInfo))
<section class="rafa-quick-start" aria-labelledby="quick-start-title">
    <div class="rafa-section-heading"><h2 id="quick-start-title">{{ __('What would you like to do?') }}</h2><span>{{ __('Your everyday essentials, one click away.') }}</span></div>
    <div class="rafa-shortcut-grid">
        @foreach ($quickActions as [$url, $icon, $label, $hint])
            @if ($audience !== 'admin' || is_null($roleInfo))
                <a class="rafa-shortcut" href="{{ $url }}"><span class="rafa-shortcut-icon"><i class="{{ $icon }}" aria-hidden="true"></i></span><strong>{{ $label }}</strong><p>{{ $hint }}</p><span class="rafa-shortcut-arrow" aria-hidden="true">↗</span></a>
            @endif
        @endforeach
    </div>
    @if (!$isCustomer && ($audience !== 'admin' || is_null($roleInfo)))
        <div class="rafa-listing-tip"><i class="fas fa-lightbulb" aria-hidden="true"></i><p><strong>{{ __('A clear listing gets a better start.') }}</strong> {{ __('Use recent photos, the correct commune, a price in DH and a surface area in m².') }}</p></div>
    @endif
</section>
@endif
