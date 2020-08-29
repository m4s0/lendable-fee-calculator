<?php declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Service;

use Exception;
use Lendable\Interview\Interpolation\Exception\FeeCalculatorException;
use Lendable\Interview\Interpolation\Exception\TermAmountNotFoundException;
use Lendable\Interview\Interpolation\Model\LoanApplication;
use Money\Money;

class FeeCalculator implements FeeCalculatorInterface
{
    public const UPPER_LIMIT = 2000000;
    public const LOWER_LIMIT = 100000;
    public const STEP = 500;

    private InterpolationInterface $interpolation;
    private RoundUpInterface $roundUp;

    public function __construct(InterpolationInterface $interpolation, RoundUpInterface $roundUp)
    {
        $this->interpolation = $interpolation;
        $this->roundUp = $roundUp;
    }

    public function calculate(LoanApplication $application): Money
    {
        $amount = (int)$application->amount()->getAmount();
        if ($amount < self::LOWER_LIMIT || $amount > self::UPPER_LIMIT) {
            throw new FeeCalculatorException('Amount not valid');
        }

        $term = $application->term();
        try {
            $fee = $term->getFee($application->amount());
        } catch (TermAmountNotFoundException $e) {
            $fee = $this->interpolation->execute($application->amount(), $term);
        } catch (Exception $e) {
            throw new FeeCalculatorException('Cannot calculate fee');
        }

        return $this->roundUp->execute(
            $fee,
            $application->amount(),
            new Money(self::STEP, $application->amount()->getCurrency())
        );
    }
}