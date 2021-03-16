@if($youtube_id || $$vimeo_id)
<div class="sp-block-video__wrap relative">
    @if(is_admin())<div class="sp-block-video__admin"></div>@endif
    @if('youtube' == $provider && $youtube_id)
    <div class="sp-block-video__player" data-plyr-provider="youtube" data-plyr-embed-id="{{ $youtube_id }}" data-plyr-config="{{ $player_config }}" @if($image) data-poster="{{ $image[$image_size] }}" @endif></div>
    @elseif('vimeo' == $provider && $vimeo_id)
    <div class="sp-block-video__player" data-plyr-provider="vimeo" data-plyr-embed-id="{{ $vimeo_id }}" data-plyr-config="{{ $player_config }}" @if($image) data-poster="{{ $image[$image_size] }}" @endif></div>
    @endif
</div>
@endif