<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinanceAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance_accounts', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('account_name',100);
            $table->string('account_code',250);
            $table->smallInteger('account_category_id')->unsigned();
            $table->bigInteger('balance');
            $table->text('description')->nullable(true);
            $table->timestamps();

            $table->foreign('account_category_id')
            ->references('id')->on('finance_account_categories')
            ->onUpdate('cascade')
            ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('finance_account');
    }
}
