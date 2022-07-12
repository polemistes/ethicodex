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
            $table->renameColumn('dealer_id', 'purchase_party_id');
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
            $table->renameColumn('purchase_party_id', 'dealer_id');
        });
    }
};
