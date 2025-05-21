<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('table_jobs_uploading', function (Blueprint $table) {
            $table->id();


            $table->string('jobTitle');
            $table->string('employmentType');
            $table->string('location');
            $table->string('salary');
            $table->text('description');
            $table->text('companyName');
            $table->text('requirements');
            $table->foreignId('creator')->constrained('user')->onDelete('cascade');
            $table->string('status')->default('open');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_jobs_uploading');
    }
};
