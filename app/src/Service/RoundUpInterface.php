<?php declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Service;

use Money\Money;

interface RoundUpInterface
{
    public function execute(Money $fee, Money $amount, Money $divisor): Money;
}