@if($url)
<div class="{{ $classes }}">
    @if(is_admin())<div class="absolute inset-0 z-50"></div>@endif
    <iframe src="{{ $url }}" class="{{ $iframe_classes }}"  frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</div>
@endif