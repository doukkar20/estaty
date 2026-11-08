@if (\App\Support\MoroccoMarket::enabled())
    @php
        $locationLanguage = \App\Models\Language::where('is_default', 1)->value('id');
        $moroccanCities = \App\Models\Property\City::with(['cityContent' => fn ($query) => $query->where('language_id', $locationLanguage)])->orderBy('serial_number')->get();
    @endphp
    <div class="form-group">
        <label for="morocco-project-city">{{ __('City / Commune') }} *</label>
        <select id="morocco-project-city" name="city_id" class="form-control js-example-basic-single1" required>
            <option value="">{{ __('Select City') }}</option>
            @foreach ($moroccanCities as $moroccanCity)
                <option value="{{ $moroccanCity->id }}" @selected((string) old('city_id', $project->city_id ?? '') === (string) $moroccanCity->id)>{{ $moroccanCity->cityContent?->name }}</option>
            @endforeach
        </select>
        @error('city_id')<p class="text-danger">{{ $message }}</p>@enderror
    </div>
@endif
