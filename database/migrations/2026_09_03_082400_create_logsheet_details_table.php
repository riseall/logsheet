<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsheetDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logsheet_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logsheet_header_id')->constrained('logsheet_headers')->cascadeOnDelete();
            $table->foreignId('form_parameter_id')->constrained('form_parameters')->cascadeOnDelete();
            $table->string('value')->nullable();
            $table->enum('condition_status', ['baik', 'perlu_perhatian'])->default('baik');
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
        Schema::dropIfExists('logsheet_details');
    }
}
