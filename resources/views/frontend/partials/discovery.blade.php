<section class="rafa-discovery" aria-labelledby="discovery-title">
    <div class="container">
        <div class="rafa-discovery-grid">
            <div class="rafa-discovery-copy">
                <span class="rafa-kicker">{{ __('Your next address in Morocco') }}</span>
                <h1 id="discovery-title">{{ $heroStatic->title ?? __('Find a place to call home.') }}</h1>
                <p>{{ __('Rent, buy or publish a property. Start with the city that matters to you.') }}</p>
                <div class="rafa-discovery-links">
                    <a href="{{ route('frontend.properties', ['purpose' => 'sale']) }}">{{ __('I want to buy') }} <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                    <a href="{{ route('frontend.properties', ['purpose' => 'rent']) }}">{{ __('I want to rent') }} <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                </div>
            </div>
            <div class="rafa-discovery-photo">
                <img src="{{ asset('assets/img/hero/static/' . $heroImg) }}" alt="" fetchpriority="high">
                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> {{ __('Across Morocco') }}</span>
            </div>
        </div>
        <div class="rafa-search-panel">
            <ul class="nav rafa-purpose-tabs" role="tablist" aria-label="{{ __('Property purpose') }}">
                @foreach (['rent' => __('Rent'), 'sale' => __('Buy')] as $purpose => $label)
                    <li role="presentation"><button class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $purpose }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $purpose }}" type="button" role="tab" aria-controls="{{ $purpose }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $label }}</button></li>
                @endforeach
            </ul>
            <div class="tab-content">
                @foreach (['rent', 'sale'] as $purpose)
                    <div class="tab-pane fade {{ $loop->first ? 'active show' : '' }}" id="{{ $purpose }}" role="tabpanel" aria-labelledby="{{ $purpose }}-tab">
                        <form action="{{ route('frontend.properties') }}" method="get" class="rafa-simple-search">
                            <input type="hidden" name="purpose" value="{{ $purpose }}">
                            <div>
                                <label for="{{ $purpose }}-city">{{ __('Where are you looking?') }}</label>
                                <select id="{{ $purpose }}-city" name="city" class="form-control select2" aria-label="{{ __('City / Commune') }}">
                                    <option value="all">{{ __('All Moroccan cities') }}</option>
                                    @foreach ($all_cities as $city)
                                        @if ($city->cityContent)
                                            <option value="{{ $city->cityContent->name }}">{{ $city->cityContent->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="{{ $purpose }}-category">{{ __('What kind of property?') }}</label>
                                <select id="{{ $purpose }}-category" name="category" class="form-control select2">
                                    <option value="all">{{ __('All property categories') }}</option>
                                    @foreach ($all_proeprty_categories as $category)
                                        @if ($category->categoryContent)
                                            <option value="{{ $category->categoryContent->slug }}">{{ $category->categoryContent->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="rafa-button rafa-button-primary"><i class="fas fa-search" aria-hidden="true"></i> {{ __('Find my property') }}</button>
                        </form>
                    </div>
                @endforeach
            </div>
            <p class="rafa-search-hint"><i class="fas fa-sliders-h" aria-hidden="true"></i> {{ __('More filters, including budget and surface area, are available in the results.') }}</p>
        </div>
    </div>
</section>
<section class="rafa-pathways container" aria-label="{{ __('How can we help?') }}">
    <a href="{{ route('frontend.properties') }}" class="rafa-pathway"><span class="rafa-path-icon"><i class="fas fa-home" aria-hidden="true"></i></span><div><h2>{{ __('Find your home') }}</h2><p>{{ __('Explore properties by city, budget and property type.') }}</p></div><i class="fas fa-arrow-right" aria-hidden="true"></i></a>
    <a href="{{ Auth::guard('vendor')->check() ? route('vendor.property_management.create_property') : route('vendor.signup') }}" class="rafa-pathway"><span class="rafa-path-icon"><i class="fas fa-plus" aria-hidden="true"></i></span><div><h2>{{ __('Publish a property') }}</h2><p>{{ __('Create your professional account, then add your listing.') }}</p></div><i class="fas fa-arrow-right" aria-hidden="true"></i></a>
    <a href="{{ route('contact') }}" class="rafa-pathway"><span class="rafa-path-icon"><i class="fas fa-comment-dots" aria-hidden="true"></i></span><div><h2>{{ __('Need a hand?') }}</h2><p>{{ __('Tell us about your property project. We can help you get started.') }}</p></div><i class="fas fa-arrow-right" aria-hidden="true"></i></a>
</section>
