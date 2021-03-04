@if($img_1 && $img_2) 
<div class="sp-block-image-compare__wrap">
    <img src="{{ $img_1[$img_1_size] }}" />
    <img src="{{ $img_2[$img_2_size] }}" />
</div>
@endif