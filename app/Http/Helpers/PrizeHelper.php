<?php

declare(strict_types = 1);

namespace App\Http\Helpers;

use App\Http\Enums\PrizeTypeEnum;
use App\Http\Services\MoneyService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class PrizeHelper
{
	protected $moneyService;
	protected $services;

    public function __construct(MoneyService $moneyService)
    {
		$this->moneyService = $moneyService;
		$this->services[PrizeTypeEnum::MONEY] = $moneyService;
    }

	public function checkPrize(): array
	{
        foreach ($this->services as $service){
            $prize = $service->checkPrize();

            if (!empty($prize))
                return $prize;
        }

        return [];
	}

	public function getPrize(): array
	{
        $randomServiseKey = array_rand($this->services);
        $prize = $this->services[$randomServiseKey]->getPrize();

        return $prize;
	}

	public function receive(): void
    {
        $this->services[request('type')]->receive();
    }

    public function refuse(): void
    {
        $this->services[request('type')]->refuse();
    }
}
