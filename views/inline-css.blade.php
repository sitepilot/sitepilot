:root {
@if($primary_color = sitepilot()->model->get_primary_color())
--plyr-color-main: {!! $primary_color !!};
--sp-color-primary: {!! $primary_color !!};
@endif
@if($secondary_color = sitepilot()->model->get_secondary_color())
--sp-color-secondary: {!! $secondary_color !!};
@endif
@if($third_color = sitepilot()->model->get_third_color())
--sp-color-third: {!! $third_color !!};
@endif
@if($fourth_color = sitepilot()->model->get_fourth_color())
--sp-color-fourth: {!! $fourth_color !!};
@endif
@if($container_width = sitepilot()->model->get_container_width())
--sp-container-width: {!! $container_width !!};
@endif
}
@if(sitepilot()->model->get_hide_recaptcha_badge())
.grecaptcha-badge {
display: none !important;
}
@endif