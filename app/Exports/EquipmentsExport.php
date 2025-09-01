<?php

namespace App\Exports;

use App\Models\Equipment;
use App\Models\Equipment_unit;
use App\Models\Equipment_type;
use App\Models\User;
use App\Models\Location;
use App\Models\Prefix;
use App\Models\Title;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;

class EquipmentsExport implements FromCollection, WithHeadings, WithColumnWidths, WithEvents, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */

    use Exportable;

    protected $title;

    public function __construct(Title $title)
    {
        $this->title = $title;
    }

    // public function title(): string
    // {
    //     return $this->title->group . "-" . $this->title->name;
    // }

    public function collection()
    {
        $count = 1;

        //ดึงค่าจากตาราง Equipment_unit โดยให้ id เป็น key และให้ name เป็น value
        // หลังจากนั้นเปลี่ยนจาก collection เป็น array ปกติด้วย toArray()
        $unitMap = Equipment_unit::pluck('name', 'id')->toArray();
        $typeNameMap = Equipment_type::pluck('name', 'id')->toArray();
        $typeUnitMap = Equipment_type::pluck('equipment_unit_id', 'id')->toArray();
        $typeAmountMap = Equipment_type::pluck('amount', 'id')->toArray();
        $typePriceMap = Equipment_type::pluck('price', 'id')->toArray();
        $typeTotalPriceMap = Equipment_type::pluck('total_price', 'id')->toArray();
        $userPrefixMap = User::pluck('prefix_id', 'id')->toArray();
        $userFirstnameMap = User::pluck('firstname', 'id')->toArray();
        $userLastnameMap = User::pluck('lastname', 'id')->toArray();
        $prefixMap = Prefix::pluck('name', 'id');
        $locationMap = Location::pluck('name', 'id');
        $data = Equipment::all();

        $displayedTypes = [];

        $dataWithExtra = $data->flatMap(function ($item, $key) use (&$count, $data, $typeNameMap, $typeUnitMap, $typeAmountMap, $typePriceMap, $typeTotalPriceMap, $unitMap, $userPrefixMap, $userFirstnameMap, $userLastnameMap, $prefixMap, $locationMap, &$displayedTypes) {
            $firstname = $userFirstnameMap[$item->user_id] ?? '';
            $firstname = $firstname ? $firstname . " " : $firstname;

            $location = $locationMap[$item->location_id] ?? '';
            $location = ($location && $item->user_id) ? "\n" . $location : $location;

            $description = $item->description ?? '';
            $description = ($description && $item->location_id) ? "\n" . $description : $description;

            $type = $item->equipment_type_id;

            $result = [];

            // if #111
            if (($item->equipment_type_id !== null && !(in_array($item->equipment_type_id, $displayedTypes))) && $item->title_id == $this->title->id) {
                $result[] = (object)[
                    'index' => '',
                    'number' => '',
                    'name' => $typeNameMap[$item->equipment_type_id] ?? null,
                    'unit_name' => ($unitMap[$typeUnitMap[$item->equipment_type_id] ?? null] ?? ''),
                    'amount' => $typeAmountMap[$item->equipment_type_id] ?? null,
                    'price' => $typePriceMap[$item->equipment_type_id] ?? null,
                    'total_price' => $typeTotalPriceMap[$item->equipment_type_id] ?? null
                ];

                foreach ($data->where('equipment_type_id', $type) as $key => $itemList) {
                    // dd($data);
                    $result[] = (object)[
                        'index' => $count,
                        'number' => $itemList->number,
                        'name' => $itemList->name,
                        'unit_name' => $unitMap[$itemList->equipment_unit_id] ?? null,
                        'amount' => $itemList->amount,
                        'price' => $itemList->price,
                        'total_price' => $itemList->total_price,
                        'status_found' => $itemList->status_found,
                        'status_not_found' => $itemList->status_not_found,
                        'status_broken' => $itemList->status_broken,
                        'status_disposal' => $itemList->status_disposal,
                        'description' => ($prefixMap[$userPrefixMap[$itemList->user_id] ?? null] ?? '')
                            . ($firstname)
                            . ($userLastnameMap[$itemList->user_id] ?? '')
                            . ($location)
                            . ($description),
                    ];
                    $count ++;
                }
                $displayedTypes[] = $type;
                return $result;
            } elseif ((!(in_array($item->equipment_type_id, $displayedTypes))) && $item->title_id == $this->title->id) {
                $result[] = (object)[
                    'index' => $count,
                    'number' => $item->number,
                    'name' => $item->name,
                    'unit_name' => $unitMap[$item->equipment_unit_id] ?? null,
                    'amount' => $item->amount,
                    'price' => $item->price,
                    'total_price' => $item->total_price,
                    'status_found' => $item->status_found,
                    'status_not_found' => $item->status_not_found,
                    'status_broken' => $item->status_broken,
                    'status_disposal' => $item->status_disposal,
                    'description' => ($prefixMap[$userPrefixMap[$item->user_id] ?? null] ?? '')
                        // . ($userFirstnameMap[$item->user_id] ?? '')
                        . ($firstname)
                        . ($userLastnameMap[$item->user_id] ?? '')
                        // . ($locationMap[$item->location_id] ?? '')
                        . ($location)
                        // . ($item->description ?? '')
                        . ($description),
                ];
                            $count ++;
            }

            return $result;
        });
        return $dataWithExtra;
    }

    public function headings(): array
    {
        return [
            // แถวที่ 1
            ['ลำดับ', 'หมายเลข', 'ชื่อ', 'หน่วยนับ', 'จำนวน', 'ราคาต่อหน่วย', 'จำนวนเงิน', 'สถานะ', '', '', '', 'หมายเหตุและหรือใช้ประจำที่'],
            // แถวที่ 2
            ['', '', '', '', '', '', '', 'พบ', 'ไม่พบ', 'ชำรุด', 'จำหน่าย', '']
        ];
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // ผสานเซลหัวข้อ "สถานะ"
                $sheet->mergeCells('H1:K1'); // สถานะ
                $sheet->mergeCells('A1:A2'); // ลำดับ
                $sheet->mergeCells('B1:B2'); // หมายเลข
                $sheet->mergeCells('C1:C2'); // ชื่อ
                $sheet->mergeCells('D1:D2'); // หน่วยนับ
                $sheet->mergeCells('E1:E2'); // จำนวน
                $sheet->mergeCells('F1:F2'); // ราคาต่อหน่วย
                $sheet->mergeCells('G1:G2'); // จำนวนเงิน
                $sheet->mergeCells('L1:L2'); // หมายเหตุ

                // จัดกึ่งกลางข้อความ
                $sheet->getStyle('A1:L2')->getAlignment()->setHorizontal('center')->setVertical('center');
                $sheet->getStyle('A1:L2')->getFont()->setBold(true);
            },
        ];
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


    public function styles(Worksheet $sheet)
    {
        // กำหนด style ทั้ง column B และ C
        $sheet->getStyle('A:ZZ')->getAlignment()->setWrapText(true); // ไม่ตัดบรรทัดใหม่

        $sheet->getDefaultRowDimension()->setRowHeight(-1);

        return [ // ตั้งค่าความสูงแถวหัวตาราง
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['bold' => true]],
        ];
    }
}

