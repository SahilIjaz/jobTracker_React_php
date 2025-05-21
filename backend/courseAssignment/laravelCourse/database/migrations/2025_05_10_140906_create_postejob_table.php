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
        Schema::create('postejob', function (Blueprint $table) {
            $table->id();
            $table->string('jobTitle');
            $table->string('employmentType');
            $table->string('location');
            $table->string('salary');
            $table->text('description');

            $table->foreignId('creator')->constrained('user')->onDelete('cascade');

            $table->string('status')->default('open');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('postejob');
    }
};

?>