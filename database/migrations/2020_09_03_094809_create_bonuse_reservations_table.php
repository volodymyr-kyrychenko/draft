<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonuseReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonuse_reservations', function (Blueprint $table) {
            $table->id();
			$table->bigInteger('user_id');
			$table->smallInteger('status')->default(0)
				->comment('0 - reserved, 2 - canceled, 3 - approved');
            $table->unsignedDecimal('amount', 8, 2);
            $table->timestamps();
			$table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bonuse_reservations');
    }
}
