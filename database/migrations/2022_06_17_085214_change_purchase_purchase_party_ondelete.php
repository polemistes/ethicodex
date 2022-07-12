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
            $table->dropForeign('purchase_purchase_party_purchase_party_id_foreign');
            $table->dropForeign('purchase_purchase_party_purchase_id_foreign');
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
            $table->foreign('purchase_party_id')->references('id')->on('purchase_parties')->onDelete('cascade');
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
            $table->dropForeign('purchase_purchase_party_purchase_party_id_foreign');
            $table->dropForeign('purchase_purchase_party_purchase_id_foreign');
            $table->foreign('purchase_id')->references('id')->on('purchases');
            $table->foreign('purchase_party_id')->references('id')->on('purchase_parties');
        });
    }
};
