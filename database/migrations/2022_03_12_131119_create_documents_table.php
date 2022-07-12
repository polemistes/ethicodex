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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            $table->string('standard_name', 2048)->nullable();
            $table->text('other_names')->nullable();
            $table->integer('trismegistos_id')->nullable();
            $table->string('publication', 2048)->nullable();
            $table->string('current_shelfmarks', 4096)->nullable();
            $table->string('ancient_author', 2048)->nullable();
            /* language_m2m */
            $table->text('language_comment')->nullable();
            $table->smallInteger('start_year')->nullable();
            $table->smallInteger('end_year')->nullable();
            $table->foreignId('dating_method_id')->nullable();
            $table->foreignId('dating_certainty_id')->nullable();
            $table->text('dating_comment')->nullable();
            $table->text('internal_comment')->nullable();
            $table->text('general_comment')->nullable();
            
            $table->foreignId('material_id')->nullable();
            $table->smallInteger('full_page_width')->nullable();
            $table->smallInteger('full_page_height')->nullable();
            $table->smallInteger('upper_margin')->nullable();
            $table->smallInteger('lower_margin')->nullable();
            $table->smallInteger('inner_margin')->nullable();
            $table->smallInteger('outer_margin')->nullable();
            $table->smallInteger('full_text_block_width')->nullable();
            $table->smallInteger('full_text_block_height')->nullable();
            
            $table->foreignId('pagination_id')->nullable();
            $table->foreignId('quire_signatures_id')->nullable();
            /* script_m2m */
            $table->text('script_description')->nullable();
            /* decoration_m2m */
            $table->text('decoration_description')->nullable();
            /* paratexts_m2m */
            $table->text('paratext_description')->nullable();
            $table->foreignId('quire_structures_id')->nullable();
            $table->smallInteger('bifolia_per_quire')->nullable();
            $table->foreignId('cover_id')->nullable();
            $table->foreignId('ink_id')->nullable();
            $table->text('binding_description')->nullable();
            
            $table->foreignId('ancient_provenance_id')->nullable();
            $table->foreignId('ancient_provenance_certainty_id')->nullable();
            $table->text('ancient_provenance_comment')->nullable();
            $table->foreignId('modern_collection_id')->nullable();
            /* dealers_m2m */
            $table->foreignId('purchase_id')->nullable();
            $table->foreignId('legal_classification_id')->nullable();
            $table->text('legal_classification_explanation')->nullable();
            $table->text('bibliography')->nullable();
            $table->text('images_info')->nullable();
            /* local_images_o2m */

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
};
