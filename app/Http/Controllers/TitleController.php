<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Equipment_unit;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Title;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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

            'name' => 'required'
        ]);

        Title::create($validated);
        return response()->noContent();
    }

    public function update(Request $request, Title $title)
    {
        $validated = $request->validate([
            'name' => 'required'
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
        $inUse = $title->equipments()->exists();
        return response()->json(['in_use' => $inUse]);
    }

    public function clone(Title $title)
    {
        $item = Title::findOrFail($title->id);
        $unitMap = [];
        $userMap = [];
        $locationMap = [];
        Title::create([
            'name' => $item->name . ' - Copy'
        ]);

        $item->update(['is_locked' => 1]);

        $equipments = Equipment::where('title_id', $title->id)->get();

        foreach ($equipments as $equipment) {
            if ($equipment->user_id) {
                $user = User::where('id', $equipment->user_id)->get()->toArray();
                User::create(['prefix_id' => $user[0]['prefix_id'], 'firstname' => $user[0]['firstname'], 'lastname' => $user[0]['lastname'], 'user_type' => $user[0]['user_type'], 'is_locked' => 1]);
                $userMap[$user[0]['id']] = User::latest('id')->first()->id;
            }
            if ($equipment->location_id) {
                $location = Location::where('id', $equipment->location_id)->get()->toArray();
                Location::create(['name' => $location[0]['name'], 'is_locked' => 1]);
                $locationMap[$location[0]['id']] = Location::latest('id')->first()->id;
            }
            if ($equipment->equipment_unit_id) {
                $unit = Equipment_unit::where('id', $equipment->equipment_unit_id)->get()->toArray();
                Equipment_unit::create(['name' => $unit[0]['name'], 'is_locked' => 1]);
                $unitMap[$unit[0]['id']] = Equipment_unit::latest('id')->first()->id;
            }
        }
        // dd($userMap, $unitMap, $locationMap);

        foreach ($equipments as $equipment) {
            if (
                $equipment->amount
                - (
                    ($equipment->status_disposal ?? 0)
                    + ($equipment->status_not_found ?? 0)
                    + ($equipment->status_transfer ?? 0)
                ) > 0
            ) {

                if ($equipment->stored_image_name) {
                    $name = Str::beforeLast($equipment->stored_image_name, '.'); // name
                    $ext  = Str::afterLast($equipment->stored_image_name, '.');  // pdf

                    Storage::disk('public')->copy($equipment->stored_image_name, $name . '-copy' . $ext);
                }

                Equipment::create([
                    'number' => $equipment->number,
                    'name' => $equipment->name,
                    'amount' => $equipment->amount
                        - (
                            ($equipment->status_disposal ?? 0)
                            + ($equipment->status_not_found ?? 0)
                            + ($equipment->status_transfer ?? 0)
                        ),
                    'status_found' => 0,
                    'status_not_found' => 0,
                    'status_broken' => 0,
                    'status_disposal' => 0,
                    'status_transfer' => 0,
                    'price' => $equipment->price,
                    'total_price' => $equipment->price * $equipment->amount,
                    'equipment_unit_id' => $equipment->equipment_unit_id ? $unitMap[$equipment->equipment_unit_id] : null,
                    'location_id' => $equipment->location_id ? $locationMap[$equipment->location_id] : null,
                    'title_id' => Title::latest('id')->first()->id,
                    'user_id' => $equipment->user_id ? $userMap[$equipment->user_id] : null,
                    'description' => $equipment->description,
                    'original_image_name' => $equipment->original_image_name ? $equipment->original_image_name : null,
                    'stored_image_name' => $equipment->stored_image_name ? $name . '-copy' . $ext : null,
                    'created_at' => $equipment->created_at,
                    'updated_at' => $equipment->updated_at
                ]);
            }
        }

        Equipment::where('title_id', $title->id)->update(['is_locked' => 1]);

        return redirect()->route('equipment.index', 'title_filter=1&unit_filter=all&location_filter=all&user_filter=all');
    }
}
