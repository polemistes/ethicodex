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
            $table->dropForeign(['purchase_id']);
            $table->dropColumn('purchase_id');
            $table->foreignId('gregorys_rule_id')->nullable()->after('quire_comment');
            $table->text('gregorys_rule_comment')->nullable()->after('gregorys_rule_id');
            $table->smallInteger('columns')->nullable()->after('gregorys_rule_comment');
            $table->smallInteger('columnlines')->nullable()->after('columns');
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
            $table->dropForeign(['gregorys_rule_id']);
            $table->dropColumn('gregorys_rule_id');
            $table->dropColumn('columns');
            $table->dropColumn('columnlines');
        });
    }
};
