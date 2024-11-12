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
        Schema::create('monthly_compliances', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->unsignedBigInteger('compliance_id'); // Foreign key to compliances
            $table->string('compliance_name', 255); // Cached compliance name
            $table->unsignedSmallInteger('department_id'); // Department ID for quick access
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending'); // Status of compliance
            $table->string('image', 255)->nullable(); // Image path (optional)
            $table->boolean('approve')->default(false); // Approval status
            $table->timestamp('approved_at')->nullable(); // Approval timestamp
            $table->timestamps(); // Created and updated timestamps
            $table->softDeletes(); // Soft delete timestamp
            
            // Computed dates
            $table->date('computed_start_date')->nullable(); // Computed start date
            $table->date('computed_deadline')->nullable(); // Computed deadline
            $table->date('computed_submit_date')->nullable(); // Computed submit date

            // Indexes for optimized queries
            $table->index('compliance_id');
            $table->index('department_id');
            $table->index('status');
            
            // Foreign key constraint (optional, uncomment if foreign key integrity is needed)
            // $table->foreign('compliance_id')->references('id')->on('compliances')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_compliances');
    }
};
