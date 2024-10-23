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
        Schema::create('compliance_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('compliance_id')->nullable(); // Nullable for new compliance
            $table->unsignedBigInteger('user_id');
            $table->string('action'); // 'add', 'edit', 'delete'
            $table->text('changes'); // Store compliance changes as JSON
            $table->boolean('approved')->default(false); // Approved status
            $table->timestamps();

            // Foreign key relationships
            $table->foreign('compliance_id')->references('id')->on('compliances')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compliance_request');
    }
};
