<?php

namespace App\Helpers;
//namespace : menenntukan lokasi folder dari file ini

//nama class == nama file
class ApiFormatter {
    // variable struktur data yang akan di tapilkan di response postman
    protected static $response = [
        "status" => NULL,
        "message" => NULL,
        "data" => NULL,
    ];

    public static function sendResponse($status = NULL, $message = NULL, $data = []) 
    {
        self::$response['status'] = $status;
        self::$response['message'] = $message;
        self::$response['data'] = $data;
        return response()->json(self::$response, self::$response['status']);
        // status : http status code (200,400,500)
        // message : desc http status code ('succes', 'bad request', 'server error')
        // data : hasil yang di ambil dari db
    }
}

?>