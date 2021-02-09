@if($url)
<div class="{{ $classes }}">
    @if(is_admin())<div class="absolute inset-0 z-50"></div>@endif
    <iframe src="{{ $url }}" class="{{ $iframe_classes }}" frameborder="0" aria-hidden="true"></iframe>
</div>
@endif