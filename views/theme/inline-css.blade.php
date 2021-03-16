:root {
@foreach($theme->colors() as $color)
--sp-color-{{ $color->key }}: {!! $color->value !!};
@endforeach
@foreach($theme->css_vars() as $var)
--{{ $var->key }}: {!! $var->value !!};
@endforeach
}

@foreach($theme->colors() as $color)
.has-{{ $color->key }}-color,
.has-{{ $color->key }}-text-color {
    color: var(--sp-color-{{ $color->key }});
}

.has-{{ $color->key }}-background-color {
    background-color: var(--sp-color-{{ $color->key }});
}
@endforeach

@if(apply_filters('sp_hide_recaptcha_badge', false))
.grecaptcha-badge {
display: none !important;
}
@endif