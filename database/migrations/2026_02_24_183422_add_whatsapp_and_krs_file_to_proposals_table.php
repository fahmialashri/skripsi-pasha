<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('proposals', function (Blueprint $table) {
        $table->string('whatsapp', 30)->nullable()->after('student_id');
        $table->string('krs_file')->nullable()->after('abstract');
    });
}

public function down(): void
{
    Schema::table('proposals', function (Blueprint $table) {
        $table->dropColumn(['whatsapp', 'krs_file']);
    });
}
};
