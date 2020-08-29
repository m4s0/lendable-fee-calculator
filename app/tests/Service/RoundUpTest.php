<?php declare(strict_types=1);

namespace Lendable\Interview\Interpolation\Tests\Service;

use Lendable\Interview\Interpolation\Service\RoundUp;
use Money\Money;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class RoundUpTest extends TestCase
{
    /** @test */
    public function should_round_up(): void
    {
        $divisor = Money::GBP(500);

        $roundUp = new RoundUp();

        Assert::assertEquals(Money::GBP(20399), $roundUp->execute(Money::GBP(20000), Money::GBP(275101), $divisor));
        Assert::assertEquals(Money::GBP(30350), $roundUp->execute(Money::GBP(30000), Money::GBP(275150), $divisor));
        Assert::assertEquals(Money::GBP(20303), $roundUp->execute(Money::GBP(20000), Money::GBP(275197), $divisor));
        Assert::assertEquals(Money::GBP(20295), $roundUp->execute(Money::GBP(20000), Money::GBP(275205), $divisor));
        Assert::assertEquals(Money::GBP(12492), $roundUp->execute(Money::GBP(12000), Money::GBP(296008), $divisor));
    }
}