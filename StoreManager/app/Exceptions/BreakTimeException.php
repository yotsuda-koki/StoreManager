<?php

namespace App\Exceptions;

use Exception;

/**
 * 休憩時間が正しくなければ例外を投げる
 */
class BreakTimeException extends Exception
{
    public function __construct()
    {
        parent::__construct();
    }
}
