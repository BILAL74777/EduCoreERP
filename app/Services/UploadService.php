<?php

namespace App\Services;

use App\Models\Upload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadService
{
    public function store(UploadedFile $file, string $folder = 'uploads'): Upload
    {
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $file->storeAs($folder, $fileName, 'public');

        return Upload::create([
            'file_original_name' => $file->getClientOriginalName(),
            'file_name'          => $fileName,
            'file_size'          => $file->getSize(),
            'extension'          => $file->getClientOriginalExtension(),
            'mime_type'          => $file->getMimeType(),
            'disk'               => 'public',
            'folder'             => $folder,
        ]);
    }

    public function delete(?Upload $upload): void
    {
        if (!$upload) {
            return;
        }

        Storage::disk($upload->disk)->delete(
            $upload->folder . '/' . $upload->file_name
        );

        $upload->delete();
    }

    public function replace(
        ?Upload $oldUpload,
        UploadedFile $file,
        string $folder = 'uploads'
    ): Upload {
        $this->delete($oldUpload);

        return $this->store($file, $folder);
    }
}