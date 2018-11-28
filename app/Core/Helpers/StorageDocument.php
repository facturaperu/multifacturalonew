<?php

namespace App\Core\Helpers;

use Illuminate\Support\Facades\Storage;

trait StorageDocument
{
    public function uploadStorage($folder, $file_content, $filename, $extension = 'xml')
    {
        Storage::disk('tenant')->put($folder.DIRECTORY_SEPARATOR.$filename.'.'.$extension, $file_content);
    }

    public function downloadStorage($folder, $filename, $extension = 'xml')
    {
        return Storage::disk('tenant')->download($folder.DIRECTORY_SEPARATOR.$filename.'.'.$extension);
    }

    public function getStorage($folder, $filename, $extension = 'xml')
    {
        return Storage::disk('tenant')->get($folder.DIRECTORY_SEPARATOR.$filename.'.'.$extension);
    }
}