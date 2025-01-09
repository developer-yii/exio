<?php
if (! function_exists('pr')) {
    function pr($data)
    {
        echo "<pre>";
        print_r($data);
        exit();
    }
}
if (! function_exists('cacheclear')) {
    function cacheclear()
    {
        return time();
    }
}
if (! function_exists('getDateFormateView')) {
    function getDateFormateView($date)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('d-m-Y');
    }
}
if (! function_exists('addPageJsLink')) {
    function addPageJsLink($link)
    {
        return asset('backend/js/pages') . "/" . $link . '?' . time();
    }
}
if (!function_exists('isActiveRouteMain')) {
    function isActiveRouteMain($routeNames = "")
    {
        if ($routeNames) {
            if (is_array($routeNames)) {
                foreach ($routeNames as $routeName) {
                    if (request()->routeIs($routeName)) {
                        return 'menuitem-active';
                    }
                }
            } else {
                if (request()->routeIs($routeNames)) {
                    return 'menuitem-active';
                }
            }
        }
        return '';
    }
}
if (!function_exists('isActiveRoute')) {
    function isActiveRoute($routeNames = "")
    {
        if ($routeNames) {
            if (is_array($routeNames)) {
                foreach ($routeNames as $routeName) {
                    if (request()->routeIs($routeName)) {
                        return 'active';
                    }
                }
            } else {
                if (request()->routeIs($routeNames)) {
                    return 'active';
                }
            }
        }
        return '';
    }
}
if (!function_exists('isSuperAdmin')) {
    function isSuperAdmin()
    {
        return (auth()->check() && auth()->user()->role_type) ? true : false;
    }
}
