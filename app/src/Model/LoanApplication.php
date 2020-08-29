<?php declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Model;

use Lendable\Interview\Interpolation\Exception\LoanApplicationException;
use Money\Money;

/**
 * A cut down version of a loan application containing
 * only the required properties for this test.
 */
class LoanApplication
{
    private Money $amount;
    private Term $term;

    public function __construct(Term $term, Money $amount)
    {
        if ($amount->isNegative()) {
            throw new LoanApplicationException('Amount not valid');
        }

        $this->term = $term;
        $this->amount = $amount;
    }

    /**
     * Term (loan duration) for this loan application.
     */
    public function term(): Term
    {
        return $this->term;
    }

    /**
     *
     * Amount requested for this loan application.
     */
    public function amount(): Money
    {
        return $this->amount;
    }
}
