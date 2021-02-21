<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeletesOnIncentive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::beginTransaction();
        try {
            Schema::table('incentives', function (Blueprint $table) {
                $table->softDeletes();
            });
    
            Schema::table('incentive_details', function (Blueprint $table) {
                $table->softDeletes();
            });

            Schema::table('incentive_staff', function (Blueprint $table) {
                $table->softDeletes();
            });

            Schema::table('incentive_staff_details', function (Blueprint $table) {
                $table->softDeletes();
            });
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::beginTransaction();
        try {
            Schema::table('incentives', function (Blueprint $table) {
                $table->softDeletes();
            });
    
            Schema::table('incentive_details', function (Blueprint $table) {
                $table->softDeletes();
            });

            Schema::table('incentive_staff', function (Blueprint $table) {
                $table->softDeletes();
            });

            Schema::table('incentive_staff_details', function (Blueprint $table) {
                $table->softDeletes();
            });
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }
    }
}