// class EquipmentsExport implements WithMultipleSheets
// {
//     public function sheets(): array
//     {
//         $data = Title::all();
//         $tab = [];

//         foreach ($data as $title) {
//             $tab[] = new BaseClass($title);
//         }
//         // dd($tab);
//         return $tab;
//     }
// }

// class T1 extends Model implements FromCollection, WithHeadings, WithColumnWidths, WithEvents, WithStyles
// {
//     /**
//      * @return \Illuminate\Support\Collection
//      */

//     public function collection()
//     {
//         $unitMap = Equipment_unit::pluck('name', 'id')->toArray();
//         $typeNameMap = Equipment_type::pluck('name', 'id')->toArray();
//         $typeUnitMap = Equipment_type::pluck('equipment_unit_id', 'id')->toArray();
//         $typeAmountMap = Equipment_type::pluck('amount', 'id')->toArray();
//         $typePriceMap = Equipment_type::pluck('price', 'id')->toArray();
//         $typeTotalPriceMap = Equipment_type::pluck('total_price', 'id')->toArray();
//         $userPrefixMap = User::pluck('prefix_id', 'id')->toArray();
//         $userFirstnameMap = User::pluck('firstname', 'id')->toArray();
//         $userLastnameMap = User::pluck('lastname', 'id')->toArray();
//         $prefixMap = Prefix::pluck('name', 'id');
//         $locationMap = Location::pluck('name', 'id');
//         $data = Equipment::all();

//         $displayedTypes = [];

//         $dataWithExtra = $data->flatMap(function ($item, $key) use ($data, $typeNameMap, $typeUnitMap, $typeAmountMap, $typePriceMap, $typeTotalPriceMap, $unitMap, $userPrefixMap, $userFirstnameMap, $userLastnameMap, $prefixMap, $locationMap, &$displayedTypes) {
//             $firstname = $userFirstnameMap[$item->user_id] ?? '';
//             $firstname = $firstname ? $firstname . " " : $firstname;

