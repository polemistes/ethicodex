<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ancient_provenances', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('name');
            $table->foreign('parent_id')
                  ->references('id')->on('ancient_provenances')
                  ->cascadeOnUpdate()->cascadeOnDelete();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ancient_provenances', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};
