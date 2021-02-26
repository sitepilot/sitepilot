:root {
@if($plugin->model->get_primary_color())
--plyr-color-main: {!! $plugin->model->get_primary_color() !!};
--sp-color-primary: {!! $plugin->model->get_primary_color() !!};
@endif
@if($plugin->model->get_secondary_color())
--sp-color-secondary: {!! $plugin->model->get_secondary_color() !!};
@endif
@if($plugin->model->get_third_color())
--sp-color-third: {!! $plugin->model->get_third_color() !!};
@endif
@if($plugin->model->get_fourth_color())
--sp-color-fourth: {!! $plugin->model->get_fourth_color() !!};
@endif
@if($plugin->model->get_container_width())
--sp-container-width: {!! $plugin->model->get_container_width() !!};
@endif
}
@if($plugin->settings->enabled('hide_recaptcha'))
.grecaptcha-badge {
display: none !important;
}
@endif