//             $location = $locationMap[$item->location_id] ?? '';
//             $location = ($location && $item->user_id) ? "\n" . $location : $location;

//             $description = $item->description ?? '';
//             $description = ($description && $item->location_id) ? "\n" . $description : $description;

//             $type = $item->equipment_type_id;

//             $result = [];

//             // if #111
//             if ($item->equipment_type_id !== null && !(in_array($item->equipment_type_id, $displayedTypes))) {
//                 $result[] = (object)[
//                     'index' => '',
//                     'number' => '',
//                     'name' => $typeNameMap[$item->equipment_type_id] ?? null,
//                     'unit_name' => ($unitMap[$typeUnitMap[$item->equipment_type_id] ?? null] ?? ''),
//                     'amount' => $typeAmountMap[$item->equipment_type_id] ?? null,
//                     'price' => $typePriceMap[$item->equipment_type_id] ?? null,
//                     'total_price' => $typeTotalPriceMap[$item->equipment_type_id] ?? null
//                 ];

//                 foreach ($data->where('equipment_type_id', $type) as $key => $itemList) {
//                     // dd($data);
//                     $result[] = (object)[
//                         'index' => (int)$key + 1,
//                         'number' => $itemList->number,
//                         'name' => $itemList->name,
//                         'unit_name' => $unitMap[$itemList->equipment_unit_id] ?? null,
//                         'amount' => $itemList->amount,
//                         'price' => $itemList->price,
//                         'total_price' => $itemList->total_price,
//                         'status_found' => $itemList->status_found,
//                         'status_not_found' => $itemList->status_not_found,
//                         'status_broken' => $itemList->status_broken,
//                         'status_disposal' => $itemList->status_disposal,
//                         'description' => ($prefixMap[$userPrefixMap[$itemList->user_id] ?? null] ?? '')
//                             . ($firstname)
//                             . ($userLastnameMap[$itemList->user_id] ?? '')
//                             . ($location)
//                             . ($description),
//                     ];
//                 }
//                 $displayedTypes[] = $type;
//                 return $result;
//             } elseif (!(in_array($item->equipment_type_id, $displayedTypes))) {
//                 return [(object)[
//                     'index' => $key + 1,
//                     'number' => $item->number,
//                     'name' => $item->name,
//                     'unit_name' => $unitMap[$item->equipment_unit_id] ?? null,
//                     'amount' => $item->amount,
//                     'price' => $item->price,
//                     'total_price' => $item->total_price,
//                     'status_found' => $item->status_found,
//                     'status_not_found' => $item->status_not_found,
//                     'status_broken' => $item->status_broken,
//                     'status_disposal' => $item->status_disposal,
//                     'description' => ($prefixMap[$userPrefixMap[$item->user_id] ?? null] ?? '')
//                         // . ($userFirstnameMap[$item->user_id] ?? '')
//                         . ($firstname)
//                         . ($userLastnameMap[$item->user_id] ?? '')
//                         // . ($locationMap[$item->location_id] ?? '')
//                         . ($location)
//                         // . ($item->description ?? '')
//                         . ($description),
//                 ]];
//             }
//         });

//         // dd($dataWithExtra);
//         return $dataWithExtra;
//     }

//     public function headings(): array
//     {
//         return [
//             // แถวที่ 1
//             ['ลำดับ', 'หมายเลข', 'ชื่อ', 'หน่วยนับ', 'จำนวน', 'ราคาต่อหน่วย', 'จำนวนเงิน', 'สถานะ', '', '', '', 'หมายเหตุและหรือใช้ประจำที่'],
//             // แถวที่ 2
//             ['', '', '', '', '', '', '', 'พบ', 'ไม่พบ', 'ชำรุด', 'จำหน่าย', '']
//         ];
//     }

//     public function registerEvents(): array
//     {
//         return [
//             \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
//                 $sheet = $event->sheet->getDelegate();

//                 // ผสานเซลหัวข้อ "สถานะ"
//                 $sheet->mergeCells('H1:K1'); // สถานะ
//                 $sheet->mergeCells('A1:A2'); // ลำดับ
//                 $sheet->mergeCells('B1:B2'); // หมายเลข
//                 $sheet->mergeCells('C1:C2'); // ชื่อ
//                 $sheet->mergeCells('D1:D2'); // หน่วยนับ
//                 $sheet->mergeCells('E1:E2'); // จำนวน
//                 $sheet->mergeCells('F1:F2'); // ราคาต่อหน่วย
//                 $sheet->mergeCells('G1:G2'); // จำนวนเงิน
//                 $sheet->mergeCells('L1:L2'); // หมายเหตุ

