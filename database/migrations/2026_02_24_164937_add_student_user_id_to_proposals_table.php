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
        $table->foreignId('student_user_id')
            ->nullable()
            ->after('id')
            ->constrained('users')
            ->nullOnDelete();
    });
}
public function down(): void
{
    Schema::table('proposals', function (Blueprint $table) {
        $table->dropConstrainedForeignId('student_user_id');
    });
}
};
