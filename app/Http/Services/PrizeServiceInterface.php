<?php

namespace App\Http\Services;

interface PrizeServiceInterface
{
    public function checkPrize();
    public function getPrize();
    public function receive();
    public function refuse();
}
