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
        Schema::table('transaction_transaction_party', function (Blueprint $table) {
            $table->renameColumn('purchase_party_id', 'transaction_party_id');
            $table->renameColumn('purchase_id', 'transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_transaction_party', function (Blueprint $table) {
            $table->renameColumn('transaction_party_id', 'purchase_party_id');
            $table->renameColumn('transaction_id', 'purchase_id');
        });
    }
};
