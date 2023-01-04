<?php


if (!function_exists('translateInterTerm')) {
    /**
     * Return the path to public dir
     *
     * @param $text
     * @return string
     */
    function translateInterTerm($text)
    {
//        return \App\Core\Base\Services\TranslateTextService::execute($text);
        return $text;
    }
}

if (!function_exists('translateText')) {
    /**
     * Return the path to public dir
     *
     * @param $text
     * @return string
     */
    function translateText($text)
    {
//        return \App\Core\Base\Services\TranslateTextService::execute($text);
        return $text;
    }
}
