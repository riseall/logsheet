<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLabelsToMachinesTable extends Migration
{
    public function up()
    {
        Schema::table('machines', function (Blueprint $table) {
            $table->string('type')->nullable()->after('name');
            $table->string('asset_number')->nullable()->after('type');
            $table->string('room')->nullable()->after('asset_number');
        });
    }

    public function down()
    {
        Schema::table('machines', function (Blueprint $table) {
            $table->dropColumn(['type', 'asset_number', 'room']);
        });
    }
}
