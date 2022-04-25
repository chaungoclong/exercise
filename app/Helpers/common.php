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
    /**
     * [displayErrorInput description]
     * @param  [type] $inputName  [description]
     * @param  [type] $classError [description]
     * @param  [type] $errors     [description]
     * @return [type]             [description]
     */
    function displayErrorInput($inputName, $classError, $errors)
    {
        if ($errors->has($inputName)) {
            return $classError;
        }

        return '';
    }
}

if (!function_exists('formatName')) {
    /**
     * format name
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    function formatName($name): string
    {
        return implode(
            ' ',
            array_map(
                fn($word) => ucfirst($word),
                explode(
                    ' ',
                    preg_replace('/\s+/', ' ', strtolower($name))
                )
            )
        );
    }
}
