<?php

namespace App\Core\Builder\Documents;

use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use App\Models\Tenant\Summary;
use Carbon\Carbon;

class SummaryBuilder
{
    protected $summary;
    protected $date_of_issue = null;

    public function register($data)
    {
        $this->date_of_issue = Carbon::parse($data['date_of_issue']);
        $documents = $data['documents'];
        $this->save((object)$documents[0]);
        foreach ($documents as $document)
        {
            $this->saveDocument((object)$document);
        }
    }

    public function voided($document)
    {
        $this->save($document, 3);
        $this->saveDocument($document);
    }

    public function saveDocument($document)
    {
        $this->summary->documents()->create([
            'document_id' => $document->id
        ]);
    }

    public function save($document, $process_type_id = 1)
    {
        $company = Company::first();
        $date_of_issue = ($this->date_of_issue)?$this->date_of_issue:Carbon::now();
        $identifier = $this->getIdentifier($date_of_issue);

        $this->summary = Summary::create([
            'process_type_id' => $process_type_id,
            'state_type_id' => '01',
            'soap_type_id' => $document->soap_type_id,
            'ubl_version' => 'v20',
            'date_of_issue' => $date_of_issue->format('Y-m-d'),
            'date_of_reference' => $document->date_of_issue,
            'identifier' => $identifier,
            'filename' => $company->number.'-'.$identifier,
        ]);
    }

    private function getIdentifier(Carbon $date_of_issue)
    {
        $records = Summary::select('id')
                            ->where('date_of_issue', $date_of_issue->format('Y-m-d'))
                            ->get();
        $numeration = count($records) + 1;

        return join('-', ['RC', $date_of_issue->format('Ymd'), $numeration]);
    }

    public function getDocument()
    {
        return Summary::with(['documents'])->find($this->summary->id);
    }

    public function getFilename()
    {
        return $this->summary->filename;
    }
}