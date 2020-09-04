<?php

namespace App\Http\Controllers;

use App\Http\Helpers\PrizeHelper;
use App\Http\Validators\PrizeValidator;

class PrizeController extends Controller
{
    private $prizeHelper;
    private $prizeValidator;

    /**
     * PrizeController constructor.
     * @param PrizeHelper $prizeHelper
     * @param PrizeValidator $prizeValidator
     */
    public function __construct(PrizeHelper $prizeHelper, PrizeValidator $prizeValidator)
    {
        $this->middleware('auth');
        $this->prizeHelper = $prizeHelper;
        $this->prizeValidator = $prizeValidator;
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
        if ($this->prizeValidator->prizeRequestValidator()->fails())
            // todo write an error to the log
            return view('home');

        $this->prizeHelper->receive();

        return view('home');
    }

    public function refuse()
    {
        if ($this->prizeValidator->prizeRequestValidator()->fails())
            // todo write an error to the log
            return view('home');

        $this->prizeHelper->refuse();

        return view('home');
    }

    public function convert()
    {
        if ($this->prizeValidator->prizeRequestValidator()->fails())
            // todo write an error to the log
            return view('home');

        $this->prizeHelper->convert();

        return view('home');
    }
}
