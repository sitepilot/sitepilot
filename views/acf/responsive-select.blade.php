<div class="sp-responsive-select">
    <div class="sp-responsive-select__variations" style="display: flex; gap: 5px; margin-bottom: 4px;">
        @foreach($variations as $variation_key => $variation)
        <div class="sp-responsive-select__variation" data-device="{{ $variation_key }}" style="background-color: #F3F4F6; border-radius: 100%; height: 25px; width: 25px; display: flex; align-items: center; justify-content: center; color: #111827;">
            <span style="cursor: pointer; font-size: 16px; width: 16px; height: 16px; color: rgb(16, 98, 254);" data-toggle="{{ $variation_key }}" class="dashicons {{ $variation['icon'] }}"></span>
        </div>
        @endforeach
    </div>

    @foreach($variations as $variation_key => $variation)
    <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: center;">
        @foreach($fields as $subkey=>$subfield)
        <div data-device="{{ $variation_key }}" class="sp-responsive-select__field" style="flex: 1 1 {{ $subfield['width'] ?? '25%' }}; padding-right: 2px;">
            @if(count($fields) > 1)<label style="display: block; margin-bottom: 2px;">{{ $subfield['label'] }}</label>@endif
            <select name="{{ $name }}[{{ $subkey }}][{{ $variation_key }}]" style="max-width: 100%;">
                @foreach($subfield['choices'] as $choice_value => $choice_name)
                @if(is_string($choice_value) && $choice_value == 'default' && isset($default_values[$subkey][$variation_key]) && isset($subfield['choices'][$default_values[$subkey][$variation_key]]))
                <optgroup label="Default">
                    @php($choice_name = $subfield['choices'][$default_values[$subkey][$variation_key]])
                    <option value="{{ $choice_value }}" @if(isset($value[$subkey][$variation_key]) && (string) $value[$subkey][$variation_key]==(string) $choice_value) selected @endif>{{ $choice_name }}</option>
                </optgroup>
                <optgroup label="{{ __('Options', 'sitepilot') }}">
                    @else
                    <option value="{{ $choice_value }}" @if(isset($value[$subkey][$variation_key]) && (string) $value[$subkey][$variation_key]==(string) $choice_value) selected @endif>{{ $choice_name }}</option>
                    @endif
                    @endforeach
                </optgroup>
            </select>
        </div>
        @endforeach
    </div>
    @endforeach
</div>