<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\Upload; // Import the Upload model
use League\Csv\Reader;

class ProcessUploadedFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $upload;

    public $tries = 3;

    public function __construct($filePath, Upload $upload)
    {
        $this->filePath = $filePath;
        $this->upload = $upload;
    }

    public function handle()
    {
        $fileContent = Storage::get($this->filePath);

        $cleanedContent = $this->cleanNonUtf8Characters($fileContent);

        $this->upload->update(['status' => 'completed']);
    }

    // Function to clean non-UTF-8 characters
    private function cleanNonUtf8Characters($text)
    {
        // Remove non-UTF-8 characters using regular expressions
        $cleanedText = preg_replace('/[^\x{80}-\x{F7}]/u', '', $text);

        return $cleanedText;
    }
}
