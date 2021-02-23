<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeletesOnHumanResource extends Migration
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
            Schema::table('departments', function (Blueprint $table) {
                $table->softDeletes();
            });
    
            Schema::table('staff_designations', function (Blueprint $table) {
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
            Schema::table('departments', function (Blueprint $table) {
                $table->dropColumn('deleted_at');
            });

            Schema::table('staff_designations', function (Blueprint $table) {
                $table->dropColumn('deleted_at');
            });
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }
    }
}
