<?php

namespace App\Http\Controllers;

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
        Title::create($request->validate(['name' => 'required']));
        return response()->noContent();
    }

    public function update(Request $request, Title $title)
    {
        $title->update($request->validate(['group' => 'required']));
        $title->update($request->validate(['name' => 'required']));
        return response()->noContent();
    }

    public function destroy(Title $title)
    {
        $title->delete();
        return response()->noContent();
    }

    public function checkUsage(Title $title)
    {
        $inUse = $title->equipments()->exists(); // ตรวจว่ามีการใช้จริงหรือไม่
        return response()->json(['in_use' => $inUse]);
    }
}
