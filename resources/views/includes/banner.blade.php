@php $banners = \App\Models\Banner::active()->ordered()->get(); @endphp
@if($banners->count() > 0)
<div class="banner-carousel">
    <style>.bannerSwiper { opacity: 0; transition: opacity 0.3s; } .bannerSwiper.swiper-initialized { opacity: 1; }</style>
<div class="swiper bannerSwiper">
        <div class="swiper-wrapper">
            @foreach($banners as $banner)
                <div class="swiper-slide">
                    <a href="{{ $banner->link ?: '#' }}" {{ $banner->link ? 'target="_blank"' : '' }}>
                        <img src="{{ $banner->imageUrl() }}" alt="{{ $banner->title }}" style="max-width:100%;width:100%;height:auto;display:block;">
                    </a>
                </div>
            @endforeach
        </div>
        <div class="swiper-button-next banner-next"></div>
        <div class="swiper-button-prev banner-prev"></div>
        <div class="swiper-pagination banner-pagination"></div>
    </div>
</div>
@endif
