@php
    $baseUrl = asset('frontend') . '/';
@endphp
<header>
    <div class="header-part">
        <div class="container">
            <div class="header-box">
                <div class="mobileIcon">
                    <a href="javascript:void(0)"><i class="fa-solid fa-bars"></i></a>
                </div>
                <div class="logo-box">
                    <a href="{{ route('front.home') }}"><img src="{{ $baseUrl }}assest/images/logo-img.png"
                            alt="logo-img" /></a>
                </div>
                <div class="main-nav">
                    <ul>
                        <li class="moblieHide">
                            <a href="javascript:void(0)" class="first-menu">All Projects</a>
                            <ul class="projectDropDown">
                                <li>
                                    <a href="javascript:void(0)">
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
                                    <a href="javascript:void(0)">
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
                                    <a href="javascript:void(0)">
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
                        <li class="moblieHide">
                            <a href="{{ route('front.check-and-match-property') }}" class="second-menu"><i
                                    class="fa-solid fa-circle-check"></i> Check and
                                match property</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="user-menu"><i class="fa-solid fa-user"></i></a>
                            <ul class="userDropDown">
                                @if (Auth::user())
                                    <li><a href=""><img src="{{ $baseUrl }}assest/images/login.png"
                                                alt="login">Profile({{ Auth::user()->name }})</a></li>
                                @else
                                    <li>
                                        <a href="{{ route('login') }}">
                                            <img src="{{ $baseUrl }}assest/images/login.png" alt="login">Login
                                        </a>
                                    </li>
                                @endif
                                <li><a href="site_traking_listing.html"><img
                                            src="{{ $baseUrl }}assest/images/project.png" alt="project">Project
                                        Insights</a></li>
                                <li><a href="liked_properties.html"><img
                                            src="{{ $baseUrl }}assest/images/shortlist.png"
                                            alt="shortlist">Shortlisted
                                        Properties</a></li>
                                <li><a href="downloaded_certificates.html"><img
                                            src="{{ $baseUrl }}assest/images/report.png" alt="report">Downloaded
                                        Reports</a></li>
                                <li><a href="compare_report.html"><img
                                            src="{{ $baseUrl }}assest/images/compare.png" alt="compare">Compare
                                        Reports</a>
                                </li>
                                <li><a href="javascript:void(0)"><img src="{{ $baseUrl }}assest/images/assist.png"
                                            alt="assist">Property Assist</a>
                                </li>
                                <li>
                                    <a href="{{ route('contact-us') }}">
                                        <img src="{{ $baseUrl }}assest/images/contact.png" alt="contact">Contact
                                        Us
                                    </a>
                                </li>
                                @if (Auth::user())
                                    <li>
                                        <a href="javascript:void(0)"
                                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                            <img src="{{ $baseUrl }}assest/images/contact.png"
                                                alt="contact">Logout</a>
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
