<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropForeignKeysToUsersTable extends Migration
{
    /**
     * Run the migrations.
     * // ponytail: Hapus foreign key fisik ke tabel lokal 'users' karena User kini menunjuk
     * // ke database bersama spk_phapros.mst_anggota. Kolom id tetap bertipe integer/bigint.
     */
    public function up()
    {
        Schema::table('logsheet_headers', function (Blueprint $table) {
            $table->dropForeign(['teknisi_id']);
            $table->dropForeign(['spv_id']);
            $table->dropForeign(['manager_id']);
        });

        Schema::table('form_templates', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
        });

        Schema::table('approval_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // No-op
    }
}
