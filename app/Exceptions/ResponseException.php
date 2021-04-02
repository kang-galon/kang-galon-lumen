<?php

namespace App\Exceptions;

use Exception;

class ResponseException extends Exception
{
    public $status = 400;
}
