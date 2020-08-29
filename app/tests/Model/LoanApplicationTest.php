<?php declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Tests\Model;

use Lendable\Interview\Interpolation\Exception\LoanApplicationException;
use Lendable\Interview\Interpolation\Model\LoanApplication;
use Lendable\Interview\Interpolation\Model\Term;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class LoanApplicationTest extends TestCase
{
    /** @test */
    public function should_create_a_valid_loan_application(): void
    {
        $term = new Term(new Currency('GBP'));
        $application = new LoanApplication($term, Money::GBP(275000));

        Assert::assertEquals($term, $application->term());
        Assert::assertEquals(Money::GBP(275000), $application->amount());
    }

    /** @test */
    public function should_get_an_exception_if_amount_is_not_valid(): void
    {
        $this->expectException(LoanApplicationException::class);
        $this->expectErrorMessage('Amount not valid');

        $term = new Term(new Currency('GBP'));
        new LoanApplication($term, Money::GBP(-100));
    }
}
