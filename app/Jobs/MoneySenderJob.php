<?php

declare(strict_types = 1);

namespace App\Jobs;

use App\Http\Enums\ReservationStatusEnum;
use App\MoneyReservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class MoneySenderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $id;
    private $url = 'https://postman-echo.com/post';

    /**
     * MoneySenderJob constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function handle(): void
    {
        $moneyReservation = MoneyReservation::where('id', $this->id)
            ->where('status', ReservationStatusEnum::SENDING)
            ->first();

        if (empty($moneyReservation))
            return;

        $response = Http::withHeaders(['content-type' => 'application/json'])
            ->post($this->url, [
                'email' => 'email',
                'amount' => $moneyReservation->amount
        ])->throw();

        $responseContent = $response->json()['data'];

        if ($responseContent['email'] == 'email' && $responseContent['amount'] == $moneyReservation->amount) {
            $moneyReservation->status = ReservationStatusEnum::SENT;
            $moneyReservation->save();
        }
    }
}
