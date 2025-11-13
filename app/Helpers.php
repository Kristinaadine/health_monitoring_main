<?php
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

if (!function_exists('locale_route')) {
    function locale_route($name, $parameters = [], $absolute = true)
    {
        if (!is_array($parameters)) {
            $parameters = [$parameters];
        }

        return route($name, array_merge(['locale' => app()->getLocale()], $parameters), $absolute);
    }
}

if (!function_exists('autoTrans')) {
    function autoTrans($text)
    {
        $key = Str::slug($text, '_');
        $key = preg_replace('/[^A-Za-z0-9_]/', '', $key);

        if (Lang::has("general.$key")) {
            return __("general.$key");
        }
        return $text;
    }
}