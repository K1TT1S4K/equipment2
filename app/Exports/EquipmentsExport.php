<?php

namespace App\Exports;

use App\Models\Equipment;
use App\Models\Equipment_unit;
use App\Models\User;
use App\Models\Location;
use App\Models\Prefix;
use App\Models\Title;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\Exportable;

class EquipmentsExport implements FromCollection, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */

    use Exportable;

    protected $title, $query;

    public function __construct(Title $title, $query = null)
    {
        $this->title = $title;
        $this->query = $query;
    }

    public function collection()
    {
        $request = request()->query();
        $search = null;
        if (!empty($request['query'])) $search = $request['query'];
        $title = $request['title_filter'];
        $count = 1;
        $unitMap = Equipment_unit::pluck('name', 'id')->toArray();
        $userPrefixMap = User::pluck('prefix_id', 'id')->toArray();
        $userFirstnameMap = User::pluck('firstname', 'id')->toArray();
        $userLastnameMap = User::pluck('lastname', 'id')->toArray();
        $prefixMap = Prefix::pluck('name', 'id');
        $locationMap = Location::pluck('name', 'id');
        $data = Equipment::where('title_id', $title)->get();

        $headerAdded = [
            'broken' => false,
            'disposal' => false,
            'not_found' => false,
        ];

        $dataWithExtra = $data->flatMap(function ($item, $key) use (&$count, $unitMap, $userPrefixMap, $userFirstnameMap, $userLastnameMap, $prefixMap, $locationMap, &$headerAdded) {
            $result = [];

            // เพิ่ม header Excel แถวแรก
            if ($key === 0) {
                $result[] = (object)[
                    'index' => 'ลำดับ',
                    'number' => 'หมายเลขครุภัณฑ์',
                    'name' => 'ชื่อครุภัณฑ์',
                    'unit_name' => 'หน่วยนับครุภัณฑ์',
                    'status' => 'จำนวนที่ดำเนินการ',
                    'price' => 'ราคาต่อหน่วย',
                    'total_price' => 'ราคารวม',
                    'description' => 'คำอธิบาย'
                ];
            }

            // 1️⃣ กลุ่ม "ตรวจพบและชำรุด/เสื่อมสภาพ"
            if ($item->status_broken >= 1 && $item->status_disposal < $item->status_broken) {
                if (!$headerAdded['broken']) {
                    $result[] = (object)[
                        'index' => '',
                        'number' => '',
                        'name' => 'รายการพัสดุที่ตรวจพบและชำรุด/เสื่อมสภาพ',
                    ];
                    $headerAdded['broken'] = true;
                }

                $firstname = ($userFirstnameMap[$item->user_id] ?? '') ? 'ผู้ดูแล: ' . ($userFirstnameMap[$item->user_id] . ' ') : 'ผู้ดูแล: สาขาเทคโนโลยีคอมพิวเตอร์ ';
                $location = ($locationMap[$item->location_id] ?? '') ? 'ที่อยู่: ' . $locationMap[$item->location_id] : 'ที่อยู่: - ';
                $description = ($item->description ?? '') ? 'คำอธิบาย: ' . $item->description : 'คำอธิบาย: -';

                $result[] = (object)[
                    'index' => $count,
                    'number' => $item->number,
                    'name' => $item->name,
                    'unit_name' => $unitMap[$item->equipment_unit_id] ?? null,
                    'status' => $item->status_broken - $item->status_disposal,
                    'price' => $item->price,
                    'total_price' => $item->total_price,
                    'description' => ($prefixMap[$userPrefixMap[$item->user_id] ?? null] ?? '')
                        . $firstname
                        . ($userLastnameMap[$item->user_id] ?? '')
                        . $location
                        . $description,
                ];
                $count++;
            }

            // 2️⃣ กลุ่ม "ตรวจพบและชำรุด/เสื่อมสภาพ ต้องการจำหน่าย"
            if ($item->status_disposal >= 1) {
                if (!$headerAdded['disposal']) {
                    $result[] = (object)[
                        'index' => '',
                        'number' => '',
                        'name' => 'รายการพัสดุที่ตรวจพบและชำรุด/เสื่อมสภาพ ต้องการจำหน่าย',
                    ];
                    $headerAdded['disposal'] = true;
                }

                $firstname = ($userFirstnameMap[$item->user_id] ?? '') ? 'ผู้ดูแล: ' . ($userFirstnameMap[$item->user_id] . ' ') : 'ผู้ดูแล: สาขาเทคโนโลยีคอมพิวเตอร์ ';
                $location = ($locationMap[$item->location_id] ?? '') ? 'ที่อยู่: ' . $locationMap[$item->location_id] : 'ที่อยู่: - ';
                $description = ($item->description ?? '') ? 'คำอธิบาย: ' . $item->description : 'คำอธิบาย: -';

                $result[] = (object)[
                    'index' => $count,
                    'number' => $item->number,
                    'name' => $item->name,
                    'unit_name' => $unitMap[$item->equipment_unit_id] ?? null,
                    'status' => $item->status_disposal,
                    'price' => $item->price,
                    'total_price' => $item->total_price,
                    'description' => ($prefixMap[$userPrefixMap[$item->user_id] ?? null] ?? '')
                        . $firstname
                        . ($userLastnameMap[$item->user_id] ?? '')
                        . $location
                        . $description,
                ];
                $count++;
            }

            // 3️⃣ กลุ่ม "ขึ้นทะเบียนแล้วตรวจไม่พบ"
            if ($item->status_not_found >= 1) {
                if (!$headerAdded['not_found']) {
                    $result[] = (object)[
                        'index' => '',
                        'number' => '',
                        'name' => 'รายการพัสดุที่ขึ้นทะเบียนแล้วตรวจไม่พบ',
                    ];
                    $headerAdded['not_found'] = true;
                }

                $firstname = ($userFirstnameMap[$item->user_id] ?? '') ? 'ผู้ดูแล: ' . ($userFirstnameMap[$item->user_id] . ' ') : 'ผู้ดูแล: สาขาเทคโนโลยีคอมพิวเตอร์ ';
                $location = ($locationMap[$item->location_id] ?? '') ? 'ที่อยู่: ' . $locationMap[$item->location_id] : 'ที่อยู่: - ';
                $description = ($item->description ?? '') ? 'คำอธิบาย: ' . $item->description : 'คำอธิบาย: -';

                $result[] = (object)[
                    'index' => $count,
                    'number' => $item->number,
                    'name' => $item->name,
                    'unit_name' => $unitMap[$item->equipment_unit_id] ?? null,
                    'status' => $item->status_not_found,
                    'price' => $item->price,
                    'total_price' => $item->total_price,
                    'description' => ($prefixMap[$userPrefixMap[$item->user_id] ?? null] ?? '')
                        . $firstname
                        . ($userLastnameMap[$item->user_id] ?? '')
                        . $location
                        . $description,
                ];
                $count++;
            }

            return $result;
        });


        return $dataWithExtra;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // ความกว้างของคอลัมน์ A
            'B' => 30,  // ความกว้างของคอลัมน์ B
            'C' => 50,  // ความกว้างของคอลัมน์ C
            'D' => 13,
            'H' => 7,
            'I' => 7,
            'J' => 7,
            'K' => 7,
            'L' => 20
        ];
    }
}
