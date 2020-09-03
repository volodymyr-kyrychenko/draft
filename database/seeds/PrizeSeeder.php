<?php

use Illuminate\Database\Seeder;
use App\Prize, App\Money, App\Bonuse;

class PrizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Prize::create([
			'name' => 'pencil red',
		]);

		Prize::create([
			'name' => 'pencil green',
		]);

		Prize::create([
			'name' => 'pencil yellow',
		]);

		Prize::create([
			'name' => 'pen red',
		]);

		Prize::create([
			'name' => 'pen green',
		]);

		Prize::create([
			'name' => 'pen yellow',
		]);

		Prize::create([
			'name' => 'ruler',
		]);

		Prize::create([
			'name' => 'notebook',
		]);

		Prize::create([
			'name' => 'book',
		]);

		Prize::create([
			'name' => 'phone',
		]);

		Money::create([
			'current_amount' => 150.00,
			'converting_ratio' => 2.13,
			'min_amount' => 2,
			'max_amount' => 21
		]);

		Bonuse::create([
			'min_amount' => 4,
			'max_amount' => 31
		]);
    }
}
