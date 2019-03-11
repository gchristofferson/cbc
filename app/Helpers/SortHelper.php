<?php

namespace App\Helpers;


class SortHelper {
    public static function sortByExpire($a, $b) {
        $a = strtotime($a['subscription_expire_date']);
        $b = strtotime($b['subscription_expire_date']);
        return $b - $a;
    }
}

