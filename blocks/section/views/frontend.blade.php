@if($bg_image)
<div class="sp-block-section__bg-image" style="background-image: url('{{ $bg_image[$bg_image_size] }}');"></div>
@endif

<div class="sp-block-section__inner-container">
    <InnerBlocks template="{{ $template }}" allowedBlocks="{{ $allowed_blocks }}" />
</div>
