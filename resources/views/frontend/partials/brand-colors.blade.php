@php
    // Keep all three themes in sync with Basic Settings and validate each color independently.
    $normalizeBrandColor = static function ($value, $fallback) {
        $hex = ltrim((string) $value, '#');
        return '#' . (preg_match('/\A[0-9a-fA-F]{6}\z/', $hex) ? $hex : $fallback);
    };
    $primaryColor = $normalizeBrandColor($basicInfo->primary_color ?? null, 'C69E4C');
    $secondaryColor = $normalizeBrandColor($basicInfo->secondary_color ?? null, '091F3A');
    $brandRgb = static function ($hex) {
        return implode(', ', sscanf($hex, '#%02x%02x%02x'));
    };
@endphp
<style>
    :root {
        --color-primary: {{ $primaryColor }};
        --color-primary-rgb: {{ $brandRgb($primaryColor) }};
        --color-secondary: {{ $secondaryColor }};
        --color-secondary-rgb: {{ $brandRgb($secondaryColor) }};
    }
</style>
