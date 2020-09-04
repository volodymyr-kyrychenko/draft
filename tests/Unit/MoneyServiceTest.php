<?php

namespace Tests\Unit;

use App\Http\Services\MoneyService;
use PHPUnit\Framework\TestCase;

class MoneyServiceTest extends TestCase
{
    /**
     * @dataProvider bonusAmountProvider
     * @return void
     */
    public function testGetBonusAmount(float $convertingRatio, float $amount, float $bonusAmount)
    {
        $moneyService = new MoneyService();
        $realBonusAmount = $moneyService->getBonusAmount($convertingRatio, $amount);

        $this->assertEquals($bonusAmount, $realBonusAmount);
    }

    public function bonusAmountProvider()
    {
        return [
            '0' => [2.02, 2.02, 4.08],
            '1' => [-2.13, 3.15, 0.00],
            '2' => [2.13, -3.15, 0.00],
            '3' => [-2.13, -3.15, 0.00],
            '4' => [0.0, 3.15, 0.00],
            '5' => [2.13, 0, 0.00],
            '6' => [0.5, 4.04, 2.02]
        ];
    }
}
