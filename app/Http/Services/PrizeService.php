<?php

declare(strict_types = 1);

namespace App\Http\Services;

use App\Http\Enums\PrizeStatusEnum;
use App\Http\Enums\PrizeTypeEnum;
use App\Http\Enums\ReservationStatusEnum;
use App\Prize;
use App\PrizeReservation;
use Illuminate\Support\Facades\Auth;

class PrizeService implements PrizeServiceInterface
{
    /**
     * @return array
     */
    public function checkPrize(): array
    {
        $prizeReservation = PrizeReservation::leftJoin('prizes as p', 'p.id', '=', 'prize_reservations.prize_id')
            ->select('prize_reservations.id', 'p.name')
            ->where('prize_reservations.user_id', Auth::id())
            ->where('prize_reservations.status', ReservationStatusEnum::RESERVED)
            ->first();

        if (empty($prizeReservation))
            return [];

        return [
            'name' => $prizeReservation->name,
            'type' => PrizeTypeEnum::PRIZES,
            'id' => $prizeReservation->id,
            'amount' => 0
        ];
    }

    /**
     * @return array
     */
    public function getPrize(): array
    {
        // todo wrap up in a transaction

        $prizes = Prize::where('status', PrizeStatusEnum::ENABLED)
            ->get();

        if ($prizes->isEmpty())
            return [];

        $ids = $prizes->pluck('id');
        $randomId = $ids[array_rand($ids->toArray())];

        Prize::where('id', $randomId)
            ->update(['status' => PrizeStatusEnum::RESERVED]);

        $prizeReservation = PrizeReservation::create([
            'prize_id' => $randomId,
            'user_id' => Auth::id()
        ]);

        return [
            'name' => $prizes->where('id', $randomId)->first()->name,
            'type' => PrizeTypeEnum::PRIZES,
            'id' => $prizeReservation->id,
            'amount' => 0
        ];
    }

    public function receive(): void
    {
        $prizeReservation = PrizeReservation::find(request('id'));
        $prizeReservation->status = ReservationStatusEnum::SENT;
        $prizeReservation->save();

        Prize::where('id', $prizeReservation->prize_id)
            ->update(['status' => PrizeStatusEnum::DISABLED]);
    }

    public function refuse(): void
    {
        $prizeReservation = PrizeReservation::find(request('id'));
        $prizeReservation->status = ReservationStatusEnum::CANCELED;
        $prizeReservation->save();

        Prize::where('id', $prizeReservation->prize_id)
            ->update(['status' => PrizeStatusEnum::ENABLED]);
    }
}
