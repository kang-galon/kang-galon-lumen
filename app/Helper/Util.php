<?php

namespace App\Helper;

class Util
{
    static function distance($lat1, $lon1, $lat2, $lon2)
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $km = $miles * 1.609344;

            return $km;
        }
    }

    /**
     * 1 Menunggu persetujuan, 2 Mengambil galon, 3 Mengantar galon, 4 Selesai, 5 Transaksi dibatalkan
     */
    static function transactionStatus(int $status): string
    {
        $statusDescription = '';
        if ($status == 1) {
            $statusDescription = 'Menunggu persetujuan';
        } else if ($status == 2) {
            $statusDescription = 'Mengambil galon';
        } else if ($status == 3) {
            $statusDescription = 'Mengantar galon';
        } else if ($status == 4) {
            $statusDescription = 'Transaksi selesai';
        } else if ($status == 5) {
            $statusDescription = 'Transaksi dibatalkan';
        }

        return $statusDescription;
    }
}
