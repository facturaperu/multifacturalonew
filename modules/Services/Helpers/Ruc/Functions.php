<?php

namespace Modules\Services\Helpers\Ruc;

class Functions
{
    public static function get_department_by_id($res_department_id)
    {
        $locations = require __DIR__.DIRECTORY_SEPARATOR.'locations.php';
        $departments = collect($locations['departments']);

        return $departments->where('id', $res_department_id)->first();
    }

    public static function get_name($res_name)
    {
        return str_replace('SOCIEDAD ANONIMA CERRADA', 'S.A.C.', $res_name);
    }

    public static function get_location_by_address($res_address)
    {
        $res_address = preg_replace("[\s+]", ' ', $res_address);
        if (trim($res_address) === '-') {
            $res_address = '';
        }

        $items = explode('-', $res_address);
        $district_description = trim($items[count($items) - 1]);
        $province_description = trim($items[count($items) - 2]);

        $location_id = self::get_location_id($district_description, $province_description);
        $department = self::get_department_by_id($location_id[0]);

        $res_address = trim(str_replace('- '.$district_description, '', $res_address));
        $res_address = trim(str_replace('- '.$province_description, '', $res_address));
        $res_address = trim(str_replace($department['description'], '', $res_address));

        return [
            'address' => $res_address,
            'department' => $department['description'],
            'province' => $province_description,
            'district' => $district_description,
            'location_id' => self::get_location_id($district_description, $province_description)
        ];
    }

    private static function get_location_id($res_district, $res_province)
    {
        $locations = require __DIR__.DIRECTORY_SEPARATOR.'locations.php';
        $districts = collect($locations['districts']);
        $districts_array = $districts->where('description', $res_district);

        if(count($districts_array) === 1) {
            $district = $districts_array->first();
        } else {
            $provinces = collect($locations['provinces']);
            $provinces_array = $provinces->where('description', $res_province);
            $province = $provinces_array->first();

            $district_index = collect($districts_array)->search(function ($item) use($province) {
                return substr($item['id'], 0, 4) === $province['id'];
            });

            $district = $districts[$district_index];
        }
        return [
            substr($district['id'], 0, 2),
            substr($district['id'], 0, 4),
            $district['id']
        ];
    }
}