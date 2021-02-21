<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->tinyInteger('is_min_stock')->nullable()->after('quantity');
            $table->integer('min_stock')->nullable()->before('is_min_stock');
            $table->integer('tax_id')->nullable()->after('is_min_stock');
            $table->bigInteger('purchase_price')->nullable()->after('tax_id');
            $table->integer('percentage_price_sales')->nullable();
            $table->mediumInteger('point')->default(0);
            $table->smallInteger('min_qty')->nullable();
            $table->bigInteger('purchase_price_avg')->nullable()->default(0)->after('percentage_price_sales');
            $table->tinyInteger('sales_type')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_min_stock');
            $table->dropColumn('min_stock');
            $table->dropColumn('tax_id');
            $table->dropColumn('purchase_price');
            $table->dropColumn('percentage_price_sales');
            $table->dropColumn('point');
            $table->dropColumn('min_qty');
            $table->dropColumn('purchase_price_avg');
            $table->dropColumn('sales_type');
        });
    }
}
