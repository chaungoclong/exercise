<?php

if (!function_exists('trimStringArray')) {
    /**
     * [trimStringArray description]
     * @param  array  $array [description]
     * @return [type]        [description]
     */
    function trimStringArray($array = []): array
    {
        return array_map(fn($value) => trim($value), $array);
    }
}

if (!function_exists('displayErrorInput')) {
    function displayErrorInput($inputName, $classError, $errors)
    {
        if ($errors->has($inputName)) {
            return $classError;
        }

        return '';
    }
}
