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
    Schema::create('journal_sources', function (Blueprint $table) {
        $table->id(); // Kolom ID otomatis
        $table->string('journal_title');
        $table->string('oai_url');
        $table->string('journal_website_url');
        $table->string('fakultas');
        $table->text('aim_and_scope')->nullable();
        $table->string('publisher_name')->nullable();
        $table->string('publisher_country')->nullable();
        $table->string('contact_name')->nullable();
        $table->string('contact_email')->nullable();
        $table->string('issn')->nullable();
        $table->string('eissn')->nullable();
        $table->string('doi_prefix')->nullable();
        $table->year('start_year')->nullable();
        $table->string('bulan')->nullable();
        $table->string('editorial_board_url')->nullable();
        $table->string('google_scholar_url')->nullable();
        $table->string('cover_url')->nullable();
        $table->timestamps(); // Kolom created_at dan updated_at otomatis
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_sources');
    }
};
