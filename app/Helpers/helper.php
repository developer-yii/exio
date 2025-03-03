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
if (! function_exists('frontendPageJsLink')) {
    function frontendPageJsLink($link)
    {
        return asset('frontend/assest/js/pages') . "/" . $link . '?' . time();
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
if (! function_exists('getSettingFromDb')) {
    function getSettingFromDb($key, $default = null)
    {
        $setting = \App\Models\Setting::where('setting_key', $key)->first();
        return $setting ? $setting->setting_value : $default;
    }
}
if (!function_exists('getSetting')) {
    function getSetting($option_key, $default = '')
    {
        $settings = config('settings');
        return $settings[$option_key] ?? $default;
    }
}

if (!function_exists('formatChar')) {
    function formatChar($value, $length = 35)
    {
        // Return empty string if value is null or empty
        if (empty($value)) {
            return '';
        }

        // Convert to string if not already
        $value = (string) $value;

        // Get string length
        $strLength = mb_strlen($value);

        // Return original string if shorter than max length
        if ($strLength <= $length) {
            return $value;
        }

        // Trim string and add ellipsis
        return mb_substr($value, 0, $length) . '...';
    }
}
