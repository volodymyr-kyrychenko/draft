<?php

namespace App\Http\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidatorType;
use Illuminate\Validation\Rule;

class PrizeValidator
{
	/**
	 * @return ValidatorType
	 */
	public function prizeRequestValidator(): ValidatorType
    {
        return Validator::make(request()->toArray(), [
			'id' => 'required|integer|digits_between:1,11|min:1',
            'type' =>  ['required', Rule::in([1,2,3])],
		]);
    }
}
