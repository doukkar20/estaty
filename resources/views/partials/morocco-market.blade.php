@if (\App\Support\MoroccoMarket::enabled())
    <script>
        window.rafaMorocco = @json(['countryId' => \App\Support\MoroccoMarket::countryId(), 'phonePrefix' => '+212']);
    </script>
    <script src="{{ asset('assets/js/morocco-market.js') }}"></script>
@endif
