<?php

namespace App\Http\Resources;

use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;

class ApiResponse 
{
    public static function return($data = [], $error = [], $message = [], $statusCode = null)
    {
        $objResponse = new HttpResponse();
        return Response::json([
            'http_status_code'=> $statusCode ??  $objResponse->getStatusCode(),
            'data'            => $data,
            'error'           => $error,
            'message'          => $message,
        ]);
    }
}
