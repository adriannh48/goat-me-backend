<?php 

namespace App\Exceptions;

use Exception;

class ApiException extends Exception {
    public $status = false;
    public $err = "API_EXCEPTION";
    public $statusCode = 0;
    public $statusHttp = 500;
}