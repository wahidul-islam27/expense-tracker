<?php

declare(strict_types = 1);

namespace App\Dto;

class Response {
    public static function success($response = [], $message = 'SUCCESS') {
        return [
            'error' => false,
            'message' => $message,
            'response' => $response
        ];
    }

    public static function error($message) {
        return [
            'error' => true,
            'message' => $message,
            'response' => NULL
        ];
    }
}