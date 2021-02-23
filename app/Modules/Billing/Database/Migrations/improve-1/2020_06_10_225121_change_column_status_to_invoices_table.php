<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnStatusToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('0', '1', '2', '3') DEFAULT '0' NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('0', '1', '2') DEFAULT '0' NULL");
    }
}
