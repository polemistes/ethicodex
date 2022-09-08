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
        Schema::table('purchase_purchase_party', function (Blueprint $table) {
            $table->string('party_role')->nullable()->after('purchase_id'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_purchase_party', function (Blueprint $table) {
            $table->dropColumn('party_role');
        });
    }
};
