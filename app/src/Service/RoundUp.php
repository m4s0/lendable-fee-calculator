<?php declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Service;

use Money\Money;

class RoundUp implements RoundUpInterface
{
    public function execute(Money $fee, Money $amount, Money $divisor): Money
    {
        $remainder = $fee->add($amount)->mod($divisor);
        if ($remainder->getAmount() > 0) {
            return $fee->add($divisor->subtract($remainder));
        }

        return $fee;
    }
}