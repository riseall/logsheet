<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsheetHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logsheet_headers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id')->constrained('machines')->cascadeOnDelete();
            $table->foreignId('form_template_id')->constrained('form_templates')->cascadeOnDelete();
            $table->foreignId('teknisi_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->string('shift', 20)->default('1');
            $table->enum('status', ['draft', 'menunggu_spv', 'menunggu_manager', 'selesai', 'perlu_revisi'])->default('draft');
            
            // Supervisor approval info
            $table->foreignId('spv_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('spv_approved_at')->nullable();
            $table->text('spv_note')->nullable();

            // Manager approval info
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('manager_approved_at')->nullable();
            $table->text('manager_note')->nullable();

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
        Schema::dropIfExists('logsheet_headers');
    }
}
