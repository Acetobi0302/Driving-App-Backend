<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index(Request $request, $id)
    {
        // if (Auth::user()->role === "receptionist") {
        //     $id = auth()->id();
        // }
        $data = Student::with('franchise:id,name')->whereHas('franchise', function ($q) use ($id) {
            if ($id != null && $id != 0) {
                $q->where('id', '=', $id);
            }
        })->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function list(Request $request, $id)
    {
        $data = Student::select('id', DB::raw("CONCAT(students.first_name,' ',students.last_name) as first_name"))->where('franchise_id', $id)->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sid' => 'required|unique:students,sid',
            'first_name' => 'required',
            'last_name' => 'required',
            'franchise_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->messages()->first()], 422);
        }

        $validatedData = $validator->safe()->all();
        // if (Auth::user()->role === "receptionist") {
        //     $validatedData['franchise_id'] = Auth::user()->franchise_id;
        // }

        $s = Student::Create($validatedData);
        return response()->json(['success' => true, 'data' => $s]);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'sid' => 'required|unique:students,sid,' . $id,
            'first_name' => 'required',
            'last_name' => 'required',
            'franchise_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->messages()->first()], 422);
        }

        $validatedData = $validator->safe()->all();
        // if (Auth::user()->role === "receptionist") {
        //     $validatedData['franchise_id'] = Auth::user()->franchise_id;
        // }
        $s = Student::find($id);
        if ($s) {
            $s->update($validatedData);
            return response()->json(['success' => true, 'data' => $s]);
        }
        return response()->json(['success' => false, 'message' => "Schüler Name nicht gefunden mit ID: $id."], 404);
    }

    public function delete(Request $request, $id)
    {
        $s = Student::find($id);
        if ($s) {
            $s->delete();
            Booking::where('student_id', $id)->update(['deleted_at' => Carbon::now('europe/berlin')]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => "Schüler Name nicht gefunden mit ID: $id."], 404);
    }
}
