<?php

namespace App\Core\Builder\Documents;

use App\Models\Tenant\Company;
use App\Models\Tenant\Voided;
use Carbon\Carbon;

class VoidedBuilder
{
    protected $voided;

    public function save($document)
    {
        $company = Company::first();
        $date_of_issue = $document->date_of_issue; //Carbon::now()->addDay(-1);
        $identifier = $this->getIdentifier($date_of_issue);

        $this->voided = Voided::create([
            'state_type_id' => '01',
            'soap_type_id' => $document->soap_type_id,
            'ubl_version' => 'v20',
            'date_of_issue' => $date_of_issue,
            'date_of_reference' => $document->date_of_issue,
            'identifier' => $identifier,
            'filename' => $company->number.'-'.$identifier,
        ]);

        $this->voided->documents()->create([
            'document_id' => $document->id
        ]);
    }

    private function getIdentifier(Carbon $date_of_issue)
    {
        $records = Voided::select('id')
            ->where('date_of_issue', $date_of_issue->format('Y-m-d'))
            ->get();
        $numeration = count($records) + 1;

        return join('-', ['RA', $date_of_issue->format('Ymd'), $numeration]);
    }

    public function getDocument()
    {
        return Voided::with(['documents'])->find($this->voided->id);
    }

    public function getFilename()
    {
        return $this->voided->filename;
    }
}