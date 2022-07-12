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
        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('storage_condition_id')->nullable()->after('binding_description');
            $table->text('conservation_history')->nullable()->after('storage_condition_id');
            $table->text('analyses_comment')->nullable()->after('conservation_history');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('storage_condition_id');
            $table->dropColumn('conservation_history');
            $table->dropColumn('analyses_comment');
        });
    }
};
