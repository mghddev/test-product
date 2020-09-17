<?php
use Illuminate\Support\Str;

if (!function_exists('starts_with')) {

    /**
     * @param string $str
     * @param string|string[] $find
     * @return bool
     */
    function starts_with(string $str, $find)
    {
        return Str::startsWith($str, $find);
    }
}


if(! function_exists('endsWith'))
{
    /**
     * Determine if a given string ends with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    function endsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ((string) $needle === h_substr($haystack, -length($needle))) {
                return true;
            }
        }

        return false;
    }
}

/**
 * length function
 */
if(! function_exists('length'))
{
    /**
     * @param $value
     * @return int
     */
    function length($value)
    {
        return mb_strlen($value);
    }
}


/**
 * h_substr function
 */
if(! function_exists('h_substr'))
{
    /**
     * Returns the portion of string specified by the start and length parameters.
     *
     * @param  string  $string
     * @param  int  $start
     * @param  int|null  $length
     * @return string
     */
    function h_substr($string, $start, $length = null)
    {
        return mb_substr($string, $start, $length, 'UTF-8');
    }
}

if (!function_exists('str_contains')) {

    /**
     * @param string $str
     * @param string $find
     * @return bool
     */
    function str_contains(string $str, string $find)
    {
        return Str::contains($str, $find);
    }
}
