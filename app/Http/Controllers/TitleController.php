<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Equipment_type;
use Illuminate\Http\Request;
use App\Models\Title;

class TitleController extends Controller
{
    public function index()
    {
        return response()->json(Title::all());
    }

    public function store(Request $request)
    {
        // Title::create($request->validate(['name' => 'required']));
        // return response()->noContent();
        $validated = $request->validate([

            'name' => 'required',
            'group' => 'required'
        ]);

        Title::create($validated);
        return response()->noContent();
    }

    public function update(Request $request, Title $title)
    {
        $validated = $request->validate([
            'name' => 'required',
            'group' => 'required'
        ]);

        $title->update($validated);
        return response()->noContent();
    }

    public function destroy(Title $title)
    {
        $title->delete();
        return response()->noContent();
    }

    public function checkUsage(Title $title)
    {
        $inUse = $title->equipments()->exists() || $title->equipment_types()->exists();
        return response()->json(['in_use' => $inUse]);
    }

    public function clone(Title $title)
    {
        $item = Title::findOrFail($title->id);

        Title::create([
            'name' => $item->name . ' - Copy',
            'group' => $item->group . ' - Copy'
        ]);

        $types = Equipment_type::where('title_id', $title->id)->get();
        $oldAndNewTypeId = [];

        foreach($types as $type){
            Equipment_type::create([
                'name' => $type->name,
                'amount' => $type->amount,
                'price' => $type->price,
                'total_price' => $type->total_price,
                'equipment_unit_id' => $type->equipment_unit_id,
                'title_id' => Title::latest('id')->first()->id
            ]);
        $oldAndNewTypeId[$type->id] = Equipment_type::latest('id')->first()->id;
        }

        $equipments = Equipment::where('title_id', $title->id)->get();

        foreach($equipments as $equipment){
            Equipment::create([
                'number' => $equipment->number,
                'name' => $equipment->name,
                'amount' => $equipment->amount,
                'price' => $equipment->price,
                'total_price' => $equipment->total_price,
                'equipment_unit_id' => $equipment->equipment_unit_id,
                'location_id' => $equipment->location_id,
                'equipment_type_id' => $oldAndNewTypeId[$equipment->equipment_type_id] ??  $equipment->equipment_type_id,
                'title_id' => Title::latest('id')->first()->id,
                'user_id' => $equipment->user_id,
                'description' => $equipment->description,
                'original_image_name' => $equipment->original_image_name,
                'stored_image_name' => $equipment->stored_image_name,
                'original_id' => $equipment->original_id ? $equipment->original_id : $equipment->id
            ]);
        }
        // dd($oldAndNewTypeId);

        return redirect()->route('equipment.index', 'title_filter=1&unit_filter=all&location_filter=all&user_filter=all');
    }
}
