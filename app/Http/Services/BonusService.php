<?php

declare(strict_types = 1);

namespace App\Http\Services;

use App\Bonus;
use App\BonuseReservation;
use App\Http\Enums\PrizeTypeEnum;
use App\Http\Enums\ReservationStatusEnum;
use App\User;
use Illuminate\Support\Facades\Auth;


class BonusService implements PrizeServiceInterface
{
    /**
     * @return array
     */
    public function checkPrize(): array
    {
        $bonuseReservation = BonuseReservation::where('user_id', Auth::id())
            ->where('status', ReservationStatusEnum::RESERVED)
            ->first();

        if (empty($bonuseReservation))
            return [];

        return [
            'name' => 'Bonus',
            'type' => PrizeTypeEnum::BONUSES,
            'id' => $bonuseReservation->id,
            'amount' => $bonuseReservation->amount
        ];
    }

    /**
     * @return array
     */
    public function getPrize(): array
    {
        $bonus = Bonus::first();

        try {
            $randomAmount = random_int($bonus->min_amount, $bonus->max_amount);
        } catch (\Exception $ex) {
            return [];
        }

        $bonuseReservation = BonuseReservation::create([
            'user_id' => Auth::id(),
            'amount' => $randomAmount
        ]);

        return [
            'name' => 'Bonus',
            'type' => PrizeTypeEnum::BONUSES,
            'id' => $bonuseReservation->id,
            'amount' => $bonuseReservation->amount
        ];
    }

    public function receive(): void
    {
        $bonuseReservation = BonuseReservation::find(request('id'));
        $bonuseReservation->status = ReservationStatusEnum::SENT;
        $bonuseReservation->save();

        $user = User::find(Auth::id());
        $user->bonus_balance += $bonuseReservation->amount;
        $user->save();

        Auth::setUser($user);
    }

    public function refuse(): void
    {
        BonuseReservation::where('id', request('id'))
            ->update(['status' => ReservationStatusEnum::CANCELED]);
    }
}
