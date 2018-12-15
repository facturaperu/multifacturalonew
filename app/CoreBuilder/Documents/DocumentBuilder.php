<?php

namespace App\CoreBuilder\Documents;

use App\Core\Helpers\NumberHelper;
use App\CoreBuilder\Interfaces\DocumentInterface;
use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use Illuminate\Support\Str;

class DocumentBuilder implements DocumentInterface
{
    protected $document;

    public function saveDocument($data)
    {
        $data['number'] = $this->setNumber($data);
        $data['filename'] = $this->setFilename($data);
        $data['external_id'] = Str::uuid();
        $data['legends'] = $this->addLegends($data);
        $this->document = Document::create($data);
        $this->saveItems($data);
    }

    public function addLegends($data)
    {
        $legends = key_exists('legends', $data)?$data['legends']:[];
        $legends[] = [
            'code' => 1000,
            'value' => '1111'//NumberHelper::convertToLetter($data['total'])
        ];

        return $legends;
    }

    public function saveItems($data)
    {
        foreach ($data['items'] as $row) {
            $this->document->details()->create($row);
        }
    }

    public function setNumber($data)
    {
        $number = $data['number'];
        $series = $data['series'];
        $document_type_id = $data['document_type_id'];
        $soap_type_id = $data['soap_type_id'];
        if ($data['number'] === '#') {
            $document = Document::select('number')
                                    ->where('series', $series)
                                    ->where('document_type_id', $document_type_id)
                                    ->where('soap_type_id', $soap_type_id)
                                    ->orderBy('number', 'desc')
                                    ->first();
             $number = ($document)?(int)$document->number+1:1;
        }
        return $number;
    }

    public function setFilename($data)
    {
        $company = Company::first();

        return join('-', [$company->number, $data['document_type_id'], $data['series'], $data['number']]);
    }

    public function getName()
    {
        return '';
    }

    public function getCompany()
    {
        return Company::first();
    }
}