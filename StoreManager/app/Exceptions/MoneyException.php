<?php

namespace App\Exceptions;

use Exception;

/**
 * 支払い金額が正しくなければ例外を投げる
 */
class MoneyException extends Exception
{
    public function __construct()
    {
        parent::__construct();
    }
}
