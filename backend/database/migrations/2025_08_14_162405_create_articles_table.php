<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        // Menambahkan foreign key ke tabel journal_sources
        $table->foreignId('journal_source_id')->constrained()->onDelete('cascade');
        $table->string('unique_identifier')->unique(); // Harus unik
        $table->text('title')->nullable();
        $table->text('description')->nullable();
        $table->string('publisher')->nullable();
        $table->string('date')->nullable();
        $table->string('language')->nullable();
        $table->string('coverage')->nullable();
        $table->string('rights')->nullable();
        $table->string('creator1')->nullable();
        $table->string('creator2')->nullable();
        $table->string('creator3')->nullable();
        $table->string('subject1')->nullable();
        $table->string('subject2')->nullable();
        $table->string('subject3')->nullable();
        $table->string('contributor1')->nullable();
        $table->string('contributor2')->nullable();
        $table->string('type1')->nullable();
        $table->string('type2')->nullable();
        $table->string('format1')->nullable();
        $table->string('format2')->nullable();
        $table->string('identifier1')->nullable(); // URL ke artikel asli
        $table->string('identifier2')->nullable();
        $table->string('identifier3')->nullable();
        $table->string('source1')->nullable(); // Nama jurnal sumber
        $table->string('source2')->nullable();
        $table->string('relation1')->nullable();
        $table->string('relation2')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
