<?php declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Model;

use Lendable\Interview\Interpolation\Exception\TermAmountNotFoundException;
use Lendable\Interview\Interpolation\Exception\TermException;
use Money\Currency;
use Money\Money;

class Term
{
    private Currency $currency;
    private array $breakpoints;
    private Money $lowestAmount;
    private Money $highestAmount;

    public function __construct(Currency $currency)
    {
        $this->currency = $currency;
        $this->lowestAmount = new Money(0, $this->currency);
        $this->highestAmount = new Money(0, $this->currency);
    }

    public function addBreakpoint(Money $loanAmount, Money $fee): void
    {
        if (!$this->currency->equals($loanAmount->getCurrency()) || !$this->currency->equals($fee->getCurrency())) {
            throw new TermException('Currency is not valid');
        }

        $this->breakpoints[(int)$loanAmount->getAmount()] = (int)$fee->getAmount();

        if ($loanAmount->lessThan($this->lowestAmount) || $this->lowestAmount->isZero()) {
            $this->lowestAmount = $loanAmount;
        }
        if ($loanAmount->greaterThan($this->highestAmount) || $this->highestAmount->isZero()) {
            $this->highestAmount = $loanAmount;
        }
    }

    public function getLowestAmount(): Money
    {
        return $this->lowestAmount;
    }

    public function getHighestAmount(): Money
    {
        return $this->highestAmount;
    }

    public function getFee(Money $amount): Money
    {
        if (!$this->currency->equals($amount->getCurrency())) {
            throw new TermException('Currency is not valid');
        }

        $breakpoint = $amount->getAmount();
        if (array_key_exists($breakpoint, $this->breakpoints)) {
            return new Money($this->breakpoints[$breakpoint], $this->currency);
        }

        if ($amount->lessThan($this->lowestAmount) || $amount->greaterThan($this->highestAmount)) {
            throw new TermException('Amount is not valid');
        }

        throw new TermAmountNotFoundException('Amount not found');
    }
}