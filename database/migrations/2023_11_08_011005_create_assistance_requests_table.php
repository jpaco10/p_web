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
        Schema::create('assistance_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable()->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            $table->unsignedBigInteger('workshop_id')->nullable()->foreign('workshop_id')->references('id')->on('workshops')->onDelete('set null');
            $table->unsignedBigInteger('vehicle_id')->nullable()->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('set null');
            $table->unsignedBigInteger('technician_id')->nullable()->foreign('technician_id')->references('id')->on('technicians')->onDelete('set null');
            
            $table->string('problem_description')->nullable();
            $table->decimal('latitud', 12, 5)->nullable();
            $table->decimal('longitud', 12, 5)->nullable();
            $table->text('photos')->nullable();
            $table->text('voice_note')->nullable();
            $table->string('status')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assistance_requests');
    }
};
