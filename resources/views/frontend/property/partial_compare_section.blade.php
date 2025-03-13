<a href="{{ route('property.details', [$property->slug]) }}" >
    <div class="detailmainSec">
        <div class="detailsTextSec">
            @include('frontend.property.partial_compare_section_mobile', ['project' => $property])
        </div>
        <div class="endText">
            <p><i class="fa-regular fa-calendar"></i> Possession by {{ getFormatedDate($property->possession_by, 'M, Y') }}</p>
        </div>
        <div class="innerPriceBox">
            <div class="priceShare">
                <h5>
                    <span>₹ {{ $property->price_from }} {{ formatPriceUnit($property->price_from_unit) }}</span>
                    @if(hasDifferentPrices($property))
                        - <span>₹ {{ $property->price_to }} {{ formatPriceUnit($property->price_to_unit) }}</span>
                    @endif
                </h5>
            </div>
            <div class="boxLogo">
                <img src="{{ $baseUrl }}assest/images/x-btn.png" alt="x-btn">
                <span>{{ $property->exio_suggest_percentage }}%</span>
            </div>
        </div>
    </div>
</a>