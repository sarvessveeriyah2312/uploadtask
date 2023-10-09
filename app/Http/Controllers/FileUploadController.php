<?php

namespace App\Http\Controllers;


use App\Models\Upload;
use Illuminate\Http\Request;
use App\Jobs\ProcessUploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class FileUploadController extends Controller
{
    public function showUploadForm()
{
    $uploadHistory = Upload::orderBy('created_at', 'desc')->get();

    return view('welcome', ['uploadHistory' => $uploadHistory]);
}
public function upload(Request $request)
{
    $file = $request->file('upload_file');
    
    // Calculate the hash of the file's content
    $fileHash = md5_file($file->getRealPath());

    // Use a database transaction to handle race conditions
    return DB::transaction(function () use ($file, $fileHash) {
        // Check if a record with the same hash exists
        $existingUpload = Upload::where('file_hash', $fileHash)->first();

        if (!$existingUpload) {
            // Insert a new record into the uploads table
            $upload = Upload::create([
                'name' => $file->getClientOriginalName(),
                'status' => 'processing',
                'file_hash' => $fileHash,
            ]);

            // Queue a background job for processing
            ProcessUploadedFile::dispatch($file->getRealPath(), $upload);
        }

        // Return a response or redirect
        return redirect()->back()->with('success', 'File uploaded successfully.');
    });
}
}
