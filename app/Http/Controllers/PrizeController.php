<?php

namespace App\Http\Controllers;

use App\Http\Helpers\PrizeHelper;
use Illuminate\Http\Request;

class PrizeController extends Controller
{
    private $prizeHelper;

    /**
     * HomeController constructor.
     * @param PrizeHelper $prizeHelper
     */
    public function __construct(PrizeHelper $prizeHelper)
    {
        $this->middleware('auth');
        $this->prizeHelper = $prizeHelper;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $prize = $this->prizeHelper->checkPrize();

        if (empty($prize))
            $prize = $this->prizeHelper->getPrize();

        return view('prizing', $prize);
    }

    public function receive()
    {
        $this->prizeHelper->receive();

        return view('home');
    }

    public function refuse()
    {
        $this->prizeHelper->refuse();

        return view('home');
    }
}
