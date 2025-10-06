<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity
{
    // public static function orderUserProperties($arr)
    // {
    //     $ordered = [
    //         'username' => $arr['username'],
    //         'prefix' => $arr['prefix'],
    //         'firstname' => $arr['firstname'],
    //         'lastname' => $arr['lastname'],
    //         'user_type' => $arr['user_type'],
    //         'email' => $arr['email'],
    //     ];

    //     return $ordered;
    // }

    // public static function orderDocumentProperties($arr)
    // {
    //     $ordered = [
    //         'original_name' => $arr['original_name'],
    //         'stored_name'   => $arr['stored_name'],
    //         'document_type' => $arr['document_type'],
    //         'date'          => $arr['date'],
    //     ];
    //     return $ordered;
    // }

    // public static function orderEquipmentProperties($arr)
    // {
    //     $ordered = [
    //         'number' => $arr['number'],
    //         'name' => $arr['name'],
    //         'amount' => $arr['amount'],
    //         'price' => $arr['price'],
    //         'total_price' => $arr['total_price'],
    //         // 'status_found' => $arr['status_found'],
    //         // 'status_not_found' => $arr['status_not_found'],
    //         // 'status_broken' => $arr['status_broken'],
    //         // 'status_disposal' => $arr['status_disposal'],
    //         // 'status_transfer' => $arr['status_transfer'],
    //         'description' => $arr['description'],
    //         'unit' => $arr['unit'],
    //         'location' => $arr['location'],
    //         'type' => $arr['type'],
    //         'title' => $arr['title'],
    //         'user' => $arr['user'],
    //     ];
    //     return $ordered;
    // }
}
