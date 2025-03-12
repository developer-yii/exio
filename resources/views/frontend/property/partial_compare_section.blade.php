<div class="detailmainSec">
    <div class="detailsTextSec">
        <div class="siteDetails">
            <div class="logoMain">
                <img src="{{ $property->builder->getBuilderLogoUrl() }}" loading="lazy" alt="{{ $property->builder->builder_name }}" title="{{ $property->builder->builder_name }}">
            </div>
            <div class="textBox">
                <h5>{{ $property->project_name }}</h5>
                <span>By {{ $property->builder->builder_name }}</span>
                <div class="locationProperty">
                    <div class="homeBox comBox">
                        <img src="{{ $baseUrl }}assest/images/Home.png" alt="Home">
                        <p>{{ $property->custom_property_type ?? '' }}</p>
                    </div>
                    <div class="location comBox">
                        <img src="{{ $baseUrl }}assest/images/Location.png" alt="Location">
                        <p>{{ $property->location->location_name . ', ' . $property->city->city_name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="endText">
        <p><i class="fa-regular fa-calendar"></i> Possession by {{ getFormatedDate($property->possession_by, 'M, Y') }}</p>
    </div>
    <div class="innerPriceBox">
        <div class="priceShare">
            <h5><span>₹ {{ $property->price_from }} {{ formatPriceUnit($property->price_from_unit) }}</span>
                - <span>₹ {{ $property->price_to }} {{ formatPriceUnit($property->price_to_unit) }}</span>
            </h5>
        </div>
        <div class="boxLogo">
            <img src="{{ $baseUrl }}assest/images/x-btn.png" alt="x-btn">
            <span>{{ $property->exio_suggest_percentage }}%</span>
        </div>
    </div>
</div>