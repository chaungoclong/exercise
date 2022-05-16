<?php

use App\Exceptions\NoPermissionException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

if (!function_exists('trimStringArray')) {
    /**
     * [trimStringArray description]
     * @param  array  $array [description]
     * @return [type]        [description]
     */
    function trimStringArray($array = []): array
    {
        return array_map(fn ($value) => trim($value), $array);
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
                fn ($word) => ucfirst($word),
                explode(
                    ' ',
                    preg_replace('/\s+/', ' ', strtolower($name))
                )
            )
        );
    }
}


if (!function_exists('jsend_error')) {
    /**
     * Send Error Response
     *
     * @param string $message
     * @param array|null $data
     * @param string|null $errorCode
     * @param integer $statusCode
     * @param array $headers
     * @return JsonResponse
     */
    function jsend_error(
        string $message,
        ?array $data = null,
        ?string $errorCode = null,
        int $statusCode = 500,
        array $headers = []
    ): JsonResponse {
        $response = [
            'status' => 'error',
            'message' => $message
        ];

        !is_null($data) && $response['data'] = $data;
        !is_null($errorCode) && $response['code'] = $errorCode;

        return response()->json($response, $statusCode, $headers);
    }
}

/**
 * send response fail
 */
if (!function_exists('jsend_fail')) {
    /**
     * Send Fail Response
     *
     * @param array|null $data
     * @param string $message
     * @param integer $statusCode
     * @param array $headers
     * @return JsonResponse
     */
    function jsend_fail(
        ?array $data = null,
        string $message = '',
        int $statusCode = 400,
        array $headers = []
    ): JsonResponse {
        $response = [
            'status' => 'fail',
            'message' => $message,
            'data' => $data
        ];

        return response()->json($response, $statusCode, $headers);
    }
}


if (!function_exists('jsend_success')) {
    /**
     * Send Success Response
     *
     * @param array|null $data
     * @param string $message
     * @param integer $statusCode
     * @param array $headers
     * @return JsonResponse
     */
    function jsend_success(
        ?array $data = null,
        string $message = '',
        int $statusCode = 200,
        array $headers = []
    ): JsonResponse {
        $response = [
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ];

        return response()->json($response, $statusCode, $headers);
    }
}

if (!function_exists('can')) {
    /**
     * Check User Permission
     *
     * @param string $permission
     * @return boolean
     */
    function can(string $permission)
    {
        if (!Gate::allows(trim($permission))) {
            throw new NoPermissionException(
                __('Sorry! You are not authorized to perform this action.')
            );
        }
    }
}

if (!function_exists('toSelect2')) {
    /**
     * Convert Model Or Collection to Select2 Options
     *
     * @param Model|Collection $data
     * @param string $id
     * @param string $text
     * @return array
     */
    function toSelect2(
        Model|Collection $data,
        $id = 'id',
        $text = 'text'
    ): array {
        $options = [];

        // If $data is not Model Or Collection return []
        if (!($data instanceof Model || $data instanceof Collection)) {
            return $options;
        }

        // Convert $data to Collection
        $data = ($data instanceof Collection) ? $data : collect([$data]);

        foreach ($data as $model) {
            // If $model has all attributes add it to $options
            if (isset($model->$id, $model->$text)) {
                $options[] = [
                    'id' => $model->$id,
                    'text' => $model->$text
                ];
            }
        }

        return $options;
    }
}


if (!function_exists('pluckField')) {
    /**
     * Pluck field in Collection recursive
     *
     * @param [type] $object
     * @param [type] $field
     * @return array
     */
    function pluckField($object, $field): array
    {
        $results = [];

        $object = collect($object)->toArray();

        array_walk_recursive(
            $object,
            function ($value, $key) use (&$results, $field) {
                ($key === $field) && $results[] = $value;
            }
        );

        return array_unique($results);
    }
}
