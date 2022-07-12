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
            $table->string('title')->nullable()->after('current_shelfmarks');
            $table->foreignId('genre_id')->nullable()->after('title');
            $table->text('measurement_comment')->nullable()->after('full_text_block_height');
            $table->smallInteger('hand_number')->nullable()->after('quire_signature_id');
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
            $table->dropColumn('title');
            $table->dropColumn('genre_id');
            $table->dropColumn('measurement_comment');
            $table->dropColumn('hand_number');
        });
    }
};
