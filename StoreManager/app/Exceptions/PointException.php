<?php

namespace App\Exceptions;

use Exception;

/**
 * ポイント残高が不足していたら例外を投げる
 */
class PointException extends Exception
{
    public function __construct()
    {
        parent::__construct();
    }
}
