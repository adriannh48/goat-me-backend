<?php 
namespace App\Exceptions\General;

use App\Exceptions\ApiException;

class ValidatorException extends ApiException {
    public $status = false;
    public $err = "MALFORMED_REQUEST_FIELDS";
    public $statusCode = 0;
    public $statusHttp = 400;
}