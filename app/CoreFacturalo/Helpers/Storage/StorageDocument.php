<?php

namespace App\CoreFacturalo\Helpers\Storage;

use Illuminate\Support\Facades\Storage;

class StorageDocument
{
    public static function upload($filename, $file_type, $file_content, $root = null)
    {
        self::setData($filename, $file_type, $file_content, 'upload', $root);
    }

    public static function download($filename, $file_type, $root = null)
    {
        return self::setData($filename, $file_type, null, 'download', $root);
    }

    public static function get($filename, $file_type, $root = null)
    {
        return self::setData($filename, $file_type, null, 'get', $root);
    }

    private static function setData($filename, $file_type, $file_content, $action, $root)
    {
        $extension = 'xml';
        switch ($file_type) {
            case 'unsigned':
                break;
            case 'signed':
                break;
            case 'pdf':
                $extension = 'pdf';
                break;
            case 'cdr':
                $filename = 'R-'.$filename;
                $extension = 'zip';
                break;
        }

        $filename = $filename.'.'.$extension;
        $folder = ($root)?$root.DIRECTORY_SEPARATOR.$file_type:$file_type;

        switch ($action) {
            case 'get':
                Storage::disk('tenant')->get($folder . DIRECTORY_SEPARATOR . $filename);
                break;
            case 'upload':
                Storage::disk('tenant')->put($folder.DIRECTORY_SEPARATOR.$filename, $file_content);
                break;
            case 'download':
                return Storage::disk('tenant')->download($folder.DIRECTORY_SEPARATOR.$filename);
                break;
        }
    }
}