<?php

namespace App\Commons\Libs;

class Datetime
{
    /**
     * Return current datetime in 'YmdHis' format.
     *
     * @return string
     */
    public static function getNowYmdHis(): string
    {
        return now()->format('YmdHis');
    }
}
