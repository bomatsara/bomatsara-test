<?php

namespace BomatsaraTest\Utilities;

class Helpers
{
    public static function random_date(): string
    {
        $start = strtotime("-1 month");
        $end = strtotime("now");

        $randomTimestamp = mt_rand($start, $end);
        return date("Y-m-d H:i:s", $randomTimestamp);
    }
}