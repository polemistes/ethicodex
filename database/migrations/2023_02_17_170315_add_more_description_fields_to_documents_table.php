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
            $table->text('punctuation_description')->nullable()->after('diacritic_description');
            $table->text('critical_symbols_description')->nullable()->after('diacritic_description');
            $table->text('pagination_description')->nullable()->after('diacritic_description');
            $table->text('quire_signatures_description')->nullable()->after('diacritic_description');
            $table->text('inks_description')->nullable()->after('diacritic_description');
            $table->text('cover_description')->nullable()->after('diacritic_description');
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
            $table->dropColumn('cover_description');
            $table->dropColumn('inks_description');
            $table->dropColumn('quire_signatures_description');
            $table->dropColumn('pagination_description');
            $table->dropColumn('critical_symbols_description');
            $table->dropColumn('punctuation_description');
        });
    }
};
