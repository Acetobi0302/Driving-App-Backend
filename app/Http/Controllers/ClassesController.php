<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;

class ClassesController extends Controller
{
    public function index()
    {
        $classes = Classes::all();
        return response()->json(['success' => true, 'data' => $classes]);
    }

    public function store(Request $request)
    {
        $validatedData = $this->validate($request, [
            'name' => 'required',
        ]);

        $classes = Classes::Create($validatedData);
        return response()->json(['success' => true, 'data' => $classes]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $this->validate($request, [
            'name' => 'required',
        ]);

        $classes = Classes::find($id);
        if ($classes) {
            $classes->update($validatedData);
            return response()->json(['success' => true, 'data' => $classes]);

        }
        return response()->json(['success' => false, 'message' => "Klassen nicht gefunden mit ID: $id."], 404);

    }

    public function delete(Request $request, $id)
    {
        $classes = Classes::find($id);
        if ($classes) {
            $classes->delete();
            return response()->json(['success' => true]);

        }
        return response()->json(['success' => false, 'message' => "Klassen nicht gefunden mit ID: $id."], 404);

    }
}
