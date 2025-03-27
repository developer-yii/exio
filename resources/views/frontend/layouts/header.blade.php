@php
    use App\Models\City;
    $baseUrl = asset('frontend') . '/';

    if ($showCityDropdown) {
        $defaultCity = 'Ahmedabad';
        $inputCity = request('city') ?? 1;
        $req_city = request('city');
        if ($req_city) {
            $city = City::find($req_city);
            if ($city) {
                $defaultCity = $city->city_name;
            }
        }
        $cities = City::where('status', 1)->get();
    }
@endphp
<header class="{{ $showCityDropdown ? 'headerInner' : '' }} {{ $headerClass }}">
    <div class="header-part">
        <div class="container">
            <div class="header-box">
                <div class="mobileIcon">
                    <a href="javascript:void(0)"><i class="fa-solid fa-bars"></i></a>
                </div>
                <div class="logo-box">
                    <a href="{{ route('front.home') }}"><img src="{{ $baseUrl }}assest/images/logo-img.png"
                            alt="logo-img" /></a>
                    @if ($showCityDropdown)
                        <div class="cityDropDown moblieHide">
                            <ul>
                                <li>
                                    <input type="hidden" name="city_header" id="city_header" value="{{ $inputCity }}" />
                                    <a class="cityClick cityClickHeader" href="javascript:void(0)">
                                        <span id="city_header_name">{{ $defaultCity }}</span>
                                        <i class="fa-solid fa-chevron-down rotate"></i>
                                    </a>
                                    <ul class="citySelect city-select-header">
                                        @foreach ($cities as $city)
                                            <li>
                                                <a href="javascript:void(0)" data-id="{{ $city->id }}"
                                                    data-name="{{ $city->city_name }}" class="city_click_header">
                                                    {{ $city->city_name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="main-nav">
                    <ul>
                        {{-- all Project section --}}
                        @if(request()->route()->getName() == 'front.home')
                            <li class="moblieHide">
                                <a href="javascript:void(0)" class="first-menu">All Projects</a>
                                <ul class="projectDropDown">
                                    <li>
                                        <a href="{{ route('property.result.filter', ['type' => 'high-demand']) }}">
                                            <div class="imgBox">
                                                <img src="{{ $baseUrl }}assest/images/demand.png" alt="demand">
                                            </div>
                                            <div class="text">
                                                <h6>In High Demand</h6>
                                                <p>Neque porro quisquam est qui dolorem.</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('property.result.filter', ['type' => 'just-launch']) }}">
                                            <div class="imgBox">
                                                <img src="{{ $baseUrl }}assest/images/launched.png" alt="launched">
                                            </div>
                                            <div class="text">
                                                <h6>Just Launched</h6>
                                                <p>Ipsum quia dolor sit amet consece adipisci velit.</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('property.result.filter', ['type' => 'rating']) }}">
                                            <div class="imgBox">
                                                <img src="{{ $baseUrl }}assest/images/rating.png" alt="rating">
                                            </div>
                                            <div class="text">
                                                <h6>Rating</h6>
                                                <p>Porro quisquam est qui dolorem ipsum quia.</p>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        {{-- check and match video section --}}
                        @if(request()->route()->getName() != 'front.check-and-match-property')
                            <li class="moblieHide">
                                <a href="{{ route('front.check-and-match-property') }}" class="second-menu">
                                    <i class="fa-solid fa-circle-check"></i> Check and match property
                                </a>
                            </li>
                        @endif

                        <li>
                            <a href="javascript:void(0)" class="user-menu"><i class="fa-solid fa-user"></i></a>
                            <ul class="userDropDown">
                                @if (Auth::user())
                                    <li class="cursor-pointer">
                                        <a href="" class="full-li-cover">
                                            <img src="{{ $baseUrl }}assest/images/login.png" alt="login">Profile({{ Auth::user()->name }})
                                        </a>
                                    </li>
                                @else
                                    <li class="cursor-pointer">
                                        <a href="{{ route('login') }}" class="full-li-cover">
                                            <img src="{{ $baseUrl }}assest/images/login.png" alt="login">Login
                                        </a>
                                    </li>
                                @endif
                                    {{-- <li onclick="location.href='{{ route('property.insights') }}'" style="cursor: pointer;">
                                        <img src="{{ $baseUrl }}assest/images/project.png" alt="project"> Project
                                        Insights
                                    </li> --}}
                                    <li class="cursor-pointer">
                                        <a href="{{ route('property.insights') }}" class="full-li-cover">
                                            <img src="{{ $baseUrl }}assest/images/project.png" alt="project"> Project Insights
                                        </a>
                                    </li>
                                @if (Auth::user())
                                    <li class="cursor-pointer">
                                        <a href="{{ route('property.shortlisted') }}" class="full-li-cover">
                                            <img src="{{ $baseUrl }}assest/images/shortlist.png" alt="shortlist">Shortlisted Properties
                                        </a>
                                    </li>

                                    <li class="cursor-pointer">
                                        <a href="{{ route('property.insights-report') }}" class="full-li-cover">
                                            <img src="{{ $baseUrl }}assest/images/report.png" alt="report">Downloaded Reports
                                        </a>
                                    </li>

                                    <li class="cursor-pointer">
                                        <a href="{{ route('property.compare-report') }}" class="full-li-cover">
                                            <img src="{{ $baseUrl }}assest/images/compare.png" alt="compare">Compare Reports
                                        </a>
                                    </li>
                                @endif

                                <li class="cursor-pointer">
                                    <a href="{{ route('contact-us') }}" class="full-li-cover">
                                        <img src="{{ $baseUrl }}assest/images/contact.png" alt="contact">Contact Us
                                    </a>
                                </li>
                                @if (Auth::user())
                                    <li class="cursor-pointer">
                                        <a onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="full-li-cover">
                                            <img src="{{ $baseUrl }}assest/images/contact.png" alt="contact">Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="app-display-none">
                                            @csrf
                                        </form>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
