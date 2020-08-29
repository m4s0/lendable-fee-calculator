<?php declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Service;

use Lendable\Interview\Interpolation\Model\Term;
use Money\Money;

interface InterpolationInterface
{
    public function execute(Money $amount, Term $term): Money;
}