<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeOrderIdInCartsTable extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->string('order_id')->change();
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->integer('order_id')->change();
        });
    }
};
