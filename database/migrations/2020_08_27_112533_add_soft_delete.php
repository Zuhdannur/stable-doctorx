<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDelete extends Migration
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
            Schema::table('roles', function (Blueprint $table) {
                $table->softDeletes();
            });
    
            Schema::table('abilities', function (Blueprint $table) {
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
            Schema::table('roles', function (Blueprint $table) {
                $table->dropColumn('deleted_at');
            });

            Schema::table('abilities', function (Blueprint $table) {
                $table->dropColumn('deleted_at');
            });
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }
    }
}
