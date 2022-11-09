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
            $table->float('full_page_width')->nullable()->change();
            $table->float('full_page_height')->nullable()->change();
            $table->float('upper_margin')->nullable()->change();
            $table->float('lower_margin')->nullable()->change();
            $table->float('inner_margin')->nullable()->change();
            $table->float('outer_margin')->nullable()->change();
            $table->float('full_text_block_width')->nullable()->change();
            $table->float('full_text_block_height')->nullable()->change();
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
            //
        });
    }
};
