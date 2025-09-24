<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        // try {
        //     return response()->json(Location::all());
        // } catch (\Exception $e) {
        //     return response()->json(['error' => $e->getMessage()], 500);
        // }
        // dd(Location::all());
        return response()->json(Location::all());
    }

    public function store(Request $request)
    {
        Location::create($request->validate(['name' => 'required']));
        return response()->noContent();
    }

    public function update(Request $request, Location $location)
    {
        $location->update($request->validate(['name' => 'required']));
        return response()->noContent();
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return response()->noContent();
    }

    public function checkUsage(Location $location)
    {
        $inUse = $location->equipments()->exists(); // ตรวจว่ามีการใช้จริงหรือไม่
        return response()->json(['in_use' => $inUse]);
    }
}
