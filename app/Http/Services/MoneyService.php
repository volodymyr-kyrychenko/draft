<?php

declare(strict_types = 1);

namespace App\Http\Services;

use App\Http\Enums\PrizeTypeEnum;
use App\Http\Enums\ReservationStatusEnum;
use App\Money;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\MoneyReservation;

class MoneyService implements PrizeServiceInterface
{
    /**
     * @return array
     */
    public function checkPrize(): array
    {
        $moneyReservation = MoneyReservation::where('user_id', Auth::id())
            ->where('status', ReservationStatusEnum::RESERVED)
            ->first();

        if (empty($moneyReservation))
            return [];

        return [
            'name' => 'Money',
            'type' => PrizeTypeEnum::MONEY,
            'id' => $moneyReservation->id,
            'amount' => $moneyReservation->amount
        ];
    }

    /**
     * @return array
     */
    public function getPrize(): array
    {
        // todo wrap up in a transaction

        $money = Money::first();

        if ($money->current_amount <= 0)
            return [];

        try {
            $randomAmount = random_int($money->min_amount, $money->max_amount);
        } catch (\Exception $ex) {
            return [];
        }

        if ($randomAmount > $money->current_amount)
            $randomAmount = $money->current_amount;

        $money->current_amount -= $randomAmount;
        $money->save();

        $moneyReservation = MoneyReservation::create([
            'user_id' => Auth::id(),
            'amount' => $randomAmount
        ]);

        return [
            'name' => 'Money',
            'type' => PrizeTypeEnum::MONEY,
            'id' => $moneyReservation->id,
            'amount' => $randomAmount
        ];
    }

    public function receive(): void
    {
        MoneyReservation::where('id', request('id'))
            ->update(['status' => ReservationStatusEnum::SENDING]);
    }

    public function refuse(): void
    {
        $moneyReservation = MoneyReservation::find(request('id'));
        $moneyReservation->status = ReservationStatusEnum::CANCELED;
        $moneyReservation->save();

        Money::where('id', 1)
        ->increment('current_amount', $moneyReservation->amount);
    }

    public function convert(): void
    {
        $moneyReservation = MoneyReservation::find(request('id'));
        $moneyReservation->status = ReservationStatusEnum::CONVERTED;
        $moneyReservation->save();

        $money = Money::first();

        $user = User::find(Auth::id());
        $user->bonus_balance += $this->getBonusAmount((float) $money->converting_ratio, (float) $moneyReservation->amount);
        $user->save();

        Auth::setUser($user);
    }

    /**
     * @param float $convertingRatio
     * @param float $amount
     * @return float
     */
    public function getBonusAmount(float $convertingRatio, float $amount): float
    {
        if ($amount <= 0 || $convertingRatio <= 0)
            return 0;

        return round($amount * $convertingRatio, 2);
    }
}
