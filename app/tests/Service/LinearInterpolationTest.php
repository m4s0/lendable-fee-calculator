<?php declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Tests\Service;

use Lendable\Interview\Interpolation\Exception\InterpolateException;
use Lendable\Interview\Interpolation\Model\Term;
use Lendable\Interview\Interpolation\Service\LinearInterpolation;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class LinearInterpolationTest extends TestCase
{
    /** @test */
    public function should_get_an_exception_if_amount_is_not_valid(): void
    {
        $term = new Term(new Currency('GBP'));
        $term->addBreakpoint(Money::GBP(100000), Money::GBP(7000));
        $term->addBreakpoint(Money::GBP(200000), Money::GBP(10000));

        $interpolation = new LinearInterpolation();

        $this->expectExceptionMessage('Amount not valid');
        $this->expectException(InterpolateException::class);

        $interpolation->execute(Money::GBP(99999), $term);
        $interpolation->execute(Money::GBP(200001), $term);
    }

    /** @test */
    public function should_interpolate(): void
    {
        $term = new Term(new Currency('GBP'));
        $term->addBreakpoint(Money::GBP(100000), Money::GBP(7000));
        $term->addBreakpoint(Money::GBP(200000), Money::GBP(10000));
        $term->addBreakpoint(Money::GBP(300000), Money::GBP(12000));
        $term->addBreakpoint(Money::GBP(400000), Money::GBP(16000));

        $interpolation = new LinearInterpolation();

        Assert::assertEquals(Money::GBP(10000), $interpolation->execute(Money::GBP(200000), $term));
        Assert::assertEquals(Money::GBP(10500), $interpolation->execute(Money::GBP(225000), $term));
        Assert::assertEquals(Money::GBP(11000), $interpolation->execute(Money::GBP(250000), $term));
        Assert::assertEquals(Money::GBP(11500), $interpolation->execute(Money::GBP(275000), $term));
        Assert::assertEquals(Money::GBP(12000), $interpolation->execute(Money::GBP(300000), $term));
        Assert::assertEquals(Money::GBP(13000), $interpolation->execute(Money::GBP(325000), $term));
        Assert::assertEquals(Money::GBP(14000), $interpolation->execute(Money::GBP(350000), $term));
        Assert::assertEquals(Money::GBP(15000), $interpolation->execute(Money::GBP(375000), $term));
        Assert::assertEquals(Money::GBP(16000), $interpolation->execute(Money::GBP(400000), $term));
    }
}
