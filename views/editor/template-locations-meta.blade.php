<div class="sp-block">
    <p class="mt-4 mb-2 font-bold">{{ __('Location', 'sitepilot') }}</p>
    @foreach($locations as $key => $location)
    <div class="mb-1">
        <label for="{{ $template_locations_key }}[{{ $key }}]">
            <input type="checkbox" id="{{ $template_locations_key }}[{{ $key }}]" name="{{ $template_locations_key }}[{{ $key }}]" value="enabled" {!! checked(in_array($key, $value), true) !!} />
            {{ $location }}
        </label>
    </div>
    @endforeach
</div>