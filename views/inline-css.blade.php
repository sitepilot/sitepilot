:root {
@foreach(sitepilot()->model->get_theme_colors() as $key=>$value)
--sp-color-{{ $key }}: {!! $value['color'] !!};
@endforeach
@foreach(sitepilot()->model->get_theme_vars() as $key=>$value)
--{{ $key }}: {!! $value !!};
@endforeach
}
@if(sitepilot()->model->get_hide_recaptcha_badge())
.grecaptcha-badge {
display: none !important;
}
@endif