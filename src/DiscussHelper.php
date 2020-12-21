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
