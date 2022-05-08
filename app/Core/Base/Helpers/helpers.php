<?php


if (!function_exists('translateText')) {
    /**
     * Return the path to public dir
     *
     * @param $text
     * @return string
     */
    function translateText($text)
    {
        return __($text);
    }
}
