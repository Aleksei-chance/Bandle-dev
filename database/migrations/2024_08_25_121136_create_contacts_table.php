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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('block_id')->constrained('blocks');
            $table->foreignId('user_id')->constrained('users');

            $table->foreignId('contact_type_id')->constrained('contact_types');
            $table->string('value');
            $table->integer('sort')->default(0);

            $table->boolean('publish')->default(true);
            $table->boolean('hidden')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
