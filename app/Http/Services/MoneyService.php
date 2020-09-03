<?php

declare(strict_types = 1);

namespace App\Http\Services;

use App\Http\Enums\PrizeTypeEnum;
use App\Money;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\MoneyRreservation;

class MoneyService implements PrizeServiceInterface
{
    public function checkPrize(): array
    {
        $moneyRreservation = MoneyRreservation::where('user_id', Auth::id())
            ->where('status', 0)
            ->first();

        if (empty($moneyRreservation))
            return [];

        return [
            'name' => 'Money',
            'type' => PrizeTypeEnum::MONEY,
            'id' => $moneyRreservation->id,
            'amount' => $moneyRreservation->amount
        ];
    }

    public function getPrize(): array
    {
        // todo wrap up in a transaction

        $money = Money::first();

        if ($money->current_amount <= 0)
            return [];

        $randomAmount = random_int($money->min_amount, $money->max_amount);

        if ($randomAmount > $money->current_amount)
            $randomAmount = $money->current_amount;

        $money->current_amount -= $randomAmount;
        $money->save();

        $moneyRreservation = MoneyRreservation::create([
            'user_id' => Auth::id(),
            'amount' => $randomAmount
        ]);

        return [
            'name' => 'Money',
            'type' => PrizeTypeEnum::MONEY,
            'id' => $moneyRreservation->id,
            'amount' => $randomAmount
        ];
    }

    public function receive(): void
    {
        MoneyRreservation::where('id', request('id'))
            ->update(['status' => 2]);
    }

    public function refuse(): void
    {
        $moneyRreservation = MoneyRreservation::find(request('id'));
        $moneyRreservation->status = 1;
        $moneyRreservation->save();

        Money::where('id', 1)
        ->increment('current_amount', $moneyRreservation->amount);
    }
}
