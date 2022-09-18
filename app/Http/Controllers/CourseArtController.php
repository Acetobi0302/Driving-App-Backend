<?php

namespace App\Http\Controllers;

use App\Models\CourseArt;
use Illuminate\Http\Request;

class CourseArtController extends Controller
{
    public function index(Request $request, $id)
    {
        $data = CourseArt::with('classes:id,name')->whereHas('classes', function ($q) use ($id) {
            if ($id != null && $id != 0) {
                $q->where('id', '=', $id);
            }
        })->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function list(Request $request)
    {
        $data = CourseArt::with('classes:id,name')->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        $validatedData = $this->validate($request, [
            'course_name' => 'required',
            'fees' => 'required',
            'course_time_duration' => 'required',
            'art' => 'required',
            'classes_id' => 'required',
            'exam' => 'required',
        ]);

        $ca = CourseArt::Create($validatedData);
        return response()->json(['success' => true, 'data' => $ca]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $this->validate($request, [
            'course_name' => 'required',
            'fees' => 'required',
            'course_time_duration' => 'required',
            'art' => 'required',
            'classes_id' => 'required',
            'exam' => 'required',
        ]);

        $ca = CourseArt::find($id);
        if ($ca) {
            $ca->update($validatedData);
            return response()->json(['success' => true, 'data' => $ca]);
        }
        return response()->json(['success' => false, 'message' => "Kurz Art nicht gefunden mit ID: $id."], 404);
    }

    public function delete(Request $request, $id)
    {
        $ca = CourseArt::find($id);
        if ($ca) {
            $ca->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => "Kurz Art nicht gefunden mit ID: $id."], 404);
    }
}
