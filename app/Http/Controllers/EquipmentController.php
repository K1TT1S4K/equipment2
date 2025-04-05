<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Equipment_type;
use App\Models\Equipment_unit;
use App\Models\Location;
use App\Models\Title;
use App\Models\User;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    //
    public function index()
    {
        $users = User::all();
        $equipment_units = Equipment_unit::all();
        $equipment_types = Equipment_type::all();
        $equipments = Equipment::all();
        $locations = Location::all();
        $titles = Title::all();
        return view('livewire.equipments.show', compact('equipments', 'equipment_units', 'equipment_types', 'locations', 'users', 'titles'));
    }
}
