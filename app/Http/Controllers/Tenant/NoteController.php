<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Document;

class NoteController extends Controller
{
    public function create($document_id)
    {
        $document = Document::find($document_id);
        // dd($document);
        return view('tenant.documents.note', compact('document'));
    }
}