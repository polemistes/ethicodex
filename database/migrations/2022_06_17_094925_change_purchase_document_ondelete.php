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
        Schema::table('document_purchase', function (Blueprint $table) {
            $table->dropForeign('document_purchase_purchase_id_foreign');
            $table->dropForeign('document_purchase_document_id_foreign');
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
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
            $table->dropForeign('document_purchase_purchase_id_foreign');
            $table->dropForeign('document_purchase_document_id_foreign');
            $table->foreign('purchase_id')->references('id')->on('purchases');
            $table->foreign('document_id')->references('id')->on('documents');
        });
    }
};
