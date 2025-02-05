<?php

namespace App\Exceptions;

use Exception;

/**
 * 休憩終了より休憩開始のほうが遅ければ例外を投げる
 */
class EndLaterThanStartException extends Exception
{
    public function __construct()
    {
        parent::__construct();
    }
}
