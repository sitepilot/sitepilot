:root {
@foreach($theme->colors() as $color)
--sp-color-{{ $color->key }}: {!! $color->value !!};
@endforeach
@foreach($theme->css_vars() as $var)
--sp-{{ $var->key }}: {!! $var->value !!};
@endforeach
}

@foreach($theme->colors() as $color)
.has-{{ $color->key }}-color {
    color: var(--sp-color-{{ $color->key }});
}

.has-{{ $color->key }}-background-color {
    background-color: var(--sp-color-{{ $color->key }});
}
@endforeach
