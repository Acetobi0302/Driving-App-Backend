<?php

namespace App\Http\Controllers;

use App\Models\CarManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarManagementController extends Controller
{
    public function index(Request $request, $id)
    {
        $data = CarManagement::with('franchise:id,name')->with('users:id,name')->whereHas('franchise', function ($q) use ($id) {
            if ($id != null && $id != 0) {
                $q->where('id', '=', $id);
            }
        })->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'franchise_id' => 'required|exists:franchise,id',
            'art' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->messages()->first()], 422);
        }

        $validatedData = $validator->safe()->all();
        $data = CarManagement::select('id', 'number_plate')->where('franchise_id', '=', $validatedData['franchise_id'])->where('art', $validatedData['art'])->where(function ($query) use ($validatedData) {
            return $query->where('user_id', '=', $validatedData['user_id'])->orWhere('user_id', '=', null);
        })->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function extraList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'franchise_id' => 'required|exists:franchise,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->messages()->first()], 422);
        }

        $validatedData = $validator->safe()->all();
        $data = CarManagement::select('id', 'number_plate')->where('franchise_id', '=', $validatedData['franchise_id'])->where(function ($query) use ($validatedData) {
            return $query->where('user_id', '=', null);
        })->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        $validatedData = $this->validate($request, [
            'manufacturer' => 'required',
            'model' => 'required',
            'color' => 'required',
            'number_plate' => 'required',
            'art' => 'required',
            'franchise_id' => 'required',
            'user_id' => 'nullable|exists:users,id'
        ]);

        $cm = CarManagement::Create($validatedData);
        return response()->json(['success' => true, 'data' => $cm]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $this->validate($request, [
            'manufacturer' => 'required',
            'model' => 'required',
            'color' => 'required',
            'number_plate' => 'required',
            'art' => 'required',
            'franchise_id' => 'required',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $cm = CarManagement::find($id);
        if ($cm) {
            $cm->update($validatedData);
            return response()->json(['success' => true, 'data' => $cm]);
        }
        return response()->json(['success' => false, 'message' => "Auto management ist nicht gefunden mit ID: $id."], 404);
    }

    public function delete(Request $request, $id)
    {
        $cm = CarManagement::find($id);
        if ($cm) {
            $cm->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => "Auto management ist nicht gefunden mit ID: $id."], 404);
    }
}
