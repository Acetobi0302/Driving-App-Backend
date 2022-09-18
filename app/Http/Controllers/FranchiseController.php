<?php

namespace App\Http\Controllers;

use App\Models\Franchise;
use Illuminate\Http\Request;

class FranchiseController extends Controller
{
    public function index()
    {
        $franchise = Franchise::all();
        return response()->json(['success' => true, 'data' => $franchise]);
    }

    public function list()
    {
        $franchise = Franchise::all('name','id');
        return response()->json(['success' => true, 'data' => $franchise]);
    }

    public function store(Request $request)
    {
        $validatedData = $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
        ]);

        $franchise = Franchise::Create($validatedData);
        return response()->json(['success' => true, 'data' => $franchise]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
        ]);

        $franchise = Franchise::find($id);
        if ($franchise) {
            $franchise->update($validatedData);
            return response()->json(['success' => true, 'data' => $franchise]);

        }
        return response()->json(['success' => false, 'message' => "Filialen nicht gefunden mit ID: $id."], 404);

    }

    public function delete(Request $request, $id)
    {
        $franchise = Franchise::find($id);
        if ($franchise) {
            $franchise->delete();
            return response()->json(['success' => true]);

        }
        return response()->json(['success' => false, 'message' => "Filialen nicht gefunden mit ID: $id."], 404);

    }

}
