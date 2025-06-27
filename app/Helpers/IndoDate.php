<?php

if (!function_exists('indo_date')) {
    function indo_date($date)
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $d = \Carbon\Carbon::parse($date);
        return $d->format('d') . ' ' . $months[(int)$d->format('m')] . ' ' . $d->format('Y');
    }
}
