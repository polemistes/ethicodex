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
        Schema::table('document_script', function (Blueprint $table) {
            $table->foreignId('document_id')->change();
            $table->foreignId('script_id')->change();  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_script', function (Blueprint $table) {
            $table->foreignId('document_id')->constrained()->change();
            $table->foreignId('script_id')->constrained()->change();
        });
    }
};
