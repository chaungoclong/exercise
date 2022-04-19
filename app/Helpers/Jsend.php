<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

/**
 * field respone
 * [
 *      'status' => 'success|fail|error',
 *      'message' => 'some message',
 *      'data' => 'data respone',
 *      'code' => [200, 400, 500,...]
 * ]
 */
class Jsend
{
    // status response
    private const STATUS_RESPONSE_SUCCESS = 'success';
    private const STATUS_RESPONSE_FAIL    = 'fail';
    private const STATUS_RESPONSE_ERROR   = 'error';

    /**
     * [sendSuccess description]
     * @param  string  $message [description]
     * @param  array   $data    [description]
     * @param  integer $code    [description]
     * @return [type]           [description]
     */
    public function sendSuccess(
        $message = '',
        $data = [],
        $code = 200
    ): JsonResponse {
        $response = [
            'status'  => self::STATUS_RESPONSE_SUCCESS,
            'message' => $message,
            'data'    => $data
        ];
        
        $code = ($code >= 400 || $code < 200) ? 200 : $code;

        return response()->json($response, $code);
    }

    /**
     * [sendFail description]
     * @param  string  $message [description]
     * @param  array   $data    [description]
     * @param  integer $code    [description]
     * @return [type]           [description]
     */
    public function sendFail(
        $message = '',
        $data = [],
        $code = 422
    ): JsonResponse {
        $response = [
            'status'  => self::STATUS_RESPONSE_FAIL,
            'message' => $message,
            'data'    => $data
        ];

        return response()->json($response, $code);
    }

    /**
     * [sendError description]
     * @param  string  $message [description]
     * @param  array   $data    [description]
     * @param  integer $code    [description]
     * @return [type]           [description]
     */
    public function sendError(
        $message = '',
        $data = [],
        $code = 500
    ): JsonResponse {
        $response = [
            'status'  => self::STATUS_RESPONSE_ERROR,
            'message' => $message,
            'data'    => $data
        ];

        return response()->json($response, $code);
    }
}
