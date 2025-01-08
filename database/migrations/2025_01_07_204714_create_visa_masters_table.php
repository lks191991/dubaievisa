<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisaMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('visa_masters', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('image')->nullable();
        $table->string('visa_type');
        $table->boolean('insurance_mandate')->default(false);
        $table->text('insurance_information')->nullable();
        $table->string('stay_validity');
        $table->string('visa_validity');
        $table->decimal('adult_fees', 10, 2);
        $table->decimal('child_fees', 10, 2);
        $table->decimal('express_charges', 10, 2)->nullable();
        $table->decimal('super_express_charges', 10, 2)->nullable();
        $table->string('normal_processing_timeline');
        $table->string('express_processing_timeline')->nullable();
        $table->string('super_express_processing_timeline')->nullable();
        $table->text('description')->nullable();
        $table->json('documents_checklist')->nullable();
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
        Schema::dropIfExists('visa_masters');
    }
}