//                 // จัดกึ่งกลางข้อความ
//                 $sheet->getStyle('A1:L2')->getAlignment()->setHorizontal('center')->setVertical('center');
//                 $sheet->getStyle('A1:L2')->getFont()->setBold(true);
//             },
//         ];
//     }

//     public function columnWidths(): array
//     {
//         return [
//             'A' => 5,   // ความกว้างของคอลัมน์ A
//             'B' => 30,  // ความกว้างของคอลัมน์ B
//             'C' => 50,  // ความกว้างของคอลัมน์ C
//             'D' => 13,
//             'H' => 7,
//             'I' => 7,
//             'J' => 7,
//             'K' => 7,
//             'L' => 20
//         ];
//     }


//     public function styles(Worksheet $sheet)
//     {
//         // กำหนด style ทั้ง column B และ C
//         $sheet->getStyle('A:ZZ')->getAlignment()->setWrapText(true); // ไม่ตัดบรรทัดใหม่

//         $sheet->getDefaultRowDimension()->setRowHeight(-1);

//         return [ // ตั้งค่าความสูงแถวหัวตาราง
//             1 => ['font' => ['bold' => true]],
//             2 => ['font' => ['bold' => true]],
//         ];
//     }
// }

// class T2 extends Model implements FromCollection, WithHeadings, WithColumnWidths, WithEvents, WithStyles
// {
//     /**
//      * @return \Illuminate\Support\Collection
//      */

//     public function collection()
//     {
//         $unitMap = Equipment_unit::pluck('name', 'id')->toArray();
//         $typeNameMap = Equipment_type::pluck('name', 'id')->toArray();
//         $typeUnitMap = Equipment_type::pluck('equipment_unit_id', 'id')->toArray();
//         $typeAmountMap = Equipment_type::pluck('amount', 'id')->toArray();
//         $typePriceMap = Equipment_type::pluck('price', 'id')->toArray();
//         $typeTotalPriceMap = Equipment_type::pluck('total_price', 'id')->toArray();
//         $userPrefixMap = User::pluck('prefix_id', 'id')->toArray();
//         $userFirstnameMap = User::pluck('firstname', 'id')->toArray();
//         $userLastnameMap = User::pluck('lastname', 'id')->toArray();
//         $prefixMap = Prefix::pluck('name', 'id');
//         $locationMap = Location::pluck('name', 'id');
//         $data = Equipment::all();

//         $displayedTypes = [];

//         $dataWithExtra = $data->flatMap(function ($item, $key) use ($data, $typeNameMap, $typeUnitMap, $typeAmountMap, $typePriceMap, $typeTotalPriceMap, $unitMap, $userPrefixMap, $userFirstnameMap, $userLastnameMap, $prefixMap, $locationMap, &$displayedTypes) {
//             $firstname = $userFirstnameMap[$item->user_id] ?? '';
//             $firstname = $firstname ? $firstname . " " : $firstname;

//             $location = $locationMap[$item->location_id] ?? '';
//             $location = ($location && $item->user_id) ? "\n" . $location : $location;

//             $description = $item->description ?? '';
//             $description = ($description && $item->location_id) ? "\n" . $description : $description;

//             $type = $item->equipment_type_id;

//             $result = [];

//             // if #111
//             if ($item->equipment_type_id !== null && !(in_array($item->equipment_type_id, $displayedTypes))) {
//                 $result[] = (object)[
//                     'index' => '',
//                     'number' => '',
//                     'name' => $typeNameMap[$item->equipment_type_id] ?? null,
//                     'unit_name' => ($unitMap[$typeUnitMap[$item->equipment_type_id] ?? null] ?? ''),
//                     'amount' => $typeAmountMap[$item->equipment_type_id] ?? null,
//                     'price' => $typePriceMap[$item->equipment_type_id] ?? null,
//                     'total_price' => $typeTotalPriceMap[$item->equipment_type_id] ?? null
//                 ];

