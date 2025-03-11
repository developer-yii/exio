@php
    $baseUrl = asset('frontend') . '/';
@endphp

@section('title', 'Insights Properties')
@extends('frontend.layouts.app')
@section('content')
    <section class="bannerSky">
        <div class="container">
            <div class="bannerSkyText">
                <h4>Fed Up With The Hunt For Your Ideal Property?</h4>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                    industry's standard dummy text ever.</p>
            </div>

            @if (getDeviceType() == 'desktop')
                <div class="filterSec">
                    <div class="container">
                        <div class="bannerFilterBox">
                            <div class="cityFilter">
                                <p>Choose City</p>
                                <input type="hidden" name="city_search" id="city_search" />
                                <a href="javascript:void(0)" class="cityClick"><span id="city_search_name"></span>
                                    <i class="fa-solid fa-chevron-down"></i></a>
                                <ul class="citySelect">
                                    @foreach ($cities as $city)
                                        <li><a href="javascript:void(0)" data-id="{{ $city->id }}"
                                                data-name="{{ $city->city_name }}"
                                                class="city_click">{{ $city->city_name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="landMarkFilter">
                                <div class="searchBox searchKeyup">
                                    <p>Search</p>
                                    <input type="search" class="clickListClass" placeholder="Locality, Project, or Builder"
                                        autocomplete="off">
                                    <div class="search-key d-none">
                                        <ul>
                                            @if ($locations->count() > 0)
                                                <h6>Locality</h6>
                                                @foreach ($locations as $location)
                                                    <li style="display: none;">
                                                        <a href="javascript:void(0)" data-type="locality"
                                                            data-id="{{ $location->id }}"
                                                            data-city-id="{{ $location->city_id }}"
                                                            data-name="{{ $location->location_name }}">
                                                            {{ $location->location_name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            @endif
                                            @if ($projects->count() > 0)
                                                <h6>Project</h6>
                                                @foreach ($projects as $project)
                                                    <li style="display: none;">
                                                        <a href="javascript:void(0)" data-type="project"
                                                            data-id="{{ $project->id }}"
                                                            data-name="{{ $project->project_name }}">
                                                            {{ $project->project_name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            @endif
                                            @if ($builders->count() > 0)
                                                <h6>Builder</h6>
                                                @foreach ($builders as $builder)
                                                    <li style="display: none;"><a href="javascript:void(0)"
                                                            data-type="builder" data-id="{{ $builder->id }}"
                                                            data-name="{{ $builder->builder_name }}">{{ $builder->builder_name }}</a>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <div class="searchIcon">
                                    <a href="javascript:void(0)" class="btn btnIcon searchBtn"><i
                                            class="bi bi-search"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (getDeviceType() == 'mobile')
                <div class="mobileFilterHero">
                    <div class="cityDropDown">
                        <ul>
                            <li>
                                <input type="hidden" name="city_search" id="city_search" />
                                <a class="cityClick" href="javascript:void(0)"><span id="city_search_name">Ahmedabad</span>
                                    <i class="fa-solid fa-chevron-down"></i></a>
                                <ul class="citySelect">
                                    @foreach ($cities as $city)
                                        <li><a href="javascript:void(0)" data-id="{{ $city->id }}"
                                                data-name="{{ $city->city_name }}"
                                                class="city_click">{{ $city->city_name }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="seacrhNewBox searchKeyup">
                        <input type="search" class="clickListClass" placeholder="Search Locality, Project, or Builder"
                            autocomplete="off">
                        <div class="search-key d-none">
                            <ul>
                                @if ($locations->count() > 0)
                                    <h6>Locality</h6>
                                    @foreach ($locations as $location)
                                        <li style="display: none;">
                                            <a href="javascript:void(0)" data-type="locality" data-id="{{ $location->id }}"
                                                data-city-id="{{ $location->city_id }}"
                                                data-name="{{ $location->location_name }}">
                                                {{ $location->location_name }}
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                                @if ($projects->count() > 0)
                                    <h6>Project</h6>
                                    @foreach ($projects as $project)
                                        <li style="display: none;">
                                            <a href="javascript:void(0)" data-type="project" data-id="{{ $project->id }}"
                                                data-name="{{ $project->project_name }}">
                                                {{ $project->project_name }}
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                                @if ($builders->count() > 0)
                                    <h6>Builder</h6>
                                    @foreach ($builders as $builder)
                                        <li style="display: none;"><a href="javascript:void(0)" data-type="builder"
                                                data-id="{{ $builder->id }}"
                                                data-name="{{ $builder->builder_name }}">{{ $builder->builder_name }}</a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                        <div class="searchIcon">
                            {{-- <a href="" class="btn btnIcon"><i class="bi bi-search"></i></a> --}}
                            <a href="javascript:void(0)" class="btn btnIcon searchBtn"><i class="bi bi-search"></i></a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <section class="site_traking_listng">
        <div class="container">
            <div class="site_traking_box">
                @if ($totalProperties > 0)
                    <div class="row" id="propertyContainer">
                        @include('frontend.property.partial_insights_property_list', [
                            'properties' => $properties,
                        ])
                    </div>
                    @if ($totalProperties > $perPageProperty)
                        <div class="moreBtn">
                            <a class="btn btnExplore" id="loadMoreBtn" href="javascript:void(0)">Explore More</a>
                        </div>
                    @endif
                @else
                    <p>Property not found</p>
                @endif
            </div>
        </div>
    </section>

@endsection
@section('js')
    <script>
        var page = 2; // Start from page 2 since initial load is page 1
        var exploreMoreUrl = "{{ route('property.insights') }}";
    </script>
    <script src="{{ frontendPageJsLink('explore-more.js') }}"></script>
    <script src="{{ frontendPageJsLink('search-property.js') }}"></script>
@endsection
