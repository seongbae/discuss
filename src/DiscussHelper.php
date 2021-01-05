<?php

if (!function_exists('limitText')) {

    function limitText($text, $size = 200)
    {
        if (strlen($text) > $size)
            return substr($text, 0, $size) . '...';
        else
            return $text;
    }
}

if (!function_exists('getUserImage')) {

    function getUserImage($user, $imageField, $imagePath, $defaultImage)
    {
        if ($imageField == "")
            return  $defaultImage;
        else
            return $imagePath.$user->$imageField;
    }
}

if (!function_exists('getYoutubeEmbedUrl')) {

    function getYoutubeEmbedUrl($url)
    {
        return preg_replace(
            "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
            "<div style='position: relative;padding-bottom: 56.25%;padding-top: 25px;height: 0;'><iframe src=\"//www.youtube.com/embed/$2\" allowfullscreen style='position: absolute;top: 0;left: 0;width: 100%;height: 100%;'></iframe></div>",
            $url
        );
    }
}

if (!function_exists('processThreadBody')) {

    function processThreadBody($string)
    {
        if (strpos($string, 'youtube.com'))
            $string = preg_replace(
                "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
                "<div style='position: relative;padding-bottom: 56.25%;padding-top: 25px;height: 0;'><iframe src=\"//www.youtube.com/embed/$2\" allowfullscreen style='position: absolute;top: 0;left: 0;width: 100%;height: 100%;'></iframe></div>",
                $string
            );

        return nl2br($string);
    }
}


