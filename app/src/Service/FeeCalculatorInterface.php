<?php declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Service;

use Lendable\Interview\Interpolation\Model\LoanApplication;
use Money\Money;

interface FeeCalculatorInterface
{
    /**
     * @param LoanApplication $application
     *
     * @return Money The calculated total fee.
     */
    public function calculate(LoanApplication $application): Money;
}
