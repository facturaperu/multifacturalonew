<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index() {
        $files = Storage::allFiles('db-backup');
        return view('system.backups.index')->with('files', $files);
    }

    public function download($name) {
        return Storage::download('db-backup/'.$name);
    }
}
