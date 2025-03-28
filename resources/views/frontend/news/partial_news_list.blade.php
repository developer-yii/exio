@php
    $baseUrl = asset('frontend') . '/';
@endphp
@foreach ($news as $item)
<div class="col-lg-4 col-md-6">
    <a href="javascript:void(0)">
        <div class="blog_item">
            <div class="blog_img">
                <img src="{{ $item->getNewsImgUrl() }}" alt="property-img">
                <div class="addedBy">
                    <p>Added: 
                        <span>
                            {{ getFormatedDate($item->updated_at, 'd M Y') }}                        
                        </span>
                    </p>
                </div>
            </div>
            <div class="blog_text">
                <h5 class="one-line-text" title="{{ $item->title }}">{{ $item->title }}</h5>
                <h6 class="one-line-text" title="{{ $item->description }}">{{ $item->description }}</h6>
                <p class="discript four-line-text"> {!! $item->content !!}</p>
            </div>
        </div>
    </a>
</div>
@endforeach
