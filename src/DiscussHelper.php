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
