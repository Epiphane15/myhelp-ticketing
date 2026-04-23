<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sla_logs', function (Blueprint $table) {
            $table->string('type')->default('breach')->after('ticket_id');
        });
    }

    public function down(): void
    {
        Schema::table('sla_logs', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
