<div class="siteDetails">
    <div class="logoMain">
        <img src="{{ $project->builder->getBuilderLogoUrl() }}" loading="lazy" alt="{{ $project->builder->builder_name }}" title="{{ $project->builder->builder_name }}">
    </div>
    <div class="textBox">
        <h5 class="one-line-text" title="{{ $project->project_name }}">{{ $project->project_name }}</h5>
        <span>By {{ $project->builder->builder_name }}</span>
        <div class="locationProperty">
            <div class="homeBox comBox">
                <img src="{{ $baseUrl }}assest/images/Home.png" alt="Home">
                <p class="one-line-text" title="{{ $project->custom_property_type ?? '' }}">{{ $project->custom_property_type ?? '' }}</p>
            </div>
            <div class="location comBox">
                <img src="{{ $baseUrl }}assest/images/Location.png" alt="Location">
                <p class="one-line-text" title="{{ $project->location->location_name . ', ' . $project->city->city_name }}">{{ $project->location->location_name . ', ' . $project->city->city_name }}</p>
            </div>
        </div>
    </div>
</div>