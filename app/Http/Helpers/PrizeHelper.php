<?php

declare(strict_types = 1);

namespace App\Http\Helpers;

use App\Http\Enums\PrizeTypeEnum;
use App\Http\Services\BonusService;
use App\Http\Services\MoneyService;
use App\Http\Services\PrizeService;

class PrizeHelper
{
	protected $services;

    /**
     * PrizeHelper constructor.
     * @param MoneyService $moneyService
     * @param BonusService $bonusService
     * @param PrizeService $prizeService
     */
    public function __construct(MoneyService $moneyService, BonusService $bonusService, PrizeService $prizeService)
    {
		$this->services[PrizeTypeEnum::MONEY] = $moneyService;
        $this->services[PrizeTypeEnum::BONUSES] = $bonusService;
        $this->services[PrizeTypeEnum::PRIZES] = $prizeService;
    }

    /**
     * @return array
     */
    public function checkPrize(): array
	{
        foreach ($this->services as $service){
            $prize = $service->checkPrize();

            if (!empty($prize))
                return $prize;
        }

        return [];
	}

    /**
     * @return array
     */
    public function getPrize(): array
	{
        while (true) {
            if (empty($this->services)) {
                // todo write an error to the log

                return [
                    'name' => 'no win',
                    'type' => PrizeTypeEnum::NONE,
                    'id' => 0,
                    'amount' => 0
                ];
            }

            $randomServiseKey = array_rand($this->services);
            $prize = $this->services[$randomServiseKey]->getPrize();

            if (!empty($prize))
                return $prize;

            unset($this->services[$randomServiseKey]);
        }
	}

	public function receive(): void
    {
        $this->services[request('type')]->receive();
    }

    public function refuse(): void
    {
        $this->services[request('type')]->refuse();
    }

    public function convert(): void
    {
        $this->services[request('type')]->convert();
    }
}
