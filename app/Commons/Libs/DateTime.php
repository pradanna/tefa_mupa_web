<?php

namespace App\Commons\Libs;

class DateTime
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
