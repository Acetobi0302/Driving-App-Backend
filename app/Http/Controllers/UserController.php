<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'deleted' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->messages()->first()], 422);
        }

        $validatedData = $validator->safe()->all();
        $users = User::with('franchise:id,name')->whereHas('franchise', function ($q) use ($id) {
            if ($id != null && $id != 0) {
                $q->where('id', '=', $id);
            }
        });
        if ($validatedData['deleted'] === "true") {
            $users =  $users->onlyTrashed()->get();
        } else {
            $users =  $users->get();
        }

        return response()->json(['success' => true, 'data' => $users]);
    }

    public function driverList(Request $request, $id)
    {
        $users = User::select('id', 'name')->where('role', 'driver')->with('franchise:id,name')->whereHas('franchise', function ($q) use ($id) {
            if ($id != null && $id != 0) {
                $q->where('id', '=', $id);
            }
        })->get();
        return response()->json(['success' => true, 'data' => $users]);
    }

    public function list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required',
            'franchise_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->messages()->first()], 422);
        }

        $validatedData = $validator->safe()->all();
        $users = User::select('id', 'name')->where('role', $validatedData['role'])->where('franchise_id', $validatedData['franchise_id'])->get();
        return response()->json(['success' => true, 'data' => $users]);
    }

    public function store(Request $request)
    {
        $validatedData = $this->validate($request, [
            'username' => 'required|unique:users',
            'password' => 'required',
            'name' => 'required',
            'role' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'franchise_id' => 'required',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        $user = User::create($validatedData);
        return response()->json(['success' => true, 'data' => $user]);
    }

    public function update(Request $request)
    {
        $validatedData = $this->validate($request, [
            'username' => 'required|unique:users,username,' . auth()->user()->id,
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ]);

        $user = auth()->user();
        if ($user) {
            $user->update($validatedData);
            return response()->json(['success' => true, 'data' => $user]);
        }
        return response()->json(['success' => false, 'message' => "User ist nicht gefunden mit ID: $id."], 404);
    }

    public function updatePassword(Request $request)
    {
        $validatedData = $this->validate($request, [
            'password' => 'required'
        ]);
        $validatedData['password'] = Hash::make($validatedData['password']);

        $user = auth()->user();
        if ($user) {
            $user->update($validatedData);
            return response()->json(['success' => true, 'data' => $user]);
        }
        return response()->json(['success' => false, 'message' => "User ist nicht gefunden"], 404);
    }

    public function userupdate(Request $request, $id)
    {
        $validatedData = $this->validate($request, [
            'username' => 'required|unique:users,username,' . $id,
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'role' => 'required',
            'franchise_id' => 'required',
        ]);

        $user = User::find($id);
        if ($user) {
            $user->update($validatedData);
            return response()->json(['success' => true, 'data' => $user]);
        }
        return response()->json(['success' => false, 'message' => "User ist nicht gefunden mit ID: $id."], 404);
    }

    public function userupdatePassword(Request $request, $id)
    {
        $validatedData = $this->validate($request, [
            'password' => 'required'
        ]);
        $validatedData['password'] = Hash::make($validatedData['password']);
        $user = User::find($id);
        if ($user) {
            $user->update($validatedData);
            return response()->json(['success' => true, 'data' => $user]);
        }
        return response()->json(['success' => false, 'message' => "User ist nicht gefunden mit ID: $id."], 404);
    }

    public function delete(Request $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => "User ist nicht gefunden mit ID: $id."], 404);
    }
    public function restore(Request $request, $id)
    {
        $user = User::withTrashed()->find($id);
        if ($user) {
            $user->restore();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => "User ist nicht gefunden mit ID: $id."], 404);
    }
}
