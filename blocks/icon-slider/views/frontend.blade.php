
<div class="sp-block-icon-slider__wrap">
    <div class="owl-carousel" data-items="{{ count($items) }}">
        @foreach($items as $item)
        <div class="sp-block-icon-slider__item">
            <div class="sp-block-icon-slider__icon">
                <i class="fas fa-check"></i>
            </div>
            <div class="sp-block-icon-slider__text">
                <span>{{ $item['text'] }}</span>
            </div>
        </div>
        @endforeach
    </div>
</div>