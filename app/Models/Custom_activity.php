<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity as BaseActivity;

class Custom_activity extends BaseActivity
{
    public function orderUserProperties()
    {
        $newProperties = json_decode($this->properties, true);
        if (array_key_exists('ข้อมูลก่อนแก้ไข', $newProperties)) {
            $ordered['ข้อมูลก่อนแก้ไข'] = [
                'username' => $this->properties['ข้อมูลก่อนแก้ไข']['username'],
                'prefix' => $this->properties['ข้อมูลก่อนแก้ไข']['prefix'],
                'firstname' => $this->properties['ข้อมูลก่อนแก้ไข']['firstname'],
                'lastname' => $this->properties['ข้อมูลก่อนแก้ไข']['lastname'],
                'user_type' => $this->properties['ข้อมูลก่อนแก้ไข']['user_type'],
            ];

            $ordered['ข้อมูลหลังแก้ไข'] = [
                'username' => $this->properties['ข้อมูลหลังแก้ไข']['username'],
                'prefix' => $this->properties['ข้อมูลหลังแก้ไข']['prefix'],
                'firstname' => $this->properties['ข้อมูลหลังแก้ไข']['firstname'],
                'lastname' => $this->properties['ข้อมูลหลังแก้ไข']['lastname'],
                'user_type' => $this->properties['ข้อมูลหลังแก้ไข']['user_type'],
            ];
        } else {
            $ordered = [
                'username' => $this->properties['username'],
                'prefix' => $this->properties['prefix'],
                'firstname' => $this->properties['firstname'],
                'lastname' => $this->properties['lastname'],
                'user_type' => $this->properties['user_type'],
            ];
        }
        return $ordered;
    }

    public function orderDocumentProperties()
    {
        $newProperties = json_decode($this->properties, true);
        if (array_key_exists('ข้อมูลก่อนแก้ไข', $newProperties)) {
            $ordered['ข้อมูลก่อนแก้ไข'] = [
                'original_name' => $this->properties['ข้อมูลก่อนแก้ไข']['original_name'],
                'document_type' => $this->properties['ข้อมูลก่อนแก้ไข']['document_type'],
                'date'          => $this->properties['ข้อมูลก่อนแก้ไข']['date'],
            ];
            $ordered['ข้อมูลหลังแก้ไข'] = [
                'original_name' => $this->properties['ข้อมูลหลังแก้ไข']['original_name'],
                'document_type' => $this->properties['ข้อมูลหลังแก้ไข']['document_type'],
                'date'          => $this->properties['ข้อมูลหลังแก้ไข']['date'],
            ];
        } else {
            $ordered = [
                'original_name' => $this->properties['original_name'],
                'document_type' => $this->properties['document_type'],
                'date'          => $this->properties['date'],
            ];
        }
        return $ordered;
    }

    public function orderEquipmentProperties()
    {
        $newProperties = json_decode($this->properties, true);
        if (array_key_exists('ข้อมูลก่อนแก้ไข', $newProperties)) {
            $ordered['ข้อมูลก่อนแก้ไข'] = [
                'number' => $this->properties['ข้อมูลก่อนแก้ไข']['number'],
                'name' => $this->properties['ข้อมูลก่อนแก้ไข']['name'],
                'amount' => $this->properties['ข้อมูลก่อนแก้ไข']['amount'],
                'price' => $this->properties['ข้อมูลก่อนแก้ไข']['price'],
                'total_price' => $this->properties['ข้อมูลก่อนแก้ไข']['total_price'],
                'description' => $this->properties['ข้อมูลก่อนแก้ไข']['description'],
                'unit' => $this->properties['ข้อมูลก่อนแก้ไข']['unit'],
                'location' => $this->properties['ข้อมูลก่อนแก้ไข']['location'],
                'type' => $this->properties['ข้อมูลก่อนแก้ไข']['type'],
                'title' => $this->properties['ข้อมูลก่อนแก้ไข']['title'],
                'user' => $this->properties['ข้อมูลก่อนแก้ไข']['user'],
                'original_image_name' => $this->properties['ข้อมูลก่อนแก้ไข']['original_image_name']
            ];
            $ordered['ข้อมูลหลังแก้ไข'] = [
                'number' => $this->properties['ข้อมูลหลังแก้ไข']['number'],
                'name' => $this->properties['ข้อมูลหลังแก้ไข']['name'],
                'amount' => $this->properties['ข้อมูลหลังแก้ไข']['amount'],
                'price' => $this->properties['ข้อมูลหลังแก้ไข']['price'],
                'total_price' => $this->properties['ข้อมูลหลังแก้ไข']['total_price'],
                'description' => $this->properties['ข้อมูลหลังแก้ไข']['description'],
                'unit' => $this->properties['ข้อมูลหลังแก้ไข']['unit'],
                'location' => $this->properties['ข้อมูลหลังแก้ไข']['location'],
                'type' => $this->properties['ข้อมูลหลังแก้ไข']['type'],
                'title' => $this->properties['ข้อมูลหลังแก้ไข']['title'],
                'user' => $this->properties['ข้อมูลหลังแก้ไข']['user'],
                'original_image_name' => $this->properties['ข้อมูลหลังแก้ไข']['original_image_name']
            ];
        } else {
            $ordered = [
                'number' => $this->properties['number'],
                'name' => $this->properties['name'],
                'amount' => $this->properties['amount'],
                'price' => $this->properties['price'],
                'total_price' => $this->properties['total_price'],
                'description' => $this->properties['description'],
                'unit' => $this->properties['unit'],
                'location' => $this->properties['location'],
                'type' => $this->properties['type'],
                'title' => $this->properties['title'],
                'user' => $this->properties['user'],
                'original_image_name' => $this->properties['original_image_name']
            ];
        }
        return $ordered;
    }

    public function orderExportProperties()
    {
        $newProperties = json_decode($this->properties, true);
        if (array_key_exists('query', $newProperties)) {
            $ordered = [
                'title_filter' => $this->properties['title_filter'],
                'unit_filter' => $this->properties['unit_filter'],
                'location_filter' => $this->properties['location_filter'],
                'user_filter' => $this->properties['user_filter'],
                'query' => $this->properties['query']
            ];
        } else {
            $ordered = [
                'title_filter' => $this->properties['title_filter'],
                'unit_filter' => $this->properties['unit_filter'],
                'location_filter' => $this->properties['location_filter'],
                'user_filter' => $this->properties['user_filter']
            ];
        }
        return $ordered;
    }
}
