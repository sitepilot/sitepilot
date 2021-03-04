@if(count($items)) 
@foreach($items as $item)
<article class="sp-block-accordion__item">
    <div class="sp-block-accordion__title-wrap">
        <div class="sp-block-accordion__title">
            <span>{{ $item['title'] }}</span>
        </div>
        <div class="sp-block-accordion__icon">
            <i class="fas fa-plus"></i>
        </div>
    </div>  
    <div class="sp-block-accordion__content">
        {!! $item['content'] !!}
    </div>
</article>
@endforeach
@endif