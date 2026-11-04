<section class="rafa-overview" aria-labelledby="rafa-overview-title">
    <div>
        <span class="rafa-eyebrow">RAFAIMMOBILIER · {{ $workspace }}</span>
        <h1 id="rafa-overview-title">{{ __('Welcome back,') }} {{ $displayName }}!</h1>
        <p>{{ $description }}</p>
    </div>
    <div class="rafa-overview-actions">
        <a href="{{ $actionUrl }}" class="rafa-action rafa-action-primary">
            <i class="fas fa-arrow-right" aria-hidden="true"></i> {{ $actionLabel }}
        </a>
        <a href="{{ route('index') }}" class="rafa-action">
            <i class="fas fa-home" aria-hidden="true"></i> {{ __('Visit website') }}
        </a>
    </div>
</section>
