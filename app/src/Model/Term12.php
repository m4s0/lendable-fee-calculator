<?php declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Model;

use Money\Currency;
use Money\Money;

class Term12
{
    public static function create(): Term
    {
        $breakpoints = [
            100000  => 5000,
            200000  => 9000,
            300000  => 9000,
            400000  => 11500,
            500000  => 10000,
            600000  => 12000,
            700000  => 14000,
            800000  => 16000,
            900000  => 18000,
            1000000 => 20000,
            1100000 => 22000,
            1200000 => 24000,
            1300000 => 26000,
            1400000 => 28000,
            1500000 => 30000,
            1600000 => 32000,
            1700000 => 34000,
            1800000 => 36000,
            1900000 => 38000,
            2000000 => 40000
        ];

        $currency = new Currency('GBP');
        $term = new Term($currency);

        foreach ($breakpoints as $amount => $fee) {
            $term->addBreakpoint(
                new Money((int)$amount, $currency),
                new Money($fee, $currency)
            );
        }

        return $term;
    }
}