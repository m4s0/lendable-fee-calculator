<?php declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Tests\Model;

use Lendable\Interview\Interpolation\Exception\TermAmountNotFoundException;
use Lendable\Interview\Interpolation\Exception\TermException;
use Lendable\Interview\Interpolation\Model\Term;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class TermTest extends TestCase
{
    /** @test */
    public function should_get_fee_from_amount(): void
    {
        $term = new Term(new Currency('GBP'));
        $term->addBreakpoint(Money::GBP(100000), Money::GBP(5000));
        $term->addBreakpoint(Money::GBP(200000), Money::GBP(10000));

        Assert::assertEquals(
            Money::GBP(5000),
            $term->getFee(Money::GBP(100000))
        );

        Assert::assertEquals(
            Money::GBP(10000),
            $term->getFee(Money::GBP(200000))
        );
    }

    /** @test */
    public function should_get_an_exception_if_cannot_get_fee_from_amount(): void
    {
        $term = new Term(new Currency('GBP'));
        $term->addBreakpoint(Money::GBP(100000), Money::GBP(5000));
        $term->addBreakpoint(Money::GBP(200000), Money::GBP(10000));

        $this->expectException(TermAmountNotFoundException::class);
        $this->expectExceptionMessage('Amount not found');

        $term->getFee(Money::GBP(110000));
    }

    /** @test */
    public function should_get_an_exception_if_amount_is_not_valid(): void
    {
        $term = new Term(new Currency('GBP'));
        $term->addBreakpoint(Money::GBP(100000), Money::GBP(5000));
        $term->addBreakpoint(Money::GBP(200000), Money::GBP(10000));

        $this->expectException(TermException::class);
        $this->expectExceptionMessage('Amount is not valid');

        $term->getFee(Money::GBP(99999));
        $term->getFee(Money::GBP(200001));
    }

    /** @test */
    public function should_get_an_exception_if_currency_is_not_valid(): void
    {
        $term = new Term(new Currency('GBP'));
        $term->addBreakpoint(Money::GBP(100000), Money::GBP(5000));
        $term->addBreakpoint(Money::GBP(200000), Money::GBP(10000));

        $this->expectException(TermException::class);
        $this->expectExceptionMessage('Currency is not valid');

        $term->getFee(Money::EUR(100000));
        $term->getFee(Money::EUR(100000));
    }

    /** @test */
    public function should_get_lowest_amount(): void
    {
        $term = new Term(new Currency('GBP'));
        $term->addBreakpoint(Money::GBP(100000), Money::GBP(5000));
        $term->addBreakpoint(Money::GBP(200000), Money::GBP(10000));

        Assert::assertEquals(Money::GBP(100000), $term->getLowestAmount());
    }

    /** @test */
    public function should_get_highest_amount(): void
    {
        $term = new Term(new Currency('GBP'));
        $term->addBreakpoint(Money::GBP(100000), Money::GBP(5000));
        $term->addBreakpoint(Money::GBP(200000), Money::GBP(10000));

        Assert::assertEquals(Money::GBP(200000), $term->getHighestAmount());
    }

    /** @test */
    public function should_get_nearest_lower_amount(): void
    {
        $term = new Term(new Currency('GBP'));
        $term->addBreakpoint(Money::GBP(100000), Money::GBP(5000));
        $term->addBreakpoint(Money::GBP(200000), Money::GBP(10000));

        Assert::assertEquals(Money::GBP(100000), $term->getNearestLowerAmount(Money::GBP(100000)));
        Assert::assertEquals(Money::GBP(100000), $term->getNearestLowerAmount(Money::GBP(100001)));
        Assert::assertEquals(Money::GBP(100000), $term->getNearestLowerAmount(Money::GBP(199999)));
    }

    /** @test */
    public function should_get_nearest_upper_amount(): void
    {
        $term = new Term(new Currency('GBP'));
        $term->addBreakpoint(Money::GBP(100000), Money::GBP(5000));
        $term->addBreakpoint(Money::GBP(200000), Money::GBP(10000));

        Assert::assertEquals(Money::GBP(200000), $term->getNearestUpperAmount(Money::GBP(200000)));
        Assert::assertEquals(Money::GBP(200000), $term->getNearestUpperAmount(Money::GBP(100001)));
        Assert::assertEquals(Money::GBP(200000), $term->getNearestUpperAmount(Money::GBP(199999)));
    }
}
