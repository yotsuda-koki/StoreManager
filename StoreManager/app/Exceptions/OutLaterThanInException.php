<?php

namespace App\Exceptions;

use Exception;

/**
 * 退勤時間より出勤時間のほうが遅ければ例外を投げる
 */
class OutLaterThanInException extends Exception
{
    public function __construct()
    {
        parent::__construct();
    }
}
