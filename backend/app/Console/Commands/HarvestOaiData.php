<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JournalSource;
use App\Models\Article;
use Illuminate\Support\Facades\Http;
use SimpleXMLElement;

class HarvestOaiData extends Command
{
    /**
     * Nama dan signature dari perintah konsol.
     * Ini yang akan kita ketik di terminal: php artisan oai:harvest
     */
    protected $signature = 'oai:harvest';

    /**
     * Deskripsi dari perintah konsol.
     */
    protected $description = 'Memanen data artikel dari semua sumber jurnal OAI-PMH';

    /**
     * Eksekusi logika perintah.
     */
    public function handle()
    {
        $this->info('Memulai proses panen menyeluruh...');

        // Ambil semua sumber jurnal yang memiliki URL OAI
        $sources = JournalSource::whereNotNull('oai_url')->where('oai_url', '!=', '')->get();

        if ($sources->isEmpty()) {
            $this->warn('Tidak ada sumber jurnal dengan URL OAI yang valid. Proses dihentikan.');
            return 1;
        }

        $this->info("Ditemukan " . $sources->count() . " sumber jurnal untuk dipanen.");

        // Loop setiap sumber jurnal
        foreach ($sources as $source) {
            $this->line("\n--- Memproses Jurnal: " . $source->journal_title . " ---");
            $this->line("Target OAI URL: " . $source->oai_url);

            $resumptionToken = null;
            $isFirstRequest = true;
            $totalNew = 0;
            $totalSkipped = 0;

            do {
                // Bangun URL secara dinamis
                $url = $isFirstRequest
                    ? $source->oai_url . '?verb=ListRecords&metadataPrefix=oai_dc'
                    : $source->oai_url . '?verb=ListRecords&resumptionToken=' . urlencode($resumptionToken);

                $this->comment("Mengambil dari: " . $url);

                // Ambil data XML menggunakan HTTP Client
                try {
                    $response = Http::timeout(60)->get($url); // Timeout 60 detik
                    if (!$response->successful()) {
                        $this->error("Gagal mengambil data XML. Status: " . $response->status());
                        break;
                    }
                    $xmlContent = $response->body();
                } catch (\Exception $e) {
                    $this->error("Error koneksi: " . $e->getMessage());
                    break;
                }

                // Parsing XML
                libxml_use_internal_errors(true);
                $xml = new SimpleXMLElement($xmlContent);
                $xml->registerXPathNamespace('oai', 'http://www.openarchives.org/OAI/2.0/');
                $xml->registerXPathNamespace('dc', 'http://purl.org/dc/elements/1.1/');

                $records = $xml->xpath('//oai:record');

                if (empty($records)) {
                    $this->warn('Tidak ada record ditemukan di halaman ini.');
                    break;
                }

                // Proses setiap record
                foreach ($records as $record) {
                    if (!isset($record->metadata)) continue;

                    $dc = $record->metadata->children('http://www.openarchives.org/OAI/2.0/oai_dc/')->dc;
                    $identifier = (string) $dc->xpath('//dc:identifier')[0] ?? null;

                    if (!$identifier) continue;

                    // Gunakan updateOrCreate untuk efisiensi.
                    // Jika unique_identifier sudah ada, data akan di-update.
                    // Jika belum ada, data baru akan dibuat.
                    Article::updateOrCreate(
                        ['unique_identifier' => $identifier], // Kondisi pencarian
                        [ // Data untuk diisi atau di-update
                            'journal_source_id' => $source->id,
                            'title'             => (string) $dc->title ?? null,
                            'description'       => (string) $dc->description ?? null,
                            'publisher'         => (string) $dc->publisher ?? null,
                            'date'              => (string) $dc->date ?? null,
                            'language'          => (string) $dc->language ?? null,
                            'creator1'          => (string) $dc->creator[0] ?? null,
                            'creator2'          => (string) $dc->creator[1] ?? null,
                            'subject1'          => (string) $dc->subject[0] ?? null,
                            'identifier1'       => (string) $dc->identifier[0] ?? null,
                            'identifier2'       => (string) $dc->identifier[1] ?? null,
                            'source1'           => (string) $dc->source[0] ?? null,
                            // Tambahkan field lain sesuai kebutuhan
                        ]
                    );
                }

                // Cari resumptionToken untuk iterasi berikutnya
                $resumptionToken = (string) $xml->ListRecords->resumptionToken;
                $isFirstRequest = false;
                sleep(1); // Beri jeda untuk tidak membebani server target

            } while (!empty($resumptionToken));

            $this->info("Proses untuk jurnal ini selesai.");
        }

        $this->info("\nSEMUA PROSES PANEN TELAH SELESAI.");
        return 0;
    }
}