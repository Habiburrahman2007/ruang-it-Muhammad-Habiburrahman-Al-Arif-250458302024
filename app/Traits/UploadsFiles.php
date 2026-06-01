<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait UploadsFiles
{
    /**
     * Upload a file with a unique generated name.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param string $disk
     * @return string The path to the stored file.
     */
    public function uploadFile(UploadedFile $file, string $folder, string $disk = 'public'): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = uniqid($folder . '_', true) . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        
        return $file->storeAs($folder, $filename, $disk);
    }

    /**
     * Delete a file if it exists.
     *
     * @param string|null $path
     * @param string $disk
     * @return void
     */
    public function deleteFile(?string $path, string $disk = 'public'): void
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }
}
