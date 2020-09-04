<?php

namespace App\Http\Controllers;

use App\Http\Helpers\PrizeHelper;

class HomeController extends Controller
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $prize = $this->prizeHelper->checkPrize();

        if (empty($prize))
            return view('home');

        return view('prizing', $prize);
    }
}
