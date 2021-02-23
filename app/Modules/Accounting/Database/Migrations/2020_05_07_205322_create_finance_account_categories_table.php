<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinanceAccountCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance_account_categories', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('category_code',250)->unique();
            $table->string('category_name',250);
            $table->smallInteger('parent_id')->nullable(false);
            $table->enum('type',[1,2,3,4,5])->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('finance_account_categories');
    }
}
