<?php

namespace App\Factory;

use App\Entity\Earnings;

class EarningsFactory
{
    /**
     * @return Earnings
     */
    public static function create(): Earnings
    {
        return new Earnings();
    }
}