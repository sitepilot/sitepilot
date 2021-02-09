<div class="{{ $classes }}">
    @if($bg_image)
    <div class="{{ $bg_image_classes }}" style="background-image: url('{{ $bg_image[$bg_image_size] }}');"></div>
    @endif

    <div class="{{ $content_classes }}">
        <InnerBlocks />
    </div>
</div>