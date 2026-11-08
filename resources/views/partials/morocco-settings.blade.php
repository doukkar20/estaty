<div class="alert alert-info">
    <h5>{{ __('Morocco market') }}</h5>
    <input type="hidden" name="morocco_market" value="0">
    <label class="d-flex align-items-center" style="gap: .6rem">
        <input type="checkbox" name="morocco_market" value="1" @checked(old('morocco_market', \App\Support\MoroccoMarket::enabled()))>
        <strong>{{ __('Morocco profile') }}</strong>
    </label>
    <p class="mb-1">{{ __('Morocco profile help') }}</p>
    <p class="mb-1">{{ __('Morocco defaults') }}</p>
    <a href="{{ route('admin.property_specification.cities') }}">{{ __('Manage Moroccan locations') }}</a>
</div>
