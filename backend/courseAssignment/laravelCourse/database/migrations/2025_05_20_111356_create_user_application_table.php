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
        Schema::create('user_application', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant')->constrained('user')->onDelete('cascade');
            $table->foreignId('jobApplied')->constrained('table_jobs_uploading')->onDelete('cascade');
            $table->dateTime('appliedAt');
            $table->string('name');
            $table->string('experience');
            $table->string('contactNumber');
            $table->string('resumeLink');
            $table->text('expectations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_application');
    }
};
