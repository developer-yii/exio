<div class="leftside-menu">
    <!-- LOGO -->
    <a href="{{ route('admin.dashboard') }}" class="logo text-center logo-light">
        <span class="logo-lg">
            <img src="{{ asset('backend/images/logo.png') }}" alt="" height="50">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('backend/images/logo.png') }}" alt="" height="20">
        </span>
    </a>

    <!-- LOGO -->
    <a href="{{ route('admin.dashboard') }}" class="logo text-center logo-dark">
        <span class="logo-lg">
            <img src="{{ asset('backend/images/logo.png') }}" alt="" height="16">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('backend/images/logo.png') }}" alt="" height="16">
        </span>
    </a>

    <div class="h-100" id="leftside-menu-container" data-simplebar>

        <!--- Sidemenu -->
        <ul class="side-nav">

            <li class="side-nav-title side-nav-item">&nbsp</li>

            <li class="side-nav-item {{ isActiveRouteMain(['dashboard']) }}">
                <a href="{{ route('admin.dashboard') }}" class="side-nav-link {{ isActiveRoute(['dashboard']) }}">
                    <i class="uil-home-alt"></i>
                    <span> Dashboard </span>
                </a>
            </li>

            <li class="side-nav-item {{ isActiveRouteMain('users') }}">
                <a href="{{ route('admin.user') }}" class="side-nav-link {{ isActiveRoute(['users']) }}">
                    <i class="uil-users-alt"></i>
                    <span> Users </span>
                </a>
            </li>

            <li class="side-nav-item {{ isActiveRouteMain('builder') }}">
                <a href="{{ route('admin.builder') }}" class="side-nav-link {{ isActiveRoute(['builders']) }}">
                    <i class="uil-constructor"></i>
                    <span> Builders </span>
                </a>
            </li>

            <li class="side-nav-item {{ isActiveRouteMain('project') }}">
                <a href="{{ route('admin.project') }}" class="side-nav-link {{ isActiveRoute(['project']) }}">
                    <i class="uil-building"></i>
                    <span> Projects </span>
                </a>
            </li>

            <li class="side-nav-item {{ isActiveRouteMain('amenity') }}">
                <a href="{{ route('admin.amenity') }}" class="side-nav-link {{ isActiveRoute(['amenity']) }}">
                    <i class="uil-cog"></i>
                    <span> Amenities </span>
                </a>
            </li>

            <li class="side-nav-item {{ isActiveRouteMain('locality') }}">
                <a href="{{ route('admin.locality') }}" class="side-nav-link {{ isActiveRoute(['locality']) }}">
                    <i class="uil-map"></i>
                    <span> Localities </span>
                </a>
            </li>

            <li class="side-nav-item {{ isActiveRouteMain(['faqs']) }}">
                <a data-bs-toggle="collapse" href="#faqs_menu" aria-expanded="false" aria-controls="faqs_menu"
                    class="side-nav-link collapsed">
                    <i class="uil-copy-alt"></i>
                    <span> CMS Pages </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="faqs_menu" style="">
                    <ul class="side-nav-second-level">
                        <li>
                            <a class="{{ isActiveRoute(['news']) }}" href="{{ route('admin.news') }}">News</a>
                        </li>
                        <li>
                            <a class="{{ isActiveRoute(['faq']) }}" href="{{ route('admin.faq') }}">FAQs</a>
                        </li>
                        <li>
                            <a class="{{ isActiveRoute(['page']) }}"
                                href="{{ route('admin.page', ['terms-condition']) }}">Terms and Conditions</a>
                        </li>
                        <li>
                            <a class="{{ isActiveRoute(['page']) }}"
                                href="{{ route('admin.page', ['privacy-policy']) }}">Privacy Policy</a>
                        </li>
                        <li>
                            <a class="{{ isActiveRoute(['page']) }}"
                                href="{{ route('admin.page', ['about-us']) }}">About Us</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item {{ isActiveRouteMain(['setting', 'city', 'location']) }}">
                <a data-bs-toggle="collapse" href="#setting_menu" aria-expanded="false" aria-controls="setting_menu"
                    class="side-nav-link collapsed">
                    <i class="mdi mdi-cog"></i>
                    <span> Settings </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="setting_menu" style="">
                    <ul class="side-nav-second-level">
                        <li>
                            <a class="{{ isActiveRoute(['setting']) }}" href="{{ route('admin.setting') }}">General
                                Settings</a>
                        </li>
                        <li>
                            <a class="{{ isActiveRoute(['city']) }}" href="{{ route('admin.city') }}">Cities</a>
                        </li>
                        <li>
                            <a class="{{ isActiveRoute(['location']) }}"
                                href="{{ route('admin.location') }}">Locations</a>
                        </li>
                        <li>
                            <a class="{{ isActiveRoute(['project-badge']) }}"
                                href="{{ route('admin.project-badge') }}">Project Badges</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>

        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
