<?php declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Service;

use Lendable\Interview\Interpolation\Exception\InterpolateException;
use Lendable\Interview\Interpolation\Model\Term;
use Money\Money;

class LinearInterpolation implements InterpolationInterface
{
    public function execute(Money $amount, Term $term): Money
    {
        if ($amount->lessThan($term->getLowestAmount()) || $amount->greaterThan($term->getHighestAmount())) {
            throw new InterpolateException('Amount not valid');
        }

        $lowerAmount = $term->getNearestLowerAmount($amount);
        $upperAmount = $term->getNearestUpperAmount($amount);

        if ($lowerAmount->equals($upperAmount)) {
            return $term->getFee($amount);
        }

        $lowerFee = $term->getFee($lowerAmount);
        $upperFee = $term->getFee($upperAmount);

        // dy/dx
        // slope = (y1 - y0) / (x1 - x0)
        // y = y0 + slope * (x - x0)
        $slope = $upperFee->subtract($lowerFee)->divide($upperAmount->subtract($lowerAmount)->getAmount() / 100);
        return $lowerFee->add($slope->multiply($amount->subtract($lowerAmount)->getAmount() / 100));
    }
}