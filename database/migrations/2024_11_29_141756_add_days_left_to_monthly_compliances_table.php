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
        Schema::table('monthly_compliances', function (Blueprint $table) {
            $table->integer('days_left')->nullable()->comment('Days left until the deadline, can be negative if overdue');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monthly_compliances', function (Blueprint $table) {
            // Dropping the days_left column if the migration is rolled back
            $table->dropColumn('days_left');
        });
    }
};
