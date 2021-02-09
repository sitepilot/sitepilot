@if($image)
<div class="{{ $classes }}">
    <img src="{{ $image[$image_size] }}" loading="lazy" class="{{ $image_classes }}" />
</div>
@endif