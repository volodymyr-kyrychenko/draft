<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrizeReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prize_reservations', function (Blueprint $table) {
            $table->id();
			$table->bigInteger('prize_id');
			$table->bigInteger('user_id');
			$table->smallInteger('status')->default(0)
                ->comment('0 - reserved, 1 - canceled, 2 - sending, 3 - sent, 4 - converted');
            $table->timestamps();
			$table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prize_reservations');
    }
}
