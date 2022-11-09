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
            $table->boolean('page_dimensions_known')->nullable()->after('material_id');
            $table->float('fragment_width')->nullable()->after('full_page_height');
            $table->float('fragment_height')->nullable()->after('fragment_width');
            $table->boolean('textbox_size_stabe')->nullable()->after('full_page_height');
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