//                 foreach ($data->where('equipment_type_id', $type) as $key => $itemList) {
//                     // dd($data);
//                     $result[] = (object)[
//                         'index' => (int)$key + 1,
//                         'number' => $itemList->number,
//                         'name' => $itemList->name,
//                         'unit_name' => $unitMap[$itemList->equipment_unit_id] ?? null,
//                         'amount' => $itemList->amount,
//                         'price' => $itemList->price,
//                         'total_price' => $itemList->total_price,
//                         'status_found' => $itemList->status_found,
//                         'status_not_found' => $itemList->status_not_found,
//                         'status_broken' => $itemList->status_broken,
//                         'status_disposal' => $itemList->status_disposal,
//                         'description' => ($prefixMap[$userPrefixMap[$itemList->user_id] ?? null] ?? '')
//                             . ($firstname)
//                             . ($userLastnameMap[$itemList->user_id] ?? '')
//                             . ($location)
//                             . ($description),
//                     ];
//                 }
//                 $displayedTypes[] = $type;
//                 return $result;
//             } elseif (!(in_array($item->equipment_type_id, $displayedTypes))) {
//                 return [(object)[
//                     'index' => $key + 1,
//                     'number' => $item->number,
//                     'name' => $item->name,
//                     'unit_name' => $unitMap[$item->equipment_unit_id] ?? null,
//                     'amount' => $item->amount,
//                     'price' => $item->price,
//                     'total_price' => $item->total_price,
//                     'status_found' => $item->status_found,
//                     'status_not_found' => $item->status_not_found,
//                     'status_broken' => $item->status_broken,
//                     'status_disposal' => $item->status_disposal,
//                     'description' => ($prefixMap[$userPrefixMap[$item->user_id] ?? null] ?? '')
//                         // . ($userFirstnameMap[$item->user_id] ?? '')
//                         . ($firstname)
//                         . ($userLastnameMap[$item->user_id] ?? '')
//                         // . ($locationMap[$item->location_id] ?? '')
//                         . ($location)
//                         // . ($item->description ?? '')
//                         . ($description),
//                 ]];
//             }
//         });

//         // dd($dataWithExtra);
//         return $dataWithExtra;
//     }

//     public function headings(): array
//     {
//         return [
//             // แถวที่ 1
//             ['ลำดับ', 'หมายเลข', 'ชื่อ', 'หน่วยนับ', 'จำนวน', 'ราคาต่อหน่วย', 'จำนวนเงิน', 'สถานะ', '', '', '', 'หมายเหตุและหรือใช้ประจำที่'],
//             // แถวที่ 2
//             ['', '', '', '', '', '', '', 'พบ', 'ไม่พบ', 'ชำรุด', 'จำหน่าย', '']
//         ];
//     }

//     public function registerEvents(): array
//     {
//         return [
//             \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
//                 $sheet = $event->sheet->getDelegate();

//                 // ผสานเซลหัวข้อ "สถานะ"
//                 $sheet->mergeCells('H1:K1'); // สถานะ
//                 $sheet->mergeCells('A1:A2'); // ลำดับ
//                 $sheet->mergeCells('B1:B2'); // หมายเลข
//                 $sheet->mergeCells('C1:C2'); // ชื่อ
//                 $sheet->mergeCells('D1:D2'); // หน่วยนับ
//                 $sheet->mergeCells('E1:E2'); // จำนวน
//                 $sheet->mergeCells('F1:F2'); // ราคาต่อหน่วย
//                 $sheet->mergeCells('G1:G2'); // จำนวนเงิน
//                 $sheet->mergeCells('L1:L2'); // หมายเหตุ

//                 // จัดกึ่งกลางข้อความ
//                 $sheet->getStyle('A1:L2')->getAlignment()->setHorizontal('center')->setVertical('center');
//                 $sheet->getStyle('A1:L2')->getFont()->setBold(true);
//             },
//         ];
//     }

//     public function columnWidths(): array
//     {
//         return [
//             'A' => 5,   // ความกว้างของคอลัมน์ A
//             'B' => 30,  // ความกว้างของคอลัมน์ B
//             'C' => 50,  // ความกว้างของคอลัมน์ C
//             'D' => 13,
//             'H' => 7,
//             'I' => 7,
//             'J' => 7,
//             'K' => 7,
//             'L' => 20
//         ];
//     }


//     public function styles(Worksheet $sheet)
//     {
//         // กำหนด style ทั้ง column B และ C
//         $sheet->getStyle('A:ZZ')->getAlignment()->setWrapText(true); // ไม่ตัดบรรทัดใหม่

//         $sheet->getDefaultRowDimension()->setRowHeight(-1);

//         return [ // ตั้งค่าความสูงแถวหัวตาราง
//             1 => ['font' => ['bold' => true]],
//             2 => ['font' => ['bold' => true]],
//         ];
//     }
// }