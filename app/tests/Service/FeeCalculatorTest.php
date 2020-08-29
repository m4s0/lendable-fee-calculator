<?php declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Tests\Service;

use Lendable\Interview\Interpolation\Exception\FeeCalculatorException;
use Lendable\Interview\Interpolation\Model\LoanApplication;
use Lendable\Interview\Interpolation\Model\Term;
use Lendable\Interview\Interpolation\Service\FeeCalculator;
use Lendable\Interview\Interpolation\Service\InterpolationInterface;
use Lendable\Interview\Interpolation\Service\RoundUpInterface;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class FeeCalculatorTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    public function should_get_an_exception_if_amount_is_less_than_lower_limit(): void
    {
        $term = new Term(new Currency('GBP'));
        $term->addBreakpoint(Money::GBP(100000), Money::GBP(5000));
        $term->addBreakpoint(Money::GBP(200000), Money::GBP(10000));

        $calculator = new FeeCalculator($this->prophesize(InterpolationInterface::class)->reveal(), $this->prophesize(RoundUpInterface::class)->reveal());
        $application = new LoanApplication($term, Money::GBP(99999));

        $this->expectException(FeeCalculatorException::class);
        $this->expectExceptionMessage('Amount not valid');

        $calculator->calculate($application);
    }

    /** @test */
    public function should_get_an_exception_if_amount_is_greater_than_upper_limit(): void
    {
        $term = new Term(new Currency('GBP'));
        $term->addBreakpoint(Money::GBP(100000), Money::GBP(5000));
        $term->addBreakpoint(Money::GBP(2000000), Money::GBP(10000));

        $calculator = new FeeCalculator($this->prophesize(InterpolationInterface::class)->reveal(), $this->prophesize(RoundUpInterface::class)->reveal());
        $application = new LoanApplication($term, Money::GBP(2000001));

        $this->expectException(FeeCalculatorException::class);
        $this->expectExceptionMessage('Amount not valid');

        $calculator->calculate($application);
    }

    /** @test */
    public function should_calculate_without_interpolating(): void
    {
        $interpolate = $this->prophesize(InterpolationInterface::class);
        $roundUp = $this->prophesize(RoundUpInterface::class);
        $calculator = new FeeCalculator($interpolate->reveal(), $roundUp->reveal());

        $currency = new Currency('GBP');
        $term = new Term($currency);
        $term->addBreakpoint(new Money(100000, $currency), new Money(7000, $currency));
        $term->addBreakpoint(new Money(200000, $currency), new Money(10000, $currency));

        $application = new LoanApplication($term, Money::GBP(100000));
        $roundUp->execute(Money::GBP(7000), Money::GBP(100000), Money::GBP(500))->shouldBeCalled()->willReturn(Money::GBP(7000));

        Assert::assertEquals(Money::GBP(7000), $calculator->calculate($application));

        $application = new LoanApplication($term, Money::GBP(200000));
        $roundUp->execute(Money::GBP(10000), Money::GBP(200000), Money::GBP(500))->shouldBeCalled()->willReturn(Money::GBP(10000));

        Assert::assertEquals(Money::GBP(10000), $calculator->calculate($application));
    }

    /** @test */
    public function should_calculate_interpolating(): void
    {
        $interpolate = $this->prophesize(InterpolationInterface::class);
        $roundUp = $this->prophesize(RoundUpInterface::class);
        $calculator = new FeeCalculator($interpolate->reveal(), $roundUp->reveal());

        $currency = new Currency('GBP');
        $term = new Term($currency);
        $term->addBreakpoint(new Money(100000, $currency), new Money(7000, $currency));
        $term->addBreakpoint(new Money(200000, $currency), new Money(10000, $currency));

        $application = new LoanApplication($term, Money::GBP(150000));
        $interpolate->execute(Money::GBP(150000), $term)->shouldBeCalled()->willReturn(Money::GBP(10000));
        $roundUp->execute(Money::GBP(10000), Money::GBP(150000), Money::GBP(500))->shouldBeCalled()->willReturn(Money::GBP(10000));

        Assert::assertEquals(Money::GBP(10000), $calculator->calculate($application));
    }
}
