<?php

namespace App\Imports;

use App\Models\Tenant\Person;
use Exception;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomersImport implements ToCollection, WithHeadingRow
{
    use Importable;

    protected $data;

    public function collection(Collection $rows)
    {
            $total = count($rows);
            $registered = 0;

            foreach ($rows as $row)
            {
                $type = request()->input('type');
                $identity_document_type_id = $row['identity_document_type_id'];
                $number = $row['number'];
                $name = $row['name'];
                $trade_name = $row['trade_name'];
                $country_id = ($row['country_id'])?:'PE';
                $location_id = $row['location_id'];
                $department_id = null;
                $province_id = null;
                if($location_id) {
                    $department_id = substr($location_id, 0, 2);
                    $province_id = substr($location_id, 0, 4);
                }
                $address = $row['address'];
                $email = $row['email'];
                $telephone = $row['telephone'];

                $person = Person::where('type', $type)
                                ->where('identity_document_type_id', $identity_document_type_id)
                                ->where('number', $number)
                                ->first();

                if(!$person) {
                    Person::create([
                        'type' => $type,
                        'identity_document_type_id' => $identity_document_type_id,
                        'number' => $number,
                        'name' => $name,
                        'trade_name' => $trade_name,
                        'country_id' => $country_id,
                        'department_id' => $department_id,
                        'province_id' => $province_id,
                        'district_id' => $location_id,
                        'address' => $address,
                        'email' => $email,
                        'telephone' => $telephone,
                    ]);
                    $registered += 1;
                }
            }
            $this->data = compact('total', 'registered');

    }

    public function getData()
    {
        return $this->data;
    }
}
