<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Summary;
use App\Models\Tenant\Voided;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VoidedCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
//    public function toArray($request)
//    {
//        return $this->collection->transform(function($row, $key) {
//
//            $btn_ticket = true;
//            $has_xml = true;
//            $has_pdf = true;
//            $has_cdr = false;
//
//            if($row->state_type_id === '11') {
//                $btn_ticket = false;
//                $has_cdr = true;
//            }
//
//            if($row->group_id === '01') {
//                $voided =  Voided::whereHas('details', function($query) use($row) {
//                                    $query->where('document_id', $row->id);
//                                })
//                                ->whereIn('state_type_id', ['03', '05'])
//                                ->first();
//                $voided_id = $voided->id;
//                $voided_description = $voided->details[0]->description;
//                $download_voided_xml = $voided->download_external_xml;
//                $download_voided_pdf = $voided->download_external_pdf;
//                $download_voided_cdr = $voided->download_external_cdr;
//            } else {
//
//            }
//
//            return [
//                'id' => $voided_id,
//                'group_id' => $row->group_id,
//                'soap_type_id' => $row->soap_type_id,
//                'date_of_issue' => $row->date_of_issue->format('Y-m-d'),
//                'document_type_description' => $row->document_type->description,
//                'number' => $row->number_full,
//                'voided_description' => $voided_description,
//                'ticket' => $voided->ticket,
//                'state_type_id' => $row->state_type_id,
//                'state_type_description' => $row->state_type->description,
//                'has_xml' => $has_xml,
//                'has_pdf' => $has_pdf,
//                'has_cdr' => $has_cdr,
//                'download_xml' => $download_voided_xml,
//                'download_pdf' => $download_voided_pdf,
//                'download_cdr' => $download_voided_cdr,
//                'btn_ticket' => $btn_ticket,
//                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
//                'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
//            ];
//        });
//    }

    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {

            $btn_ticket = true;
            $has_xml = true;
            $has_pdf = true;
            $has_cdr = false;

            if($row->state_type_id === '11') {
                $btn_ticket = false;
                $has_cdr = true;
            }

            return [
                'type' => $row->type,
                'id' => $row->id,
                'ticket' => $row->ticket,
                'identifier' => $row->identifier,
                'date_of_issue' => $row->date_of_issue,
                'date_of_reference' => $row->date_of_reference,
                'state_type_id' => $row->state_type_id,
                //'state_type_description' => $row->state_type->description,
                'has_xml' => $has_xml,
                'has_pdf' => $has_pdf,
                'has_cdr' => $has_cdr,
//                'download_xml' => $row->download_external_xml,
//                'download_pdf' => $row->download_external_pdf,
//                'download_cdr' => $row->download_external_cdr,
                'btn_ticket' => $btn_ticket,
//                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
//                'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
            ];

//            $voided = Voided::latest()->get()->transform(function($row) {
//                return $this->toArrayRow($row, 'voided');
//            });
//
//            $summaries = Summary::where('process_type_id', '3')->latest()->get()->transform(function($row) {
//                return $this->toArrayRow($row, 'summaries');
//            });
//
//            return collect(array_merge($voided, $summaries))->sortBy('date_of_issue');
        });
    }

    private function toArrayRow($row, $type)
    {

    }
}